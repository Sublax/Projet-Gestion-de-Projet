<?php
session_start();

// Verificar si hay datos previamente enviados en la sesión
$username = $_SESSION['form_data']['username'] ?? '';
$firstName = $_SESSION['form_data']['first_name'] ?? '';
$lastName = $_SESSION['form_data']['last_name'] ?? '';
$email = $_SESSION['form_data']['email'] ?? '';
$location = $_SESSION['form_data']['location'] ?? '';

// Verificar si hay errores en la sesión
$errors = $_SESSION['errors'] ?? [];

// Limpiar los datos de la sesión después de mostrarlos
unset($_SESSION['form_data'], $_SESSION['errors']);
?>

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

        <!-- Afficher les erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="errors" style="color: red;">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form action="process_register.php" method="post">
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
            <a href="login.php" class="back-button">Retour</a>
        </form>
    </div>
</body>
</html>