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

class SGBDrepository {

    private static ?SGBDrepository $instance = null;
    private ?PDO $pdo = null;

    private function __construct() {
        $conf = parse_ini_file('src/conf/conf.ini');

        $capsule = new Capsule;
        $capsule->addConnection($conf);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

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

    public function connexion(String $username, String $password) {
        $serveur = serveur::where('login','=',$username)->where('mdp','=',$password)->first();
        if(!$serveur){
            return "Il n'y a pas de serveur avec cet identifiant";
        }
        else {
            return "Connexion réussie";
        }
    }

    public function reserverTable(int $id_table, string $date_heure, int $nb_pers, int $id_serveur): bool {
        $pdo = $this->getPDO();
        $pdo->beginTransaction();
        try {
            $queryCheck = "SELECT count(*) as total FROM reservation WHERE numtab = ? AND datres = ?";
            $stmtCheck = $pdo->prepare($queryCheck);
            $stmtCheck->execute([$id_table, $date_heure]);
            $res = $stmtCheck->fetch();

            if ($res['total'] > 0) {
                $pdo->rollBack();
                return false; 
            }

            $queryInsert = "INSERT INTO reservation (datres, nbpers, numtab, id_serv) VALUES (?, ?, ?, ?)";
            $stmtInsert = $pdo->prepare($queryInsert);
            $stmtInsert->execute([$date_heure, $nb_pers, $id_table, $id_serveur]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public function annulerReservation(int $numres): bool {
        $pdo = $this->getPDO();
        $pdo->beginTransaction();
        try {
            $queryCheck = "SELECT datpaie FROM reservation WHERE numres = ? FOR UPDATE";
            $stmtCheck = $pdo->prepare($queryCheck);
            $stmtCheck->execute([$numres]);
            $res = $stmtCheck->fetch();

            if (!$res || $res['datpaie'] !== null) {
                $pdo->rollBack();
                return false;
            }

            $queryDelCom = "DELETE FROM commande WHERE numres = ?";
            $stmtDelCom = $pdo->prepare($queryDelCom);
            $stmtDelCom->execute([$numres]);

            $queryDelete = "DELETE FROM reservation WHERE numres = ?";
            $stmtDelete = $pdo->prepare($queryDelete);
            $stmtDelete->execute([$numres]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public function ajouterPlatACommande(int $numres, int $numplat, int $quantite): bool {
        $pdo = $this->getPDO();
        $pdo->beginTransaction();

        try {
            $qStock = "SELECT qteservie FROM plat WHERE numplat = ? FOR UPDATE";
            $stStock = $pdo->prepare($qStock);
            $stStock->execute([$numplat]);
            $platData = $stStock->fetch();

            if (!$platData || $platData['qteservie'] < $quantite) {
                $pdo->rollBack();
                return false;
            }

            $qUpdateStock = "UPDATE plat SET qteservie = qteservie - ? WHERE numplat = ?";
            $stUpdate = $pdo->prepare($qUpdateStock);
            $stUpdate->execute([$quantite, $numplat]);

            $qInsertCom = "INSERT INTO commande (numres, numplat, quantite) VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE quantite = quantite + ?";
            $stInsert = $pdo->prepare($qInsertCom);
            $stInsert->execute([$numres, $numplat, $quantite, $quantite]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public function getCommandes(){
        return commande::groupBy('numres')->get()->toArray();
    }

    public function getPlatsByCommande(int $numres){
        $html = "<ul>";
        $cmd = commande::where('numres', $numres)->get()->toArray();
        foreach($cmd as $c){
            $plat = plat::where('numplat', $c['numplat'])->first();
            if ($plat) {
                $html .= '<li>'.$plat['libelle'].' ('.$c['quantite'].'x)</li>';
            }
        }
        $html .= "</ul>";
        return $html;
    }

    public function getPrixByCommande(int $numres){
        $prix = 0;
        $cmd = commande::where('numres', $numres)->get()->toArray();
        foreach($cmd as $c){
            $plat = plat::where('numplat', $c['numplat'])->first();
            if ($plat) {
                $prix += $plat['prixunit'] * $c['quantite'];
            }
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

    public function getPlatsById(string $numplat){
        $numplat = intval($numplat);
        return plat::where('numplat', $numplat)->first()->toArray();
    }

    public function updatePrix(string $numplat, float $prix){
        $numplat = intval($numplat);
        if($prix < 0){
            return false;
        }
        $plat = plat::where('numplat', $numplat)->first();
        if ($plat) {
            $plat->prixunit = $prix;
            $plat->save();
            return true;
        }
        return false;
    }
}