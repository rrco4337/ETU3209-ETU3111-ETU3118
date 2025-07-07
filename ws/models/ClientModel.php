<?php
require_once __DIR__ . '/../db.php';

class ClientModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM EF_Client");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM EF_Client WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO EF_Client (nom, email, motdepasse) VALUES (?, ?, ?)");
        $stmt->execute([$data->nom, $data->email, password_hash($data->motdepasse, PASSWORD_DEFAULT)]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE EF_Client SET nom = ?, email = ?, motdepasse = ? WHERE id = ?");
        $stmt->execute([$data->nom, $data->email, password_hash($data->motdepasse, PASSWORD_DEFAULT), $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM EF_Client WHERE id = ?");
        $stmt->execute([$id]);
    }

   public static function checkLogin($nom, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM EF_Client WHERE nom = ? AND password = ?");
    $stmt->execute([$nom, $password]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // retourne false si non trouvé
}

}
