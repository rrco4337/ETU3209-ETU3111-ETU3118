<?php
require_once __DIR__ . '/../models/DashboardModel.php';
require_once __DIR__ . '/../helpers/Utils.php';

class DashboardClientController {

    public static function getDashboard($idClient) {
        $resume = DashboardModel::getResume($idClient);
        $prets = DashboardModel::getPretsParType($idClient);
        Flight::json([
            'resume' => $resume,
            'pretsParType' => $prets
        ]);
    }

    public static function ajouterSolde() {
        $data = Flight::request()->data;
        if (DashboardModel::ajouterSolde($data->idClient, $data->montant)) {
            Flight::json(['message' => 'Solde mis à jour']);
        } else {
            Flight::json(['error' => true, 'message' => 'Erreur lors de l’ajout de solde']);
        }
    }

    public static function payer() {
        $data = Flight::request()->data;
        $res = DashboardModel::soumettrePaiement(
            $data->idClient,
            $data->idTypePret,
            $data->montant,
            $data->date
        );
        Flight::json($res);
    }
}

   


