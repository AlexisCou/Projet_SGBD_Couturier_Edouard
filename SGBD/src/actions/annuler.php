<?php
use SGBD\models\reservation;
use SGBD\models\commande;
use Illuminate\Database\Capsule\Manager as DB;

if (isset($_POST['submit_annuler'])) {
    $numres = $_POST['numres'];

    DB::beginTransaction();
    try {
        $res = reservation::find($numres);
        if (!$res) {
            throw new Exception("La réservation n°$numres n'existe pas.");
        }

        $nbCommandes = commande::where('numres', $numres)->count();
        if ($nbCommandes > 0) {
            throw new Exception("Impossible d'annuler : cette table a déjà commencé à commander ($nbCommandes plats enregistrés).");
        }

        $res->delete();

        DB::commit();
        echo "<p style='color:green'>La réservation n°$numres a été annulée.</p>";
    } catch (Exception $e) {
        DB::rollBack();
        echo "<p style='color:red'>Erreur : " . $e->getMessage() . "</p>";
    }
}

include __DIR__ . '/../views/form_annuler.php';