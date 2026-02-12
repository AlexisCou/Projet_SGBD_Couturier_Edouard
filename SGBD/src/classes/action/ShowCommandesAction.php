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
        return $this->executeGet();
    }
}