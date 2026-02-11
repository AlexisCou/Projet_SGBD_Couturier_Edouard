<h2>Nouvelle Réservation</h2>
<form method="POST" action="index.php?action=reserver">
    <div>
        <label>Table :</label>
        <select name="numtab" required>
            <?php 
            foreach(SGBD\models\tabl::all() as $t) {
                echo "<option value='{$t->numtab}'>Table {$t->numtab} ({$t->nbplace} places)</option>";
            }
            ?>
        </select>
    </div>
    <br>
    <div>
        <label>Nombre de personnes :</label>
        <input type="number" name="nbpers" min="1" required>
    </div>
    <br>
    <div>
        <label>Date et Heure :</label>
        <input type="datetime-local" name="datres" required>
    </div>
    <br>
    <button type="submit" name="submit_reserver">Confirmer la réservation</button>
</form>
<p><a href="index.php">Retour au menu</a></p>