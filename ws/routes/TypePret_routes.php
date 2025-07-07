<?php
require_once __DIR__ . '/../controllers/TypePretController.php';


Flight::route('GET /type-pret', ['TypePretController', 'getAll']);
Flight::route('GET /type-pret/@id', ['TypePretController', 'getById']);
Flight::route('POST /type-pret', ['TypePretController', 'create']);
Flight::route('PUT /type-pret/@id', ['TypePretController', 'update']);
Flight::route('DELETE /type-pret/@id', ['TypePretController', 'delete']);
