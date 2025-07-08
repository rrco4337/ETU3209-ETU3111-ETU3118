<?php
require_once __DIR__ . '/../db.php';

class Pret {
public static function create($data) {
    $db = getDB();

    // 1. Infos Type de prêt
    $stmt1 = $db->prepare("SELECT * FROM EF_TypePret WHERE idType = ?");
    $stmt1->execute([$data->idTypePret]);
    $typePret = $stmt1->fetch(PDO::FETCH_ASSOC);

    // 2. Infos Client
    $stmt2 = $db->prepare("SELECT * FROM EF_Client WHERE idClient = ?");
    $stmt2->execute([$data->idClient]);
    $client = $stmt2->fetch(PDO::FETCH_ASSOC);

    if (!$typePret || !$client) return false;

    // 3. Données pour calcul
    $C = $typePret["montant"];
    $t = $typePret["taux"] / 100;
    $d = $typePret["duree_mois_max"];
    $salaire = $client["salaire_actuel"];

    // 4. Intérêt simple
    $interet = $C * $t * ($d / 12);
    $total_a_rembourser = $C + $interet;
    $capacite = $salaire * $d;

    // 5. Vérification capacité de remboursement
    $isApproved = ($capacite >= $total_a_rembourser) ? 1 : 0;

    if ($isApproved) {
        $stmt = $db->prepare("INSERT INTO EF_Pret_Client (
            idTypePret, idClient, status, date_debut_pret,
            montant_paye, montant_restant, interet_total, date_maj, isApproved
        ) VALUES (?, ?, 0, CURRENT_DATE, 0, ?, ?, CURRENT_DATE, 0)");

        $success = $stmt->execute([
            $data->idTypePret,
            $data->idClient,
            $C,           // montant_restant : capital sans intérêt
            $interet      // intérêt total
        ]);

        return $success;
    } else {
        return ['error' => "Vous n'êtes pas en mesure de souscrire à ce prêt."];
    }
}

    public static function findAll() {
        $db = getDB();
        $stmt = $db->query("
            SELECT pc.*, tp.libelle, tp.montant AS montant_type,
                   c.nom, c.salaire_actuel
            FROM EF_Pret_Client pc
            JOIN EF_TypePret tp ON pc.idTypePret = tp.idType
            JOIN EF_Client c ON pc.idClient = c.idClient
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function findNonApproved() {
    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, c.nom AS client_nom, t.libelle AS type_pret_libelle 
                          FROM EF_Pret_Client p
                          JOIN EF_Client c ON p.idClient = c.idClient
                          JOIN EF_TypePret t ON p.idTypePret = t.idType
                          WHERE p.isApproved = 0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public static function approuver($idPret) {
    $db = getDB();

    // 1. Approuver le prêt
    $stmt = $db->prepare("UPDATE EF_Pret_Client SET isApproved = 1 WHERE idPret = ?");
    $stmt->execute([$idPret]);

    // 2. Récupération des infos du prêt
    $stmt = $db->prepare("SELECT p.*, t.duree_mois_max, t.taux, t.montant
                          FROM EF_Pret_Client p
                          JOIN EF_TypePret t ON p.idTypePret = t.idType
                          WHERE p.idPret = ?");
    $stmt->execute([$idPret]);
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$pret) return false;

    $idClient = $pret['idClient'];
    $duree = $pret['duree_mois_max'];
    $capital = $pret['montant'];
    $taux = $pret['taux'] / 100;
    $dateDebut = new DateTime($pret['date_debut_pret']);

    // 3. Calcul des valeurs mensuelles (annuité constante)
    $tMensuel = $taux / 12;
    $annuite = round($capital * ($tMensuel / (1 - pow(1 + $tMensuel, -$duree))), 2);

    $capitalRestant = $capital;

    // 4. Préparation de l'insertion
  $stmtInsert = $db->prepare("INSERT INTO EF_SuiviPret (
    idPret, idClient, montant_attendu, montant_paye, interet_paye, interet_a_payer,
    annuite, amortissement,
    date_debut_pret, date_prevu_payement
) VALUES (?, ?, ?, 0, 0, ?, ?, ?, ?, ?)");

    for ($i = 1; $i <= $duree; $i++) {
    $datePaiement = clone $dateDebut;
    $datePaiement->modify("+$i month");

    $interetMensuel = round($capitalRestant * $tMensuel, 2);
    $amortissement = round($annuite - $interetMensuel, 2);

    $stmtInsert->execute([
        $idPret,
        $idClient,
        $annuite,           // montant_attendu = annuité
        $interetMensuel,    // interet_a_payer
        $annuite,           // annuite
        $amortissement,     // amortissement
        $dateDebut->format('Y-m-d'),
        $datePaiement->format('Y-m-d')
    ]);

    $capitalRestant -= $amortissement;
}
    return true;
}
public static function payerEcheance($idClient, $idPret, $datePaiement) {
    $db = getDB();

    // 1. Récupérer le solde du client
    $stmtSolde = $db->prepare("SELECT montant FROM Prevision_Client WHERE idClient = ?");
    $stmtSolde->execute([$idClient]);
    $solde = (float) $stmtSolde->fetchColumn();

    if ($solde <= 0) {
        return ['error' => true, 'message' => 'Solde insuffisant'];
    }

    // 2. Récupérer la première échéance non payée
    $stmtEch = $db->prepare("
        SELECT * FROM EF_SuiviPret
        WHERE idClient = ? AND idPret = ? AND montant_paye < montant_attendu
        ORDER BY date_prevu_payement ASC LIMIT 1");
    $stmtEch->execute([$idClient, $idPret]);
    $echeance = $stmtEch->fetch(PDO::FETCH_ASSOC);

    if (!$echeance) {
        return ['error' => true, 'message' => 'Aucune échéance à payer'];
    }

    // 3. Calculer les montants à payer
    $amortissement = (float) $echeance['amortissement'];
    $interet = (float) $echeance['interet_a_payer'];
    $montantTotal = $amortissement + $interet;

    // 4. Vérifier si le solde est suffisant
    if ($solde < $montantTotal) {
        return ['error' => true, 'message' => 'Solde insuffisant pour payer l’échéance (' . $montantTotal . ' MGA requis)'];
    }

    // 5. Déduire du solde du client
    $stmtUpdateSolde = $db->prepare("UPDATE Prevision_Client SET montant = montant - ? WHERE idClient = ?");
    $stmtUpdateSolde->execute([$montantTotal, $idClient]);

    // 6. Mettre à jour EF_SuiviPret
    $stmtUpdateEch = $db->prepare("
        UPDATE EF_SuiviPret
        SET montant_paye = ?, interet_paye = ?
        WHERE idSuivi = ?");
    $stmtUpdateEch->execute([$amortissement, $interet, $echeance['idSuivi']]);

    // 7. Mettre à jour EF_Pret_Client
    $stmtMajPret = $db->prepare("
        UPDATE EF_Pret_Client
        SET montant_paye = montant_paye + ?, montant_restant = montant_restant - ?
        WHERE idPret = ?");
    $stmtMajPret->execute([$montantTotal, $amortissement, $idPret]);

    return ['error' => false, 'message' => 'Paiement effectué avec succès'];
}

}
