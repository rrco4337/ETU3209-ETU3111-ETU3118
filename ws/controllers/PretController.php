<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
  public static function create() {
    $data = Flight::request()->data;

    $result = Pret::create($data);

    if ($result === false) {
        Flight::halt(500, json_encode(['error' => 'Erreur lors de la création du prêt']));
    } elseif (is_array($result) && isset($result['error'])) {
        Flight::halt(400, json_encode(['error' => 'Vous ne pouvez pas souscrire au prêt choisi']));
    } else {
        Flight::json(['message' => 'Prêt créé avec succès']);
    }
}

}
