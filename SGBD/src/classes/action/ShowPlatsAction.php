<?php

declare(strict_types=1);

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class ShowPlatsAction extends Action
{
    protected function executeGet(): string
    {
        $html = '<h2>Liste des plats</h2><ul>';
        $plats = SGBDrepository::getInstance()->getPlats();
        foreach($plats as $p){
            $html .= '<li>'.$p['libelle'].' - '.$p['prixunit'].'€  <a href=?action=ModifierPrix&id='.$p['numplat'].'>Modifier prix</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    protected function executePost(): string
    {
        return $this->executeGet();
    }
}