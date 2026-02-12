<?php

declare(strict_types=1);

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class ShowReservationsAction extends Action
{
    protected function executeGet(): string
    {
        $reservations = SGBDrepository::getInstance()->getReservation($_SESSION['id']);
        $html = '<h2>Liste des réservations</h2>';
        foreach ($reservations as $res) {
            $html .= '<h3>Réservation n°' . $res['numres'] . ' <a href="?action=modifierReservation">Modifier</a></h3>';
            $html .= '<p>Date : ' . $res['datres'] . '</p>';
            $html .= '<p>Nombre de personnes : ' . $res['nbpers'] . '</p>';
            $html .= '<h4>Commande n°' . $res['numres'] . ' - Prix : ' . SGBDrepository::getInstance()->getPrixByCommande($res['numres']) . '€ <a href="?action=modifierCommande">Modifier</a></h4>';
            $html .= '<ul><p>Plats commandés :</p>';
            $html .= SGBDrepository::getInstance()->getPlatsByCommande($res['numres']);
            $html .= '</ul>';
        }
        return $html;
    }

    protected function executePost(): string
    {
        return $this->executeGet();
    }
}