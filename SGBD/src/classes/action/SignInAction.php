<?php

declare(strict_types=1);

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class SignInAction extends Action
{
    protected function executeGet(): string
    {
        return <<<HTML
            <h2>Connexion</h2>
            <form method="POST">
                <p>
                    <label for="login">Identifiant :</label><br>
                    <input type="text" name="login" id="login" placeholder="Justin_2026" required>
                </p>
                <p>
                    <label for="passwd">Mot de passe :</label><br>
                    <input type="password" name="passwd" id="passwd" placeholder="justin.dec.2026" required>
                </p>
                <p>
                    <button type="submit">Se connecter</button>
                </p>
            </form>
        HTML;
    }

    protected function executePost(): string
    {
        $username = $_POST['login'] ?? '';
        $passwd = $_POST['passwd'] ?? '';
        $repo = SGBDrepository::getInstance();
        $result = $repo->connexion($username, $passwd);
        if ($result === "Connexion réussie") {
            $_SESSION['username'] = $username;
            header('Location: index.php?action=default');
            exit();
        } else {
            return <<<HTML
                <h2>Échec de la connexion</h2>
                <p>Identifiant ou mot de passe incorrect.</p>
                <p><a href="?action=SignIn">Réessayer</a></p>
                HTML;
        }
    }
}