<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
  public static function create() {
    $data = Flight::request()->data;

    $result = Pret::create($data);

    if ($result === false) {
        Flight::json(['error' => 'Erreur lors de la création du prêt'], 500);
    } elseif (is_array($result) && isset($result['error'])) {
         Flight::halt(400, json_encode(['message' => 'vous ne pouvez pas souscrire au pret']));
    } else {
        Flight::json(['message' => 'Prêt créé avec succès']);
    }
     }
    
   public static function getNonApproved() {
    $prets = Pret::findNonApproved();
    Flight::json($prets);
}
    public static function approuver($idPret) {
        $result = Pret::approuver($idPret);
        if ($result) {
            Flight::json(['message' => 'Prêt approuvé avec succès']);
        } else {
            Flight::halt(500, json_encode(['message' => 'Erreur lors de l’approbation du prêt']));
        }
    }
     public static function payer() {
         

        $data = Flight::request()->data;
 

           $idClient = $data->idClient ?? null;
    $idPret = $data->idPret ?? null;
    $datePaiement = $data->datePaiement ?? null;

     if (!$idClient || !$idPret || !$datePaiement) {
        Flight::json([
            'error' => true,
            'message' => 'Données manquantes',
            'received' => $data
        ], 400);
        return;
    }
        $resultat = Pret::payerEcheance($idClient, $idPret, $datePaiement);
        Flight::json($resultat);
    }
}
