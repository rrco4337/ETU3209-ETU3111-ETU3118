<?php
require_once __DIR__ . '/../db.php';

class Client {
    public static function findAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM EF_Client");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
