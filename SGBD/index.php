<?php
session_start();

require_once 'src/conf/connection.php';
require_once 'src/actions/authentification.php';

$error = "";

if (isset($_POST['login_submit'])) {
    if (tenterConnexion($_POST['login'], $_POST['password'])) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}

if (!isset($_SESSION['user_id'])) {
    include 'src/views/login.php';
} else {
    include 'src/views/header.php';
    echo "<h1>Bonjour " . $_SESSION['user_nom'] . "</h1>";
    
    include 'src/views/menu_principal.php'; 
    
    include 'src/views/footer.php';
}