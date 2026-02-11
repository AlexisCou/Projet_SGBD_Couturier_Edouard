<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;

$conf = parse_ini_file(__DIR__ . '/conf.ini');

$capsule = new Capsule;
$capsule->addConnection($conf);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->getConnection()->getPdo()->setAttribute(PDO::ATTR_AUTOCOMMIT, false);