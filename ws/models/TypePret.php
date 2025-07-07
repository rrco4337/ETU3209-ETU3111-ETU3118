<?php
require_once __DIR__ . '/../db.php';

class TypePret {

  
   public static function create( $data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO EF_TypePret (libelle, montant, taux, duree_mois_max) VALUES (?, ?, ?, ?)");
    return $stmt->execute([
        $data->libelle,
        $data->montant,
        $data->taux,
        $data->duree_mois_max
    ]);
}


 
    public static function findById($idType) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM EF_TypePret WHERE idType = ?");
        $stmt->execute([$idType]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
    public static function findAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM EF_TypePret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public static function update($id,$data) {
        $db = getDB();
        $libelle = $data->libelle;
        $montant = $data->montant;
        $taux = $data->taux;
        $duree_mois_max = $data->duree_mois_max;
        $stmt = $db->prepare("UPDATE EF_TypePret SET libelle = ?, montant = ?, taux = ?, duree_mois_max = ? WHERE idType = ?");
        return $stmt->execute([$libelle, $montant, $taux, $duree_mois_max, $id]);
       
    }

  
    public static function delete($idType) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM EF_TypePret WHERE idType = ?");
        return $stmt->execute([$idType]);
    }
}
