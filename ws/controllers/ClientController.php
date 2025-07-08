<?php
require_once __DIR__ . '/../models/Client.php';

class ClientController {
    public static function getAll() {
        $clients = Client::findAll();
        Flight::json($clients);
    }

       public static function getById($id) {
        $client = Client::getById($id);
        Flight::json($client);
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = Client::create($data);
        Flight::json(['message' => 'Client ajouté', 'id' => $id]);
    }

    public static function update($id) {
        $data = Flight::request()->data;
        Client::update($id, $data);
        Flight::json(['message' => 'Client modifié']);
    }

    public static function delete($id) {
        Client::delete($id);
        Flight::json(['message' => 'Client supprimé']);
    }
public static function checkLogin() {
    session_start();
    $data = Flight::request()->data;

    // Vérifie que les champs sont remplis
    if (empty($data->nom) || empty($data->password)) {
        Flight::json(['error' => 'Veuillez remplir tous les champs.'], 400);
        return;
    }

    // Vérifie les identifiants via le modèle
    $client = Client::checkLogin($data->nom, $data->password);

    if ($client) {
        // Enregistre l'identifiant en session
        $_SESSION['idClient'] = $client['idClient'];
        
        // Retire le mot de passe de la réponse
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
