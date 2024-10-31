<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Initialiser un tableau pour stocker les erreurs
$errors = [];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $username = $_POST['username'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $location = $_POST['location'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Vérification des champs vides
    if (empty($username) || empty($firstName) || empty($lastName) || empty($email) || empty($location) || empty($password) || empty($confirmPassword)) {
        $errors[] = "Veuillez remplir tous les champs.";
    }

    // Vérification si les mots de passe correspondent
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Si aucune erreur, rediriger pour traiter l'inscription
    if (empty($errors)) {
        // Exemple d'un message de succès ou une redirection
        $_SESSION['success'] = "Inscription réussie !";
        header("Location: process_register.php");
        exit();
    }
}
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
            <input type="text" name="username" placeholder="Nom d'utilisateur" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            <div class="name-fields">
                <input type="text" name="first_name" placeholder="Prénom" value="<?php echo htmlspecialchars($firstName ?? ''); ?>" required>
                <input type="text" name="last_name" placeholder="Nom de famille" value="<?php echo htmlspecialchars($lastName ?? ''); ?>" required>
            </div>
            <input type="email" name="email" placeholder="Adresse e-mail" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            <input type="text" name="location" placeholder="Localisation" value="<?php echo htmlspecialchars($location ?? ''); ?>" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            <button type="submit" class="register-button">S’inscrire</button>
            <a href="login.php" class="back-button">Retour</a>
        </form>
    </div>
</body>
</html>
