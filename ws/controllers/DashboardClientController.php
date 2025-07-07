<?php
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/OffrePret.php';
require_once __DIR__ . '/../helpers/Utils.php';

class DashboardClientController{

    // Endpoint pour le résumé (cartes principales)
    public static function getSummary($idClient) {
        $summary = Client::getDashboardSummary($idClient);
        if ($summary) {
            Flight::json($summary);
        } else {
            Flight::json(['message' => 'Client non trouvé'], 404);
        }
    }

    // Endpoint pour le prêt en cours
    public static function getCurrentLoan($idClient) {
        $loan = Client::getCurrentLoan($idClient);
         if ($loan) {
            Flight::json($loan);
        } else {
            // C'est normal de ne pas avoir de prêt, on renvoie un objet vide
            Flight::json([]);
        }
    }

    // Endpoint pour les offres de prêt
    public static function getLoanOffers() {
        $offers = OffrePret::getAll();
        Flight::json($offers);
    }
}