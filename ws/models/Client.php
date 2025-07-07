<?php
require_once __DIR__ . '/../db.php'; // Assurez-vous d'avoir ce fichier pour la connexion BDD

class Client {

    public static function getDashboardSummary($idClient) {
        $db = getDB();
        // On utilise LEFT JOIN pour s'assurer qu'on a un résultat même si le client n'a pas de prêt en cours.
        $stmt = $db->prepare("
            SELECT
                c.solde AS solde_client,
                pc.montant_restant,
                (pc.montant_paye + pc.montant_restant) AS montant_total_pret
            FROM
                EF_Client c
            LEFT JOIN
                EF_Pret_Client pc ON c.idClient = pc.idClient AND pc.status != 3 -- On exclut les prêts terminés
            WHERE
                c.idClient = ?
            ORDER BY pc.date_debut_pret DESC
            LIMIT 1;
        ");
        $stmt->execute([$idClient]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les détails du prêt en cours d'un client.
     */
    public static function getCurrentLoan($idClient) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT
                pc.idPret,
                pc.date_debut_pret,
                pc.montant_paye,
                pc.montant_restant,
                pc.status,
                tp.libelle AS type_pret_libelle,
                tp.montant AS type_pret_montant_initial,
                tp.taux AS type_pret_taux
            FROM
                EF_Pret_Client pc
            JOIN
                EF_TypePret tp ON pc.idTypePret = tp.idType
            WHERE
                pc.idClient = ? AND pc.status != 3 -- Exclure les prêts terminés (status 3 = payé)
            ORDER BY pc.date_debut_pret DESC
            LIMIT 1;
        ");
        $stmt->execute([$idClient]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}