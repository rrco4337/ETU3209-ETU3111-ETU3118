<?php
require_once __DIR__ . '/../controllers/TypePretController.php';


Flight::route('GET /type-pret', ['TypeController', 'getAll']);
Flight::route('GET /type-pret/@id', ['TypeController', 'getById']);
Flight::route('POST /type-pret', ['TypeController', 'create']);
Flight::route('PUT /type-pret/@id', ['TypeController', 'update']);
Flight::route('DELETE /type-pret/@id', ['TypeController', 'delete']);

