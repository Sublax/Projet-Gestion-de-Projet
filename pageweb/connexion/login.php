<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/processLogin.js"></script>
</head>
<body>
<div id="particles-js"></div>
    <div class="login-container">
        <img src="../images/logo.png" alt="Logo" class="logo">
        <form action="process_login.php" method="post">
            <input id="username" type="text" name="username" placeholder="Entrez votre nom d'utilisateur ou e-mail" required>
            <input id="password" type="password" name="password" placeholder="Entrez votre mot de passe" required>
            <button id="loginButton" type="button" class="login-button">Connexion</button>
            <a id="registerButton" href="./register.php" class="signup-button">S’inscrire</a>
            <p><a href="../index.php">Une simple visite sans inscription ?</a></p>
        </form>
    </div>
    <script src="../styles/particles.js"></script>
</body>
</html>