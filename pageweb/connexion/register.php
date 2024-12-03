<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
        $visitedCountry = $_GET["visitedCountry"] ?? '';
        ?>
        <form action="./process_register.php" method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" value="<?php echo htmlspecialchars($username); ?>" required>
        <div class="name-fields">
            <input type="text" name="first_name" placeholder="Prénom" value="<?php echo htmlspecialchars($firstName); ?>" required>
            <input type="text" name="last_name" placeholder="Nom de famille" value="<?php echo htmlspecialchars($lastName); ?>" required>
        </div>
            <input type="email" name="email" placeholder="Adresse e-mail" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label for="country">Sélectionnez votre pays :</label>
            <label class= "numberOfVisitedCountry" id="numberInputRegister" for="numberInput">Nombre de pays visité : </label>
            <div class="select">
            <select name="country" id="country" required>
            <option value="">-- Sélectionnez un pays --</option>
            </select>
            <input class="numberOfVisitedCountry" type="number" id="numberInput" name="visitedCountry" value="<?php echo htmlspecialchars($visitedCountry); ?>" >
            </div>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
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
    <script src="../styles/particles.js"></script>
</body>
</html>