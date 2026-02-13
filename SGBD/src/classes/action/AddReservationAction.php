<?php

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class AddReservationAction extends Action {

    protected function executeGet(): string {
        return <<<HTML
            <h2>Réserver une table</h2>
            <form method="POST" action="?action=add-reservation">
                <p>
                    <label for="numtab">Numéro de la table :</label><br>
                    <input type="number" name="numtab" id="numtab" required>
                </p>
                <p>
                    <label for="dateres">Date et heure (AAAA-MM-JJ HH:MM) :</label><br>
                    <input type="text" name="dateres" id="dateres" placeholder="2025-05-20 20:00" required>
                </p>
                <p>
                    <label for="nbpers">Nombre de personnes :</label><br>
                    <input type="number" name="nbpers" id="nbpers" required>
                </p>
                <p>
                    <button type="submit">Valider la réservation</button>
                </p>
            </form>
HTML;
    }

    protected function executePost(): string {
        $numtab = (int)$_POST['numtab'];
        $dateres = $_POST['dateres'];
        $nbpers = (int)$_POST['nbpers'];
        $id_serveur = $_SESSION['id'];

        $repo = SGBDrepository::getInstance();
        
        $success = $repo->reserverTable($numtab, $dateres, $nbpers, $id_serveur);

        if ($success) {
            return <<<HTML
                <p style="color: green;">Réservation confirmée pour la table $numtab !</p>
                <p><a href="?action=default">Retour à l'accueil</a></p>
HTML;
        } else {
            return <<<HTML
                <p style="color: red;">Erreur : La table $numtab est déjà réservée à cette date/heure ou n'existe pas.</p>
                <p><a href="?action=add-reservation">Réessayer</a></p>
HTML;
        }
    }
}