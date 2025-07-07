<?php
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../helpers/Utils.php';

class ClientController {
    public static function getAll() {
        $clients = ClientModel::getAll();
        Flight::json($clients);
    }

    public static function getById($id) {
        $client = ClientModel::getById($id);
        Flight::json($client);
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = ClientModel::create($data);
        Flight::json(['message' => 'Client ajouté', 'id' => $id]);
    }

    public static function update($id) {
        $data = Flight::request()->data;
        ClientModel::update($id, $data);
        Flight::json(['message' => 'Client modifié']);
    }

    public static function delete($id) {
        ClientModel::delete($id);
        Flight::json(['message' => 'Client supprimé']);
    }

   public static function checkLogin() {
    $data = Flight::request()->data;

    // Vérification que les champs ne sont pas vides
    if (empty($data->nom) || empty($data->password)) {
        Flight::json(['error' => 'Veuillez remplir tous les champs.'], 400);
        return;
    }

    // Appel au modèle pour vérifier les identifiants
    $client = ClientModel::checkLogin($data->nom, $data->password);

    if ($client) {
        // On ne retourne pas le mot de passe au client
        unset($client['password']);
        Flight::json([
            'message' => 'Connexion réussie',
            'data' => $client
        ]);
    } else {
        Flight::json([
            'erreur' => 'Nom d\'utilisateur ou mot de passe incorrect.'
        ], 401);
    }
}

}
