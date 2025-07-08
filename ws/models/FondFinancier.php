<?php
require_once __DIR__ . '/../db.php';

class FondFinancier {

  public static function create($data) {
    $db = getDB();

    // Extraire année et mois depuis la date_creation
    $dateCreation = $data->date_creation; // ex: "2025-07-08"
    $annee = (int) date('Y', strtotime($dateCreation));
    $mois = (int) date('m', strtotime($dateCreation));

    // Vérifier si un fond existe déjà pour ce mois et année
    $stmt = $db->prepare("SELECT * FROM EF_Fond_Financier WHERE annee = ? AND mois = ?");
    $stmt->execute([$annee, $mois]);
    $existant = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existant) {
        // Mise à jour du solde initial si même mois+année
        $stmt = $db->prepare("UPDATE EF_Fond_Financier SET solde_initiale = solde_initiale + ? WHERE annee = ? AND mois = ?");
        return $stmt->execute([$data->solde_initiale, $annee, $mois]);
    } else {
        // Insertion normale
        $stmt = $db->prepare("INSERT INTO EF_Fond_Financier (date_creation, solde_initiale, solde_final, annee, mois) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $dateCreation,
            $data->solde_initiale,
            $data->solde_final ?? 0,
            $annee,
            $mois
        ]);
    }
}

    public static function findAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM EF_Fond_Financier ORDER BY annee DESC, mois DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByMoisAnnee($mois, $annee) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM EF_Fond_Financier WHERE mois = ? AND annee = ?");
        $stmt->execute([$mois, $annee]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




}


