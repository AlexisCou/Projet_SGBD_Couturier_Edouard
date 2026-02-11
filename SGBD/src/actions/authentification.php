<?php

use SGBD\models\serveur;

function tenterConnexion($login, $password) {
    $serv = serveur::where('login', $login)->first();

    if ($serv && $serv->mdp === $password) {
        $_SESSION['user_id'] = $serv->id_serv;
        $_SESSION['user_nom'] = $serv->nom;
        return true;
    }

    return false;
}