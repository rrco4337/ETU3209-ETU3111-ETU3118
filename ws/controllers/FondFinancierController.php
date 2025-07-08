<?php
require_once __DIR__ . '/../models/FondFinancier.php';

class FondFinancierController {
    public static function getAll() {
        $fonds = FondFinancier::findAll();
        Flight::json($fonds);
    }

    public static function getByMoisAnnee($mois,$annee) {
        $fond = FondFinancier::findByMoisAnnee($mois,$annee);
        Flight::json($fond);
    }

     
    public static function create() {
        $data = Flight::request()->data;
        FondFinancier::create($data);
        Flight::json(['message' => 'Fond financier ajouté ou mis à jour']);
    }

 

}
