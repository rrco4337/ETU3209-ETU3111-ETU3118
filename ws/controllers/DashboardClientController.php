<?php
require_once __DIR__ . '/../models/DashboardModel.php';
require_once __DIR__ . '/../helpers/Utils.php';

class DashboardClientController {

    // Récupérer tous les prêts d’un client, groupés par type de prêt
    public static function getClientLoans($idClient) {
        $loans = DashboardModel::getLoansByClientGroupedByType($idClient);
        $summary = DashboardModel::getClientLoanSummary($idClient);
        Flight::json([
            'pretsParType' => $loans,
            'resume' => $summary
        ]);
    }

    // Enregistrer un paiement mensuel pour un prêt
    public static function payLoan() {
        $data = Flight::request()->data;

        // Validation simple
        if (empty($data->idClient) || empty($data->idTypePret) || empty($data->date) || empty($data->montant)) {
            Flight::json(['error' => 'Données manquantes'], 400);
            return;
        }

        $idClient = $data->idClient;
        $idTypePret = $data->idTypePret;
        $datePaiement = $data->date;
        $montant = $data->montant;

        // Format du mois au format YYYY-MM (ex: 2025-07)
        $mois = date('Y-m', strtotime($datePaiement));

        DashboardModel::payLoanByMonth($idClient, $idTypePret, $mois, $montant);

        Flight::json(['message' => 'Paiement enregistré avec succès']);
    }

    // Optionnel : Récupérer le résumé des prêts d'un client (juste le résumé)
    public static function getLoanSummary($idClient) {
        $summary = DashboardModel::getClientLoanSummary($idClient);
        Flight::json($summary);
    }
    public static function getAllClientLoans($idClient) {
    $loans = DashboardModel::getAllLoansByClient($idClient);
    Flight::json($loans);
}

}
// Optionnel : Récupérer les prêts en cours d'un client
// public static function getCurrentLoan($idClient) {
//     $currentLoan = DashboardModel::getCurrentLoan($idClient);
//     Flight::json($currentLoan);
// }