<?php

declare(strict_types=1);

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class ShowCommandesAction extends Action
{
    protected function executeGet(): string
    {
        $commandes = SGBDrepository::getInstance()->getCommandes();
        
        $html = '<h2>Liste des commandes</h2>';
        foreach ($commandes as $cmd) {
            $prixCommandes = SGBDrepository::getInstance()->getPrixByCommande($cmd['numres']);
            $html .= '<h3>Commande n°' . $cmd['numres'] . ' - Prix : ' . $prixCommandes . '€ <a href="?action=ModifierCommande">Modifier</a></h3>';
            $html .= SGBDrepository::getInstance()->getPlatsByCommande($cmd['numres']);
        }
        return $html;
    }

    protected function executePost(): string
    {
        return <<<HTML
            <h2>Échec de la connexion</h2>
            <p>Identifiant ou mot de passe incorrect.</p>
            <p><a href="?action=SignIn">Réessayer</a></p>
            HTML;
    
    }
}