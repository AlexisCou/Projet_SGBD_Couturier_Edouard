<?php

declare(strict_types=1);

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class ShowReservationsAction extends Action
{
    protected function executeGet(): string
    {
        $repo = SGBDrepository::getInstance();
        $html = '<h2>Liste des réservations</h2>';

        if (isset($_GET['cancel_id'])) {
            $idToCancel = (int)$_GET['cancel_id'];
            if ($repo->annulerReservation($idToCancel)) {
                $html .= "<p style='color: green; font-weight: bold;'>La réservation n°$idToCancel a été annulée avec succès.</p>";
            } else {
                $html .= "<p style='color: red; font-weight: bold;'>Erreur : Impossible d'annuler la réservation n°$idToCancel (déjà consommée ou inexistante).</p>";
            }
        }
        if (isset($_GET['pay_id'])) {
            header('Location: ?action=PayReservation&numres=' . (int)$_GET['pay_id']);
            exit();
        }

        $reservations = $repo->getReservation((int)$_SESSION['id']);

        if (empty($reservations)) {
            $html .= "<p>Aucune réservation pour le moment.</p>";
        }

        foreach ($reservations as $res) {
            $numres = $res['numres'];
            
            $html .= '<div style="border: 1px solid #ccc; margin-bottom: 20px; padding: 10px;">';
            $html .= '<h3>Réservation n°' . $numres . '</h3>';
            $html .= '<p>Date : ' . $res['datres'] . '</p>';
            $html .= '<p>Nombre de personnes : ' . $res['nbpers'] . '</p>';
            $html .= '<p>Table n° : ' . $res['numtab'] . '</p>';

            $prix = $repo->getPrixByCommande($numres);
            $html .= '<h4>Détail de la commande - Total : ' . $prix . '€ ';
            
            if (is_null($res['datpaie'])) {
                $html .= '<a href="?action=add-commande&numres=' . $numres . '" style="font-size: 0.7em; color: blue; text-decoration: none; margin-left: 10px;">[+ Ajouter un plat]</a>';
            }
            $html .= '</h4>';

            $html .= $repo->getPlatsByCommande($numres);

            if (is_null($res['datpaie'])) {
                $html .= '<p><a href="?action=ShowReservations&cancel_id=' . $numres . '" 
                           style="color: red; font-weight: bold;" 
                           onclick="return confirm(\'Voulez-vous vraiment annuler cette réservation ?\')">
                           [Annuler la réservation]
                          </a>
                          <a href="?action=ShowReservations&pay_id=' . $numres . '" 
                           style="color: blue; font-weight: bold;" 
                           onclick="return confirm(\'Voulez-vous payer cette réservation ?\')">
                           [Payer la réservation]
                          </a></p>';
            } else {
                $html .= '<p style="color: gray; font-style: italic;">Réservation consommée (payée le ' . $res['datpaie'] . ')</p>';
            }

            $html .= '</div>';
        }
        
        return $html;
    }

    protected function executePost(): string
    {
        return $this->executeGet();
    }
}