<?php
require_once __DIR__ . '/../db.php';

class Pret {
public static function create($data) {
    $db = getDB();

    // Récupérer les infos du type de prêt
    $stmt1 = $db->prepare("SELECT * FROM EF_TypePret WHERE idType = ?");
    $stmt1->execute([$data->idTypePret]);
    $typePret = $stmt1->fetch(PDO::FETCH_ASSOC);

    // Récupérer les infos du client
    $stmt2 = $db->prepare("SELECT * FROM EF_Client WHERE idClient = ?");
    $stmt2->execute([$data->idClient]);
    $client = $stmt2->fetch(PDO::FETCH_ASSOC);

    if (!$typePret || !$client) return false;

    // Données nécessaires
    $C = $typePret["montant"];
    $t = $typePret["taux"] / 100;
    $d = $typePret["duree_mois_max"];
    $salaire = $client["salaire_actuel"];

    // Calcul intérêt simple
    $interet = $C * $t * ($d / 12);
    $total_a_rembourser = $C + $interet;
    $capacite = $salaire * $d;

    // Vérification de capacité
    $isApproved = ($capacite >= $total_a_rembourser) ? 1 : 0;

    if ($isApproved) {
        $stmt = $db->prepare("INSERT INTO EF_Pret_Client (
            idTypePret, idClient, status, date_debut_pret,
            montant_paye, montant_restant, date_maj, isApproved
        ) VALUES (?, ?, 0, CURRENT_DATE, 0, ?, CURRENT_DATE, 0)");

        $success = $stmt->execute([
            $data->idTypePret,
            $data->idClient,
            $total_a_rembourser
            
        ]);
        return $success;
    } else {
        // Tu peux ici renvoyer un message ou false
        // Par exemple :
        return ['error' => "Vous n'etes pas en mesure de souscrire a ce pret."];
    }

      }
    public static function findAll() {
        $db = getDB();
        $stmt = $db->query("
            SELECT pc.*, tp.libelle, tp.montant AS montant_type,
                   c.nom, c.salaire_actuel
            FROM EF_Pret_Client pc
            JOIN EF_TypePret tp ON pc.idTypePret = tp.idType
            JOIN EF_Client c ON pc.idClient = c.idClient
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
