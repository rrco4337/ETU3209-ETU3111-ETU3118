<?php
require_once __DIR__ . '/../controllers/DashboardClientController.php';

// Route pour obtenir le résumé financier du client (pour les cartes)
Flight::route('GET /client/@id/summary', ['DashboardClientController', 'getSummary']);

// Route pour obtenir les détails du prêt en cours du client
Flight::route('GET /client/@id/current-loan', ['DashboardClientController', 'getCurrentLoan']);

// Route pour obtenir la liste de toutes les offres de prêt
Flight::route('GET /loan-offers', ['DashboardClientController', 'getLoanOffers']);