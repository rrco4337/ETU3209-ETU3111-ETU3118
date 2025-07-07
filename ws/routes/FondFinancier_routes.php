<?php
require_once __DIR__ . '/../controllers/FondFinancierController.php';


Flight::route('GET /fond-financier', ['FondFinancierController', 'getAll']);
Flight::route('GET /fond-financier/@annee', ['FondFinancierController', 'getByAnnee']);
Flight::route('POST /fond-financier', ['FondFinancierController', 'create']);
