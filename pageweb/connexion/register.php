<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script type="module" src="../components/bundle.js"></script>

</head>
<body>
<script>
        // Fonction pour charger les pays depuis l'API REST Countries
        function loadCountries() {
            // URL de l'API REST Countries
            const apiUrl = 'https://restcountries.com/v3.1/all';
            
            // Faire une requête fetch pour récupérer les données des pays
            fetch(apiUrl)
                .then(response => response.json())  // Convertir la réponse en JSON
                .then(countries => {
                    // Sélectionner le menu déroulant (select)
                    const countrySelect = document.getElementById('country');
                    
                    // Trier les pays par nom (optionnel)
                    countries.sort((a, b) => a.name.common.localeCompare(b.name.common));
                    
                    // Boucle à travers chaque pays et ajouter une option au menu déroulant
                    countries.forEach(country => {
                        const option = document.createElement('option');
                        option.textContent = country.name.common;  // Nom du pays
                        countrySelect.appendChild(option);  // Ajouter l'option au select
                    });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des pays:', error);
                });
        }
        
        // Appeler la fonction loadCountries lorsque la page est prête
        window.onload = loadCountries;
</script>

<div id="particles-js"></div>

    <div class="register-container">
        <img src="../images/logo.png" alt="Logo" class="logo">
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
        <md-outlined-text-field name="username" placeholder="Nom d'utilisateur" label="Nom utilisateur" value="<?php echo htmlspecialchars($username); ?>"  required></md-outlined-text-field>
            <div class="name-fields">
                <md-outlined-text-field name="first_name" placeholder="Prénom" label="Prénom" value="<?php echo htmlspecialchars($firstName); ?>" required></md-outlined-text-field>
                <md-outlined-text-field name="last_name" placeholder="Nom de famille" label="Nom" value="<?php echo htmlspecialchars($lastName); ?>" required></md-outlined-text-field>
            </div>
            <md-outlined-text-field type="email" name="email" placeholder="Adresse e-mail" label="Adresse mail" value="<?php echo htmlspecialchars($email); ?>" required></md-outlined-text-field>

            
            <label for="country">Sélectionnez votre pays :</label>
            <select name="country" id="country" required>
            <option value="">-- Sélectionnez un pays --</option>
            </select>
            <md-outlined-text-field name="password" type="password" placeholder="Enter password" label="Password" required>
            </md-outlined-text-field>
            <md-outlined-text-field type="password" name="confirm_password" placeholder="Confirmer le mot de passe" label="Confirmation mot de passe" required></md-outlined-text-field>
            <button type="submit" class="register-button">S’inscrire</button>
            <a href="./login.php" class="back-button">Retour</a>
            <?php
    if (isset($_SESSION['errorMessage'])) {
        echo "<div style='color: red;'>" . htmlspecialchars($_SESSION['errorMessage']) . "</div>";
        unset($_SESSION['errorMessage']);
        
    }
?>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../styles/particles.js"></script>
</body>
</html>