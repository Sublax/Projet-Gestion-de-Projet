<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script type="module" src="../components/bundle.js"></script>
</head>
<body>
    
    <div class="login-container">
        <img src="../images/logo.png" alt="Logo" class="logo">
        <form action="process_login.php" method="post">
        <md-outlined-text-field name="username" placeholder="Nom d'utilisateur" label="Nom utilisateur" required>
        </md-outlined-text-field>
</br></br>
        <md-outlined-text-field name="password" type="password" placeholder="Enter password" label="Password" required>
        </md-outlined-text-field>
</br></br>
            <button type="submit" class="login-button">Connexion</button>
            <a href="./register.php" class="signup-button">S’inscrire</a>
        <?php
            if (isset($_SESSION['successMessage'])) {
                echo "<div style='color: green;'>" . htmlspecialchars($_SESSION['successMessage']) . "</div>";
                unset($_SESSION['successMessage']);
            }
        ?>

            <p><a href="../index.php">Une simple visite sans inscription ?</a></p>
        </form>
    </div>
</body>
</html>