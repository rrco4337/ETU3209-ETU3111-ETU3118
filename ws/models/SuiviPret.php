<?php
require_once __DIR__ . '/../db.php';

class SuiviPret {

public static function getInteretsParMois($moisDebut, $anneeDebut, $moisFin, $anneeFin) {
    $db = getDB();

    // Construire les dates début et fin
    $dateDebut = sprintf("%04d-%02d-01", $anneeDebut, $moisDebut);
    $dateFin = date("Y-m-t", strtotime(sprintf("%04d-%02d-01", $anneeFin, $moisFin)));

    $stmt = $db->prepare("
        SELECT 
            YEAR(date_prevu_payement) AS annee,
            MONTH(date_prevu_payement) AS mois,
            SUM(interet_paye) AS total_interet
        FROM EF_SuiviPret
        WHERE date_prevu_payement BETWEEN ? AND ?
        GROUP BY YEAR(date_prevu_payement), MONTH(date_prevu_payement)
        ORDER BY annee, mois
    ");
    
    $stmt->execute([$dateDebut, $dateFin]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}

?>
