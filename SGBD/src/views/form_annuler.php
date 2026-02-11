<h2>Annuler une réservation</h2>
<form method="POST" action="index.php?action=annuler">
    <div>
        <label>N° Réservation à annuler :</label>
        <input type="number" name="numres" required>
    </div>
    <br>
    <button type="submit" name="submit_annuler" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">Annuler la réservation</button>
</form>
<p><a href="index.php">Retour au menu</a></p>