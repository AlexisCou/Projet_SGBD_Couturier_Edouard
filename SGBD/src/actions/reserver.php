<?php
use Illuminate\Database\Capsule\Manager as DB;
use SGBD\models\reservation;
use SGBD\models\tabl;

if (isset($_POST['submit_reserver'])) {
    DB::beginTransaction();

    try {
        $numTable = $_POST['numtab'];
        $nbPers = $_POST['nbpers'];
        $dateRes = str_replace('T', ' ', $_POST['datres']) . ':00';

        $table = tabl::find($numTable);
        if ($table->nbplace < $nbPers) {
            throw new Exception("La table {$numTable} est trop petite ({$table->nbplace} places max).");
        }

        $res = new reservation();
        $res->numtab = $numTable;
        $res->id_serv = $_SESSION['user_id'];
        $res->datres = $dateRes;
        $res->nbpers = $nbPers;
        $res->save();

        DB::commit();
        echo "<p style='color:green'>Réservation n°{$res->numres} enregistrée avec succès !</p>";

    } catch (Exception $e) {
        DB::rollBack();
        echo "<p style='color:red'>Erreur : " . $e->getMessage() . "</p>";
    }
}

include __DIR__ . '/../views/form_reserver.php';