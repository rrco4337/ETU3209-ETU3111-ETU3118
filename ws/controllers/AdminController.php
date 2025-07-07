<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../helpers/Utils.php';



class AdminController {
   public static function login() {
        $data = Flight::request()->data;

        $mail = $data->mail ?? '';
        $mdp = $data->motdepasse ?? '';

        if (!$mail || !$mdp) {
            Flight::json(['error' => 'Mail et mot de passe requis'], 400);
            return;
        }

        $admin = Admin::findByMailandMDP($mail, $mdp);

        if ($admin) {
            Flight::json(['success' => true, 'admin' => $admin]);
        } else {
            Flight::json(['success' => false, 'message' => 'Identifiants invalides'], 401);
        }
    }

 
}
