<?php

require_once __DIR__ . '/../controllers/ClientController.php';

// Routes pour les prêts

Flight::route('GET /client', ['ClientController', 'getAll']);
