<?php
require_once __DIR__ . '/../db.php';

class FondFinancier {
    public static function create($data) {
        $db = getDB();

        // Vérifier si un fond existe déjà pour cette année
        $stmt = $db->prepare("SELECT * FROM EF_Fond_Financier WHERE annee = YEAR(?)");
        $stmt->execute([$data->date_creation]);
        $existant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existant) {
            // Mise à jour du solde initial si même année
            $stmt = $db->prepare("UPDATE EF_Fond_Financier SET solde_initiale = solde_initiale + ? WHERE annee = YEAR(?)");
            return $stmt->execute([$data->solde_initiale, $data->date_creation]);
        } else {
            // Insertion normale
            $stmt = $db->prepare("INSERT INTO EF_Fond_Financier (date_creation, solde_initiale, solde_final) VALUES (?, ?, ?)");
            return $stmt->execute([
                $data->date_creation,
                $data->solde_initiale,
                $data->solde_final ?? 0
            ]);
        }
    }

    public static function findAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM EF_Fond_Financier ORDER BY annee DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByAnnee($annee) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM EF_Fond_Financier WHERE annee = ?");
        $stmt->execute([$annee]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
