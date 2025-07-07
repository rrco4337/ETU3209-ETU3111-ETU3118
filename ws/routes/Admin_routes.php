<?php
require_once __DIR__ . '/../controllers/AdminController.php';



Flight::route('POST /Admin/login', ['AdminController', 'login']);