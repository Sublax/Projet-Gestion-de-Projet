<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../styles/style_max.css">
</head>
<body>
    <div class="register-container">
        <img src="logo.png" alt="Logo" class="logo">
        <h2>Créez votre compte !</h2>


        <?php
        session_start();
        $username = $_GET['username'] ?? '';
        $firstName = $_GET['first_name'] ?? '';
        $lastName = $_GET['last_name'] ?? '';
        $email = $_GET['email'] ?? '';
        $location = $_GET['location'] ?? '';
        ?>
        <form action="./process_register.php" method="post">
            <input type="text" name="username" placeholder="Nom d'utilisateur" value="<?php echo htmlspecialchars($username); ?>" required>
            <div class="name-fields">
                <input type="text" name="first_name" placeholder="Prénom" value="<?php echo htmlspecialchars($firstName); ?>" required>
                <input type="text" name="last_name" placeholder="Nom de famille" value="<?php echo htmlspecialchars($lastName); ?>" required>
            </div>
            <input type="email" name="email" placeholder="Adresse e-mail" value="<?php echo htmlspecialchars($email); ?>" required>
            <input type="text" name="location" placeholder="Localisation" value="<?php echo htmlspecialchars($location); ?>" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            <button type="submit" class="register-button">S’inscrire</button>
            <a href="./login.php" class="back-button">Retour</a>
        </form>
    </div>
</body>
</html>