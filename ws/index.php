<?php
require 'vendor/autoload.php';
require 'db.php';
// require 'routes/etudiant_routes.php';
// <<<<<<< HEAD
require 'routes/Admin_routes.php';
require 'routes/TypePret_routes.php';
require 'routes/FondFinancier_routes.php';

require 'routes/client_routes.php';

require 'routes/Pret_routes.php';
require 'routes/Client_routes.php';

Flight::start();