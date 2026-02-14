<?php

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class CancelReservationAction extends Action {

    public function execute(): string {
        $numres = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($numres === 0) {
            return "<p style='color: red;'>Erreur : Aucune réservation spécifiée.</p><p><a href='?action=ShowReservations'>Retour</a></p>";
        }

        $repo = SGBDrepository::getInstance();
        
        $success = $repo->annulerReservation($numres);

        if ($success) {
            return <<<HTML
                <div style="margin: 20px; padding: 15px; border: 1px solid green; background-color: #e6fffa;">
                    <h3 style="color: green;">Succès !</h3>
                    <p>La réservation n°$numres a été annulée. Les tables et stocks ont été libérés.</p>
                    <a href="?action=ShowReservations">Retour à la liste</a>
                </div>
HTML;
        } else {
            return <<<HTML
                <div style="margin: 20px; padding: 15px; border: 1px solid red; background-color: #fff5f5;">
                    <h3 style="color: red;">Annulation impossible</h3>
                    <p>La réservation n°$numres ne peut pas être annulée (elle est peut-être déjà payée ou inexistante).</p>
                    <a href="?action=ShowReservations">Retour à la liste</a>
                </div>
HTML;
        }
    }
}