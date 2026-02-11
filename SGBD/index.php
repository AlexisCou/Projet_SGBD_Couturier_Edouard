<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/conf/connection.php';
require_once __DIR__ . '/src/actions/authentification.php';

$error = "";

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST['login_submit'])) {
    if (tenterConnexion($_POST['login'], $_POST['password'])) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}

if (!isset($_SESSION['user_id'])) {
    include __DIR__ . '/src/views/login.php';
} else {
    include __DIR__ . '/src/views/header.php';
    
    echo "<h1>Bonjour " . $_SESSION['user_nom'] . "</h1>";
    echo "<p><a href='index.php?action=logout'>Déconnexion</a></p>";

    $action = $_GET['action'] ?? 'menu';

    switch($action) {
        case 'reserver':
            include __DIR__ . '/src/actions/reserver.php';
            break;
        case 'menu':
        default:
            include __DIR__ . '/src/views/menu.php';
            break;
        case 'annuler':
            include __DIR__ . '/src/actions/annuler.php';
            break;    
    }

    include __DIR__ . '/src/views/footer.php';
}