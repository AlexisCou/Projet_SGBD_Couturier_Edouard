<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use SGBD\models\serveur; 
use Illuminate\Database\Capsule\Manager as Capsule;

$conf = parse_ini_file('src/conf/conf.ini');
$capsule = new Capsule;
$capsule->addConnection($conf);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->getConnection()->getPdo()->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

if (isset($_POST['login_submit'])) {
    $serv = serveur::where('login', $_POST['login'])->first();
    
    if ($serv && $serv->mdp === $_POST['password']) {
        $_SESSION['user'] = $serv;
    } else {
        $error = "Identifiants invalides";
    }
}

if (!isset($_SESSION['user'])) {
    echo '<h2>Connexion Serveur</h2>';
    if(isset($error)) echo "<p style='color:red'>$error</p>";
    echo '<form method="POST">
            Login: <input type="text" name="login"><br>
            Mdp: <input type="password" name="password"><br>
            <input type="submit" name="login_submit" value="Se connecter">
          </form>';
    exit();
}

echo "Bonjour " . $_SESSION['user']->nom . " !";
?>