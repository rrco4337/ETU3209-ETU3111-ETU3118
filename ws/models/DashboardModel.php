<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers/Utils.php';



class DashboardModel {
    public static function getResume($idClient) {
        $db = getDB();

        // Récupère le solde actuel
        $stmt = $db->prepare("SELECT montant FROM Prevision_Client WHERE idClient = ?");
        $stmt->execute([$idClient]);
        $solde = $stmt->fetchColumn() ?: 0;

        return ['solde_disponible' => $solde];
    }

    public static function getPretsParType($idClient) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT p.*, t.libelle as type_pret
            FROM EF_Pret_Client p
            JOIN EF_TypePret t ON p.idTypePret = t.idType
            WHERE p.idClient = ?
        ");
        $stmt->execute([$idClient]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouterSolde($idClient, $montant) {
        $db = getDB();

        $stmt = $db->prepare("
            INSERT INTO Prevision_Client (idClient, montant)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE montant = montant + VALUES(montant)
        ");
        return $stmt->execute([$idClient, $montant]);
    }

    public static function soumettrePaiement($idClient, $idTypePret, $montant, $date) {
        $db = getDB();

        // Vérifie le prêt actif
        $stmt = $db->prepare("SELECT * FROM EF_Pret_Client 
            WHERE idClient = ? AND idTypePret = ? AND isApproved = 1");
        $stmt->execute([$idClient, $idTypePret]);
        $pret = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$pret) return ['error' => 'Aucun prêt actif trouvé'];

        $idPret = $pret['idPret'];

        // Met à jour le suivi : ajoute montant et met à jour EF_Pret_Client
        $stmt = $db->prepare("SELECT * FROM EF_SuiviPret 
            WHERE idPret = ? AND montant_paye < montant_attendu
            ORDER BY date_prevu_payement ASC LIMIT 1");
        $stmt->execute([$idPret]);
        $suivi = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$suivi) return ['error' => 'Aucune échéance trouvée'];

        $reste = $suivi['montant_attendu'] - $suivi['montant_paye'];
        $paiement = min($montant, $reste);

        // Mise à jour SuiviPret
        $stmt = $db->prepare("UPDATE EF_SuiviPret 
            SET montant_paye = montant_paye + ? 
            WHERE idSuivi = ?");
        $stmt->execute([$paiement, $suivi['idSuivi']]);

        // Mise à jour du prêt
        $stmt = $db->prepare("UPDATE EF_Pret_Client 
            SET montant_paye = montant_paye + ?, montant_restant = montant_restant - ? 
            WHERE idPret = ?");
        $stmt->execute([$paiement, $paiement, $idPret]);

        // Met à jour le solde disponible
        $stmt = $db->prepare("UPDATE Prevision_Client 
            SET montant = montant - ? WHERE idClient = ?");
        $stmt->execute([$paiement, $idClient]);

        return ['success' => true];
    }
}

