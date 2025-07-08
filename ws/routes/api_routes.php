<?php
require_once __DIR__ . '/../controllers/DashboardClientController.php';


// Route pour afficher dashboard client
Flight::route('GET /dashboard/client/@idClient', ['DashboardClientController', 'getDashboard']);

// Route pour ajouter solde
Flight::route('POST /dashboard/update-solde', ['DashboardClientController', 'ajouterSolde']);

// Route pour payer une mensualité
Flight::route('POST /dashboard/pay', ['DashboardClientController', 'payer']);

?>