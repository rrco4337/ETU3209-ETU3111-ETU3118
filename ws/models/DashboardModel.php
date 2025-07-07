<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers/Utils.php';

class DashboardModel {

    // Regrouper tous les prêts d’un client par type de prêt
    public static function getLoansByClientGroupedByType($idClient) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT t.libelle AS type_pret, pc.*
            FROM EF_Pret_Client pc
            JOIN EF_TypePret t ON t.idType = pc.idTypePret
            WHERE pc.idClient = ?
            ORDER BY t.libelle
        ");
        $stmt->execute([$idClient]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public static function getAllLoansByClient($idClient) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT 
            pc.idPret,
            pc.idClient,
            pc.idTypePret,
            t.libelle AS type_pret,
            t.montant AS montant_initial,
            t.taux,
            t.duree_mois_max,
            pc.status,
            pc.date_debut_pret,
            pc.montant_paye,
            pc.montant_restant,
            pc.date_maj
        FROM EF_Pret_Client pc
        JOIN EF_TypePret t ON t.idType = pc.idTypePret
        WHERE pc.idClient = ?
        ORDER BY pc.date_debut_pret DESC
    ");
    $stmt->execute([$idClient]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Récupérer le résumé de prêt du client (solde dispo, montant restant, total prêt)
    public static function getClientLoanSummary($idClient) {
        $db = getDB();

        // Solde disponible
        $soldeStmt = $db->prepare("SELECT solde FROM EF_Client WHERE idClient = ?");
        $soldeStmt->execute([$idClient]);
        $solde_dispo = $soldeStmt->fetchColumn();

        // Montant total payé par ce client
        $payeStmt = $db->prepare("SELECT COALESCE(SUM(montant), 0) FROM EF_Payement WHERE id_client = ?");
        $payeStmt->execute([$idClient]);
        $total_paye = $payeStmt->fetchColumn();

        // Montant total des prêts (total initial emprunté)
        $pretStmt = $db->prepare("SELECT COALESCE(SUM(t.montant), 0)
                                  FROM EF_Pret_Client pc
                                  JOIN EF_TypePret t ON t.idType = pc.idTypePret
                                  WHERE pc.idClient = ?");
        $pretStmt->execute([$idClient]);
        $total_pret = $pretStmt->fetchColumn();

        $montant_restant = $total_pret - $total_paye;

        return [
            'solde_disponible' => $solde_dispo,
            'montant_restant' => $montant_restant,
            'montant_total_pret' => $total_pret
        ];
    }

    // Effectuer un paiement mensuel
    public static function payLoanByMonth($idClient, $idTypePret, $mois, $montant) {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO EF_Payement (id_client, id_type_pret, mois, montant, date_paiement)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$idClient, $idTypePret, $mois, $montant]);
    }
}
