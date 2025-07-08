<?php

require_once __DIR__ . '/../controllers/PretController.php';

// Routes pour les prêts
Flight::route('GET /pret', [PretController::class, 'getAll']);         // Lister tous les prêts
Flight::route('POST /pret', [PretController::class, 'create']);        // Créer un prêt
Flight::route('GET /pret/non-approuves', ['PretController', 'getNonApproved']);
Flight::route('PUT /pret/approuver/@id', ['PretController', 'approuver']);

Flight::route('POST /dashboard/pay', [PretController::class, 'payerEcheance']);
Flight::route('POST /pret/payer', 'PretController::payer');