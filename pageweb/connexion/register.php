<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../styles/style_max.css">
    <script>
        function validateForm() {
            // Récupérer les valeurs des champs
            const username = document.forms["registerForm"]["username"].value;
            const firstName = document.forms["registerForm"]["first_name"].value;
            const lastName = document.forms["registerForm"]["last_name"].value;
            const email = document.forms["registerForm"]["email"].value;
            const location = document.forms["registerForm"]["location"].value;
            const password = document.forms["registerForm"]["password"].value;
            const confirmPassword = document.forms["registerForm"]["confirm_password"].value;

            // Vérifier si les champs sont vides
            if (username === "" || firstName === "" || lastName === "" || email === "" || location === "" || password === "" || confirmPassword === "") {
                alert("Veuillez remplir tous les champs.");
                return false;
            }

            // Vérifier si les mots de passe sont égaux
            if (password !== confirmPassword) {
                alert("Les mots de passe ne correspondent pas.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="register-container">
        <img src="logo.png" alt="Logo" class="logo">
        <h2>Créez votre compte !</h2>
        <form name="registerForm" action="process_register.php" method="post" onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <div class="name-fields">
                <input type="text" name="first_name" placeholder="Prénom" required>
                <input type="text" name="last_name" placeholder="Nom de famille" required>
            </div>
            <input type="email" name="email" placeholder="Adresse e-mail" required>
            <input type="text" name="location" placeholder="Localisation" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            <button type="submit" class="register-button">S’inscrire</button>
            <a href="login.php" class="back-button">Retour</a>
        </form>
    </div>
</body>
</html>
