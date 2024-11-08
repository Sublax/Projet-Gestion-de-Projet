<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    
    <div class="login-container">
        <img src="logo.png" alt="Logo" class="logo">
        <form action="process_login.php" method="post">
            <input type="text" name="username" placeholder="Enter username" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit" class="login-button">Connexion</button>
            <a href="./register.php" class="signup-button">S’inscrire</a>
<?php
    if (isset($_SESSION['errorMessage'])) {
        echo "<div style='color: red;'>" . htmlspecialchars($_SESSION['errorMessage']) . "</div>";
        
    }
    if (isset($_SESSION['successMessage'])) {
        echo "<div style='color: green;'>" . htmlspecialchars($_SESSION['successMessage']) . "</div>";
    }
?>
            <p><a href="../index.php">Une simple visite sans inscription ?</a></p>
        </form>
    </div>
</body>
</html>