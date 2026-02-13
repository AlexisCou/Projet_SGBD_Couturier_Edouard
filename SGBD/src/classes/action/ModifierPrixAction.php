<?php

declare(strict_types=1);

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class ModifierPrixAction extends Action
{
    protected function executeGet(): string
    {

        $plat = SGBDrepository::getInstance()->getPlatsById($_GET['id']);
        return <<<HTML
            <h2>Modifier le prix du plat : {$plat['libelle']}</h2>
            <form method="POST">
                <p>
                    <label for="prix">Nouveau prix :</label><br>
                    <input type="number" name="prix" id="prix" value="{$plat['prixunit']}" step="0.01" required>
                </p>
                <p>
                    <button type="submit">Modifier le prix</button>
                </p>
            </form>
        HTML;
    }

    protected function executePost(): string
    {
        $newPrix = $_POST['prix'] ?? '';
        $platId = $_GET['id'] ?? '';
        if (SGBDrepository::getInstance()->updatePrix($platId, (float)$newPrix)) {
            header('Location: index.php?action=ShowPlats');
            exit();
        } else {
            return <<<HTML
                <h2>Échec de la modification du prix</h2>
                <p>Prix incorrect.</p>
                <p><a href="?action=ModifierPrix&id={$platId}">Réessayer</a></p>
                HTML;
        }
    }
}