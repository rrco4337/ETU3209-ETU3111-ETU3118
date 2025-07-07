<?php

require_once __DIR__ . '/../controllers/PretController.php';

// Routes pour les prêts
Flight::route('GET /pret', [PretController::class, 'getAll']);         // Lister tous les prêts
Flight::route('POST /pret', [PretController::class, 'create']);        // Créer un prêt
