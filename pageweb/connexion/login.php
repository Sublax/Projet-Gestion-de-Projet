<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../styles/style_max.css">
</head>
<body>
    <div class="login-container">
        <img src="logo.png" alt="Logo" class="logo">
        <form action="process_login.php" method="post">
            <input type="text" name="username" placeholder="Enter username" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit" class="login-button">Connexion</button>
            <a href="./register.php" class="signup-button">S’inscrire</a>
            <p><a href="../index.php">Une simple visite sans inscription ?</a></p>
        </form>
    </div>
</body>
</html>