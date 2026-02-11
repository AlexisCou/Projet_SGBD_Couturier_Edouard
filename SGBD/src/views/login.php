<div class="login-container">
    <h2>Accès Serveur</h2>
    <?php if($error): ?>
        <p style="color:red"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <input type="text" name="login" placeholder="Login" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit" name="login_submit">Se connecter</button>
    </form>
</div>