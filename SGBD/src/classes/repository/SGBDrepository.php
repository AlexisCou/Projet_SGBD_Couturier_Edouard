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
        if(!$serveur) return "Il n'y a pas de serveur avec cet identifiant";
        return "Connexion réussie";
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

            $pdo->prepare("DELETE FROM commande WHERE numres = ?")->execute([$numres]);
            $pdo->prepare("DELETE FROM reservation WHERE numres = ?")->execute([$numres]);

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
            $stStock = $pdo->prepare("SELECT qteservie FROM plat WHERE numplat = ? FOR UPDATE");
            $stStock->execute([$numplat]);
            $platData = $stStock->fetch();

            if (!$platData || $platData['qteservie'] < $quantite) {
                $pdo->rollBack();
                return false;
            }

            $pdo->prepare("UPDATE plat SET qteservie = qteservie - ? WHERE numplat = ?")->execute([$quantite, $numplat]);
            $pdo->prepare("INSERT INTO commande (numres, numplat, quantite) VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE quantite = quantite + ?")->execute([$numres, $numplat, $quantite, $quantite]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public function encaisserReservation(int $numres, string $mode_paiement, float $montant_total): bool {
        $pdo = $this->getPDO();
        $pdo->beginTransaction();
        try {
            $query = "UPDATE reservation SET datpaie = NOW(), modpaie = ?, montcom = ? WHERE numres = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$mode_paiement, $montant_total, $numres]);
            
            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public function getPlatsByCommande(int $numres){
        $html = "<ul>";
        $cmd = commande::where('numres', $numres)->get();
        foreach($cmd as $c){
            $plat = plat::where('numplat', $c->numplat)->first();
            if ($plat) $html .= "<li>{$plat->libelle} ({$c->quantite}x)</li>";
        }
        return $html . "</ul>";
    }

    public function getPrixByCommande(int $numres): float {
        $prix = 0;
        $cmd = commande::where('numres', $numres)->get();
        foreach($cmd as $c){
            $plat = plat::where('numplat', $c->numplat)->first();
            if ($plat) $prix += $plat->prixunit * $c->quantite;
        }
        return (float)$prix;
    }

    public function getPlatsById(string $numplat) {
        $plat = plat::where('numplat', (int)$numplat)->first();
        return $plat ? $plat->toArray() : null;
    }

    public function updatePlat(int $numplat, float $prix, int $qte): bool {
        $pdo = $this->getPDO();
        $pdo->beginTransaction();
        try {
            if($prix < 0 || $qte < 0) { $pdo->rollBack(); return false; }
            $pdo->prepare("UPDATE plat SET prixunit = ?, qteservie = ? WHERE numplat = ?")->execute([$prix, $qte, $numplat]);
            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public function getPlats() { return plat::all()->toArray(); }
    public function getReservation(int $idserv) { return reservation::where('id_serv', $idserv)->get()->toArray(); }
}