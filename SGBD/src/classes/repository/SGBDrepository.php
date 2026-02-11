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

    private static ?SGBDrepository $instance = null;

    public static function getInstance(): SGBDrepository {
        if (self::$instance === null) {
            self::$instance = new SGBDrepository();
        }
        return self::$instance;
    }

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

    public function getCommandes(){
        return commande::groupBy('numres')->get()->toArray();
    }

    public function getPlats(){
        return plat::all()->toArray();
    }

    public function getPlatsByCommande(int $numres){
        $html = "<ul>";
        $cmd =commande::where('numres', $numres)->get()->toArray();
        foreach($cmd as $c){
            $plat = plat::where('numplat', $c['numplat'])->first();
            $quantity = $c['quantite'];
            $html .= '<li>'.$plat['libelle'].' ('.$quantity.'x)</li>';
        }
        $html .= "</ul>";
        return $html;
    }

    public function getPrixByCommande(int $numres){
        $prix = 0;
        $cmd =commande::where('numres', $numres)->get()->toArray();
        foreach($cmd as $c){
            $plat = plat::where('numplat', $c['numplat'])->first();
            $quantity = $c['quantite'];
            $prix += $plat['prixunit'] * $quantity;
        }
        return $prix;
    }
}
