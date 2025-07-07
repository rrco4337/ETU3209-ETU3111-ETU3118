<?php
require_once __DIR__ . '/../controllers/DashboardClientController.php';

Flight::route('GET /dashboard/client/@id', ['DashboardClientController', 'getClientLoans']);
Flight::route('POST /dashboard/pay', ['DashboardClientController', 'payLoan']);
Flight::route('GET /dashboard/client/@id/summary', ['DashboardClientController', 'getLoanSummary']);
Flight::route('GET /dashboard/client/@id/loans', ['DashboardClientController', 'getAllClientLoans']);

?>