<?php

namespace SGBD\repository;

require_once 'vendor/autoload.php';

use SGBD\models\commande;
use SGBD\models\plat;
use SGBD\models\reservation;
use SGBD\models\tabl;
use SGBD\models\serveur;

use Illuminate\Database\Capsule\Manager as Capsule;

$conf = parse_ini_file('src/conf/conf.ini');

$capsule = new Capsule;
$capsule->addConnection($conf);
$capsule->setAsGlobal();
$capsule->bootEloquent();

class SGBDrepository{
    public function connexion(String $username, String $password){
        $reponse;
        $serveur = serveur::where('login','=',$username)->where('mdp','=',$password)->first();
        if(!$serveur){
            $reponse = "Il n'y a pas de serveur avec cet identifiant";
        }
        else if($serveur->mdp != $password){
            $reponse = "Mot de passe incorrect";
        }
        else{
            $reponse = "Connexion réussie";
        }
        return $reponse;
    }
}
