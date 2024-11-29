<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div id="particles-js"></div>
    <div class="login-container">
        <img src="../images/logo.png" alt="Logo" class="logo">
        <form action="process_login.php" method="post">
            <input id="username" type="text" name="username" placeholder="Entrez votre nom d'utilisateur" required>
            <input id="password" type="password" name="password" placeholder="Entrez votre mot de passe" required>
            <button id="loginButton" type="button" class="login-button">Connexion</button>
            <a href="./register.php" class="signup-button">S’inscrire</a>
        <?php
            if (isset($_SESSION['successMessage'])) {
                echo "<div style='color: green;font-weight:bold;font-size:17px'>" . htmlspecialchars($_SESSION['successMessage']) . "</div>";
                unset($_SESSION["successMessage"]);
            }
        ?>

            <p><a href="../index.php">Une simple visite sans inscription ?</a></p>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../styles/particles.js"></script>


<script>
    $.ajax({
        url: "./process_login.php",
        type: "POST",
        dataType: "text",
        data:{
            username: $("#username").val(),
            password: $("#password").val()
        },
        success: function(response){
            if(success !== '1'){
                $("#loginButton").after("<p class='messageErreur'> Les informations sont incorrects.</p>")
            }
        }
    })
</script>
</body>
</html>