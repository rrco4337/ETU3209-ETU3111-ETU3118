<?php
require_once __DIR__ . '/../models/TypePret.php';
require_once __DIR__ . '/../helpers/Utils.php';



class TypePretController {
    public static function getAll() {
        $TypePrets = TypePret::findAll();
        Flight::json($TypePrets);
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
        $data = Flight::request()->data;
        TypePret::update($id, $data);
        Flight::json(['message' => 'Étudiant modifié']);
    }

    public static function delete($id) {
        TypePret::delete($id);
        Flight::json(['message' => 'Étudiant supprimé']);
    }
}
