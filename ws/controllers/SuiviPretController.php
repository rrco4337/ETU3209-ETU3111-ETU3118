<?php
require_once __DIR__ . '/../models/SuiviPret.php';

class SuiviPretController {
   public static function getInterets() {
    $query = Flight::request()->query;

    $moisDebut = $query->moisDebut ?? 1;
    $anneeDebut = $query->anneeDebut ?? 2000;
    $moisFin = $query->moisFin ?? date('n');
    $anneeFin = $query->anneeFin ?? date('Y');

    $resultats = SuiviPret::getInteretsParMois($moisDebut, $anneeDebut, $moisFin, $anneeFin);
    Flight::json($resultats);
}
 
}