<?php

namespace SGBD\action;

use SGBD\repository\SGBDrepository;

class PayReservationAction extends Action {

    protected function executeGet(): string
    {
        $repo = SGBDrepository::getInstance();
        $numres = isset($_GET['numres']) ? (int)$_GET['numres'] : 0;
        $prix = $repo->getPrixByCommande($numres);
        
        $html = '<h2>Paiement de la réservation</h2>';
        $html .= '<p>Vous êtes sur le point de payer la réservation n°' . $numres . ' pour un montant total de ' . $prix . '€.</p>';
        $html .= '<form method="POST">';
        $html .= '<input type="hidden" name="numres" value="' . $numres . '">';
        $html .= '<label for="methodePaiement">Méthode de paiement :</label><br>';
        $html .= '<select id="methodePaiement" name="methodePaiement">';
        $html .= '<option value="carte">Carte bancaire</option>';
        $html .= '<option value="especes">Espèces</option>';
        $html .= '<option value="cheque">Chèque</option>';
        $html .= '</select>';
        $html .= '<br><br><button type="submit">Confirmer le paiement</button>';
        $html .= '</form>';
        return $html;
    }
    protected function executePost(): string {
        $numres = isset($_GET['numres']) ? (int)$_GET['numres'] : 0;

        if ($numres === 0) {
            return "<p style='color: red;'>Erreur : Aucune réservation spécifiée.</p><p><a href='?action=ShowReservations'>Retour</a></p>";
        }

        $repo = SGBDrepository::getInstance();
        $prix = $repo->getPrixByCommande($numres);
        $success = $repo->encaisserReservation($numres, $_POST['methodePaiement'] ?? 'inconnue', $prix);

        if ($success) {
            return <<<HTML
                <div style="margin: 20px; padding: 15px; border: 1px solid green; background-color: #e6fffa;">
                    <h3 style="color: green;">Succès !</h3>
                    <p>La réservation n°$numres a été payée.</p>
                    <a href="?action=ShowReservations">Retour à la liste</a>
                </div>
HTML;
        } else {
            return <<<HTML
                <div style="margin: 20px; padding: 15px; border: 1px solid red; background-color: #fff5f5;">
                    <h3 style="color: red;">Erreur de paiement</h3>
                    <p>La réservation n°$numres ne peut pas être payée (elle est peut-être déjà payée ou inexistante).</p>
                    <a href="?action=ShowReservations">Retour à la liste</a>
                </div>
HTML;
        }
    }
}