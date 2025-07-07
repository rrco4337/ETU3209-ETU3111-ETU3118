<?php
require 'vendor/autoload.php';
require 'db.php';
// require 'routes/etudiant_routes.php';
// <<<<<<< HEAD
require 'routes/Admin_routes.php';
require 'routes/TypePret_routes.php';
require 'routes/FondFinancier_routes.php';

// =======
require 'routes/client_routes.php';
// >>>>>>> ae2b1d9f1050fce99c47b39d96b33d8f2b42d085

Flight::start();