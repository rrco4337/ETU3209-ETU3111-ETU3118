<?php
require_once __DIR__ . '/../db.php';

class Admin {
public static function findByMailandMDP($mail, $mdp) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM EF_Admin WHERE mail = ? AND motdepasse = ?");
    $stmt->execute([$mail, $mdp]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // fetch() car 1 seul résultat attendu
}



}
