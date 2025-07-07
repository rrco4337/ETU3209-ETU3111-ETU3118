<?php
require_once __DIR__ . '/../models/TypePret.php';
require_once __DIR__ . '/../helpers/Utils.php';

class TypePretController {
    public static function getAll() {
        $TypePret = TypePret::findAll();
        Flight::json($TypePret);
    }

    public static function getById($id) {
        $TypePret = TypePret::findById($id);
        Flight::json($TypePret);
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = TypePret::create($data);
        $dateFormatted = Utils::formatDate('2025-01-01');
        Flight::json(['message' => 'Étudiant ajouté', 'id' => $id]);
    }

   public static function update($id) {
    // Lire le corps brut
    parse_str(file_get_contents("php://input"), $put_vars);

    // Transformer en objet pour correspondre à ta méthode update
    $data = (object) $put_vars;

    // Appeler la méthode update dans le modèle
    $result = TypePret::update($id, $data);

    if ($result) {
        Flight::json(['message' => 'TypePret modifié avec succès']);
    } else {
        Flight::json(['message' => 'Erreur lors de la modification'], 500);
    }
}

    public static function delete($id) {
        TypePret::delete($id);
        Flight::json(['message' => 'Étudiant supprimé']);
    }
}
