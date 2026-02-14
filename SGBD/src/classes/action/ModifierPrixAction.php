<?php

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class ModifierPrixAction extends Action {

    protected function executeGet(): string {
        $id = $_GET['numplat'] ?? $_GET['id'] ?? null;
        
        $repo = SGBDrepository::getInstance();
        $plat = $repo->getPlatsById((string)$id);

        if (!$plat) {
            return "<p style='color:red;'>Plat introuvable (ID reçu : $id). Vérifiez le lien dans la liste des plats.</p>";
        }

        return <<<HTML
            <h2>Modifier le plat : {$plat['libelle']}</h2>
            <form method="POST" action="?action=ModifierPrix&numplat={$id}">
                <p>Prix actuel : <input type="number" step="0.01" name="prix" value="{$plat['prixunit']}"></p>
                <p>Stock actuel : <input type="number" name="qte" value="{$plat['qteservie']}"></p>
                <button type="submit">Enregistrer</button>
            </form>
HTML;
    }

    protected function executePost(): string {
        $id = (int)$_GET['numplat'];
        $prix = (float)$_POST['prix'];
        $qte = (int)$_POST['qte'];

        $repo = SGBDrepository::getInstance();
        $repo->updatePlat($id, $prix, $qte);

        return "Modifié ! <a href='?action=ShowPlats'>Retour</a>";
    }
}