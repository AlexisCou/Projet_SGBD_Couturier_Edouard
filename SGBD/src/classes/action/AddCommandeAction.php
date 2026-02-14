<?php

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class AddCommandeAction extends Action {

    protected function executeGet(): string {
        $numres = (int)($_GET['numres'] ?? 0);
        $repo = SGBDrepository::getInstance();
        $plats = $repo->getPlats();

        $html = "<h2>Ajouter un plat à la réservation n°$numres</h2>";
        $html .= '<form method="POST" action="?action=add-commande&numres='.$numres.'">';
        $html .= '<p><label>Choisir un plat : <select name="numplat">';
        
        foreach ($plats as $p) {
            $html .= "<option value='{$p['numplat']}'>{$p['libelle']} ({$p['prixunit']}€) - Stock: {$p['qteservie']}</option>";
        }
        
        $html .= '</select></label></p>';
        $html .= '<p><label>Quantité : <input type="number" name="quantite" value="1" min="1" required></label></p>';
        $html .= '<button type="submit">Ajouter à la commande</button>';
        $html .= '</form>';
        $html .= '<p><a href="?action=ShowReservations">Retour</a></p>';

        return $html;
    }

    protected function executePost(): string {
        $numres = (int)$_GET['numres'];
        $numplat = (int)$_POST['numplat'];
        $quantite = (int)$_POST['quantite'];

        $repo = SGBDrepository::getInstance();
        $success = $repo->ajouterPlatACommande($numres, $numplat, $quantite);

        if ($success) {
            return "<p style='color:green;'>Plat ajouté et stock mis à jour !</p><a href='?action=ShowReservations'>Retour aux réservations</a>";
        } else {
            return "<p style='color:red;'>Erreur : Stock insuffisant ou plat introuvable.</p><a href='?action=add-commande&numres=$numres'>Réessayer</a>";
        }
    }
}