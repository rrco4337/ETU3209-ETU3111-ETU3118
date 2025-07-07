<?php
require_once __DIR__ . '/../db.php';

class OffrePret {
    /**
     * Récupère toutes les offres de prêt disponibles.
     */
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT idType, libelle, montant, taux, duree_mois_max FROM EF_TypePret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}