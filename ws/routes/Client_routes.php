<?php

require_once __DIR__ . '/../controllers/ClientController.php';

// Routes pour les prêts
Flight::route('GET /clients', ['ClientController', 'getAll']);
Flight::route('GET /clients/@id', ['ClientController', 'getById']);
Flight::route('POST /clients', ['ClientController', 'create']);
Flight::route('PUT /clients/@id', ['ClientController', 'update']);
Flight::route('DELETE /clients/@id', ['ClientController', 'delete']);

//  Route pour la connexion (login)
Flight::route('POST /clients/login', ['ClientController', 'checkLogin']);
