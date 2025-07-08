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
    try {
        // Lire les données brutes du corps
        parse_str(Flight::request()->getBody(), $bodyData);

        $delai = isset($bodyData['delai']) ? (int)$bodyData['delai'] : 0;

        if ($delai < 0 || $delai > 6) {
            Flight::halt(400, json_encode([
                'message' => 'Le délai doit être compris entre 0 et 6 mois'
            ]));
            return;
        }

        $result = Pret::approuver($idPret, $delai);

        if ($result) {
            Flight::json([
                'message' => 'Prêt approuvé avec succès',
                'delai_applique' => $delai
            ]);
        } else {
            Flight::halt(500, json_encode([
                'message' => 'Erreur lors de l\'approbation du prêt'
            ]));
        }

    } catch (Exception $e) {
        Flight::halt(500, json_encode([
            'message' => 'Erreur: ' . $e->getMessage()
        ]));
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
