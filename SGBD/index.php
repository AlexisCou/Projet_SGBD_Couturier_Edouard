<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use SGBD\models\commande;
use SGBD\models\plat;
use SGBD\models\reservation;
use SGBD\models\tabl;

use Illuminate\Database\Capsule\Manager as Capsule;

$conf = parse_ini_file('src/conf/conf.ini');

$capsule = new Capsule;
$capsule->addConnection($conf);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule->getConnection()->getPdo()->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

$commmandes = commande::all();
foreach ($commmandes as $commande) {
    echo $commande->quantite . "\n";
}