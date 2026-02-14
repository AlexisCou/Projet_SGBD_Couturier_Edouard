<?php

declare(strict_types=1);

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class ShowPlatsAction extends Action
{
    protected function executeGet(): string
    {
        $repo = SGBDrepository::getInstance();
        $plats = $repo->getPlats();
        
        $html = '<h2>Liste des plats</h2>';
        $html .= '<ul style="list-style: none; padding: 0;">';

        foreach ($plats as $p) {
            $html .= '<li style="margin-bottom: 15px; padding: 10px; border-bottom: 1px solid #eee;">';
            
            $html .= "<strong>{$p['libelle']}</strong><br>";
            $html .= "Prix : {$p['prixunit']}€ | Stock : {$p['qteservie']} <br>";

            $html .= '<a href="?action=ModifierPrix&numplat=' . $p['numplat'] . '" 
                         style="display: inline-block; margin-top: 5px; color: #007bff; text-decoration: none;">
                         [Modifier le prix ou le stock]
                      </a>';
            
            $html .= '</li>';
        }

        $html .= '</ul>';
        return $html;
    }

    protected function executePost(): string
    {
        return $this->executeGet();
    }
}