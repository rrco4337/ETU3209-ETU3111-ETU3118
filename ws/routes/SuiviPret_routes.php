<?php

require_once __DIR__ . '/../controllers/SuiviPretController.php';


Flight::route('GET /interets-par-mois', ['SuiviPretController', 'getInterets']);
