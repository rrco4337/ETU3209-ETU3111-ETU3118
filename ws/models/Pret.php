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
    $tauxAssurance = $typePret["taux_assurance"] / 100;

    // 4. Intérêt simple + assurance
    $interet = $C * $t * ($d / 12);
    $assuranceTotale = $C * $tauxAssurance;
    $total_a_rembourser = $C + $interet + $assuranceTotale;

    $capacite = $salaire * $d;


    // 5. Vérification capacité de remboursement
    $isApproved = ($capacite >= $total_a_rembourser) ? 1 : 0;

    if ($isApproved) {
       
        $stmt = $db->prepare("INSERT INTO EF_Pret_Client (
            idTypePret, idClient, status, date_debut_pret,
            montant_paye, montant_total, interet_total, assurance_total,
            date_maj, isApproved
        ) VALUES (?, ?, 0, CURRENT_DATE, 0, ?, ?, ?, CURRENT_DATE, 0)");

        $success = $stmt->execute([
            $data->idTypePret,
            $data->idClient,
            $C,
            $interet,
            $assuranceTotale
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
public static function approuver($idPret, $delaiMois) {
    $db = getDB();
  file_put_contents("log.txt", "Capacité: $delaiMois", FILE_APPEND);
    // Validation du délai
    $delaiMois = (int)$delaiMois;
    if ($delaiMois < 0 || $delaiMois > 6) {
        throw new Exception("Le délai doit être compris entre 0 et 6 mois");
    }

    // 1. Marquer le prêt comme approuvé
    $stmt = $db->prepare("UPDATE EF_Pret_Client SET isApproved = 1 WHERE idPret = ?");
    $stmt->execute([$idPret]);

    // 2. Récupérer les infos nécessaires
    $stmt = $db->prepare("SELECT p.*, t.duree_mois_max, t.taux, t.taux_assurance, t.montant
                          FROM EF_Pret_Client p
                          JOIN EF_TypePret t ON p.idTypePret = t.idType
                          WHERE p.idPret = ?");
    $stmt->execute([$idPret]);
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$pret) return false;

    $idClient = $pret['idClient'];
    $duree = $pret['duree_mois_max'];
    $capital = $pret['montant'];


$stmtUpdateFond = $db->prepare("
    UPDATE EF_Fond_Financier
    SET solde_initiale = solde_initiale - ?
    WHERE mois = MONTH(?) AND annee = YEAR(?)");
$stmtUpdateFond->execute([$capital, $dateDebut, $dateDebut]);

    $taux = $pret['taux'] / 100;
    $tauxAssurance = $pret['taux_assurance'] / 100;

    $dateDebut = new DateTime($pret['date_debut_pret']);
    $dateDebut->modify("+$delaiMois months");

    // 3. Calcul des mensualités
    $tMensuel = $taux / 12;
    $annuite = round($capital * ($tMensuel / (1 - pow(1 + $tMensuel, -$duree))), 2);
    $assuranceMensuelle = round($capital * $tauxAssurance / $duree, 2);
    $capitalRestant = $capital;

    // 4. Insertion dans EF_SuiviPret
    $stmtInsert = $db->prepare("INSERT INTO EF_SuiviPret (
        idPret, idClient, montant_attendu, montant_paye, interet_paye, interet_a_payer,
        annuite, amortissement, assurance_a_payer,
        date_debut_pret, date_prevu_payement
    ) VALUES (?, ?, ?, 0, 0, ?, ?, ?, ?, ?, ?)");

    for ($i = 1; $i <= $duree; $i++) {
        $datePaiement = clone $dateDebut;
        $datePaiement->modify("+$i month");

        $interetMensuel = round($capitalRestant * $tMensuel, 2);
        $amortissement = round($annuite - $interetMensuel, 2);

        $stmtInsert->execute([
            $idPret,
            $idClient,
            $annuite + $assuranceMensuelle,
            $interetMensuel,
            $annuite,
            $amortissement,
            $assuranceMensuelle,
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
        WHERE idClient = ? AND idPret = ? AND montant_paye + interet_paye < montant_attendu
        ORDER BY date_prevu_payement ASC LIMIT 1");
    $stmtEch->execute([$idClient, $idPret]);
    $echeance = $stmtEch->fetch(PDO::FETCH_ASSOC);

    if (!$echeance) {
        return ['error' => true, 'message' => 'Aucune échéance à payer'];
    }

    // 3. Récupérer les composants de l’échéance
    $amortissement = (float) $echeance['amortissement'];
    $interet = (float) $echeance['interet_a_payer'];
    $assurance = (float) $echeance['assurance_a_payer'];
    $montantAttendu = (float) $echeance['montant_attendu'];

    // 4. Vérifier si le solde est suffisant
    if ($solde < $montantAttendu) {
        return ['error' => true, 'message' => "Solde insuffisant pour ce paiement ($montantAttendu MGA requis)"];
    }

    // 5. Déduire le solde du client
    $stmtUpdateSolde = $db->prepare("UPDATE Prevision_Client SET montant = montant - ? WHERE idClient = ?");
    $stmtUpdateSolde->execute([$montantAttendu, $idClient]);

    // 6. Mise à jour de EF_SuiviPret
    $stmtUpdateEch = $db->prepare("
        UPDATE EF_SuiviPret
        SET montant_paye = ?, interet_paye = ?
        WHERE idSuivi = ?");
    $stmtUpdateEch->execute([
        $amortissement + $assurance, // ce que le client rembourse (hors intérêt)
        $interet,                    // intérêt séparé
        $echeance['idSuivi']
    ]);

    // 7. Mise à jour du prêt client
    $stmtMajPret = $db->prepare("
        UPDATE EF_Pret_Client
        SET montant_paye = montant_paye + ?
        WHERE idPret = ?");
    $stmtMajPret->execute([
        $montantAttendu,  // Total versé ce mois (amortissement + intérêt + assurance)
        $idPret
    ]);

    // ---- Nouvelle étape : Mise à jour du solde dans EF_Fond_Financier ----
    // Extraire mois et année de la date de paiement prévu
    $datePrevu = new DateTime($echeance['date_prevu_payement']);
    $mois = (int)$datePrevu->format('m');
    $annee = (int)$datePrevu->format('Y');

    // Mettre à jour le solde_final dans EF_Fond_Financier pour ce mois et année
    $montantAPayer = $amortissement + $interet + $assurance;

    $stmtUpdateFond = $db->prepare("
        UPDATE EF_Fond_Financier
        SET solde_final = solde_final + ?
        WHERE mois = ? AND annee = ?");
    $stmtUpdateFond->execute([$montantAPayer, $mois, $annee]);

    return ['error' => false, 'message' => 'Paiement effectué avec succès'];
}



}
