<?php
require 'vendor/autoload.php';
require 'db.php';
require 'routes/etudiant_routes.php';
require 'routes/Admin_routes.php';
require 'routes/TypePret_routes.php';
require 'routes/FondFinancier_routes.php';
require 'routes/Pret_routes.php';
require 'routes/Client_routes.php';
require 'routes/SuiviPret_routes.php';
require 'routes/api_routes.php';

Flight::start();