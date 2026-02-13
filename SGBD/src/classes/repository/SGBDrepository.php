<?php

namespace SGBD\repository;

require_once 'vendor/autoload.php';

use SGBD\models\commande;
use SGBD\models\plat;
use SGBD\models\reservation;
use SGBD\models\tabl;
use SGBD\models\serveur;

use Illuminate\Database\Capsule\Manager as Capsule;
use PDO;
use PDOException;

$conf = parse_ini_file('src/conf/conf.ini');

$capsule = new Capsule;
$capsule->addConnection($conf);
$capsule->setAsGlobal();
$capsule->bootEloquent();

class SGBDrepository{

    private static ?SGBDrepository $instance = null;
    private ?PDO $pdo = null;

    private function __construct() {
        $conf = parse_ini_file('src/conf/conf.ini');
        try {
            $dsn = "mysql:host={$conf['host']};dbname={$conf['database']};charset=utf8";
            $this->pdo = new PDO($dsn, $conf['username'], $conf['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
        }
    }

    public static function getInstance(): SGBDrepository {
        if (self::$instance === null) {
            self::$instance = new SGBDrepository();
        }
        return self::$instance;
    }

    public function getPDO(): ?PDO {
        return $this->pdo;
    }

    public function connexion(String $username, String $password){
        $reponse = "";
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

    public function getPlats(){
        return plat::all()->toArray();
    }

    public function getReservation(int $idserv){
        return reservation::where('id_serv', $idserv)->get()->toArray();
    }

    public function getCommandesByReservation(int $numres){
        return commande::where('numres', $numres)->get()->toArray();
    }

    public function getServeurIdByUsername(String $username){
        $serveur = serveur::where('login', $username)->first();
        return $serveur ? $serveur['id_serv'] : 0;
    }

}