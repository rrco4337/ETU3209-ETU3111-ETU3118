<?php
require 'vendor/autoload.php';
require 'db.php';
// require 'routes/etudiant_routes.php';
require 'routes/api_routes.php';
// require '/controllers/DashboardClientController.php';
require __DIR__ . '/controllers/DashboardClientController.php';
// require __DIR__ . '/../routes/api_routes.php';
Flight::start();