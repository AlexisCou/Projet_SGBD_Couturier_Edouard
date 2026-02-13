<?php

namespace SGBD\dispatch;

use SGBD\action as A;

class dispatcher {

    private $action;

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void {
        $html = "";
        
        $act = null; 

        switch ($this->action) {
            case 'ShowPlats':
                $act = new A\ShowPlatsAction();
                break;
            case 'ShowReservations':
                $act = new A\ShowReservationsAction();
                break;
            case 'SignOut':
                $act = new A\SignOutAction();
                break;
            case 'SignIn':
                $act = new A\SignInAction();
                break;
            case 'add-reservation':
                $action = new \SGBD\action\AddReservationAction();
                $this->renderPage($action->execute());
                break;    
            default:
                $act = new A\DefaultAction();
                break;
        }

        $html = $act(); 
        $this->renderPage($html);
    }

    private function renderPage(string $html): void {
        $page = '<!DOCTYPE html>';
        $page .= '<html lang="fr">';
        $page .= '<head>';
        $page .= '<meta charset="UTF-8">';
        $page .= '<title>Projet SGBD</title>';
        $page .= '</head>';
        $page .= '<body>';

        $page .= '<header>';
        $page .= '<h1>Projet SGBD EDOUARD Justin COUTURIER Alexis</h1>';
        $page .= '<nav>';
        $page .= '<ul>';
        if (isset($_SESSION['username'])) {
            $page .= '<li><a href="?action=default">Acceuil</a></li>';
            $page .= '<li><a href="?action=add-reservation">Ajouter une réservation</a></li>';
            $page .= '<li><a href="?action=ShowReservations">Liste des réservations</a></li>';
            $page .= '<li><a href="?action=ShowPlats">Liste des plats</a></li>';
            $page .= '<li><a href="?action=SignOut">Déconnexion</a></li>';
        } else {
            $page .= '<li><a href="?action=SignIn">Connexion</a></li>';
        }
        $page .= '</ul>';
        $page .= '</nav>';
        $page .= '</header>';

        $page .= '<main>';
        $page .= $html;
        $page .= '</main>';

        $page .= '</body>';
        $page .= '</html>';

        echo $page;

    }
}