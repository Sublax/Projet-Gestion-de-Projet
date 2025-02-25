<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet - Sélection pays</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<?php include '../navbar.php'; ?>
    <title>Sélectionner un pays</title>
    <style>
        .flag-container {
            margin-top: 50px;
        }
        .flag-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .flag-item {
            width: 100px;
            height: 60px;
            margin: 10px;
            background-size: contain;  /* Assure-toi que l'image s'adapte à l'intérieur de l'élément */
            background-position: center;  /* Centre l'image à l'intérieur du conteneur */
            background-repeat: no-repeat;  /* Empêche la répétition de l'image */
            cursor: pointer;
            display: inline-block;
            position: relative;
            border-radius: 5px; /* Optionnel, pour ajouter des bords arrondis aux drapeaux */
        }

        .flag-item.selected {
            opacity: 0.5; /* Réduit l'opacité pour "griser" l'élément */
        }

        .country-name {
            position: absolute;
            bottom: 5px;
            left: 5px;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        .flag-item:hover {
            border-color: #333;
        }
        .country-name {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="flag-container" id="flagContainer">Chargement...</div>
    <button id="sendButtonPays">Envoyer les pays sélectionnés</button>

    <script>
        // Fonction pour récupérer les pays depuis l'API REST Countries
        async function fetchCountries() {
            try {
                const flagContainer = document.getElementById('flagContainer');
                flagContainer.innerHTML = ''; // Vide le message de chargement
                
                // Vérifier si les données sont déjà dans le localStorage
                const cachedFlags = localStorage.getItem('countriesFlags');
                if (cachedFlags) {
                    // Si les données sont en cache, on les charge directement
                    displayFlags(JSON.parse(cachedFlags));
                } else {
                    // Si pas en cache, on charge depuis l'API
                    const response = await fetch('https://restcountries.com/v3.1/all');
                    const countries = await response.json();
                    
                    // On enregistre les données dans le cache local
                    localStorage.setItem('countriesFlags', JSON.stringify(countries));
                    
                    // Affichage des drapeaux
                    displayFlags(countries);
                }
            } catch (error) {
                console.error('Erreur lors de la récupération des pays:', error);
            }
        }

        let selectedCountries = [];

// Fonction pour afficher les drapeaux (comme avant)
function displayFlags(countries) {
    const flagContainer = document.getElementById('flagContainer');
    flagContainer.innerHTML = ''; // Vider le conteneur avant d'ajouter de nouveaux drapeaux

    // Trier les pays par ordre alphabétique
    countries.sort((a, b) => a.name.common.localeCompare(b.name.common));

    countries.forEach(country => {
        const countryCode = country.cca2 ? country.cca2.toLowerCase() : null;
        if (!countryCode) return; // Ignore les pays sans code valide

        const flagElement = document.createElement('div');
        flagElement.classList.add('flag-item');

        // Construction de l'URL du drapeau
        const flagUrl = `https://flagcdn.com/w320/${countryCode}.png`;
        flagElement.style.backgroundImage = `url('${flagUrl}')`;

        // Affichage du nom du pays
        const countryName = document.createElement('div');
        countryName.classList.add('country-name');
        countryName.innerText = country.name.common;

        // Quand on clique sur un pays, on le sélectionne/désélectionne
        flagElement.onclick = () => selectCountry(flagElement, country);

        // Si le pays est déjà sélectionné, on applique la classe 'selected'
        if (selectedCountries.includes(country.name.common)) {
            flagElement.classList.add('selected');
        }

        flagElement.appendChild(countryName);
        flagContainer.appendChild(flagElement);
    });
}

// Fonction pour gérer la sélection des pays
function selectCountry(flagElement, country) {
    // Vérifie si le pays est déjà sélectionné
    if (selectedCountries.includes(country.name.common)) {
        // Si le pays est déjà sélectionné, on le désélectionne
        flagElement.classList.remove('selected');
        selectedCountries = selectedCountries.filter(c => c !== country.name.common);
    } else {
        // Si le pays n'est pas sélectionné, et qu'il y a encore de la place (moins de 3 pays sélectionnés)
        if (selectedCountries.length < 3) {
            flagElement.classList.add('selected');
            selectedCountries.push(country.name.common);
        } else {
            alert("Tu peux sélectionner au maximum 3 pays.");
        }
    }
}

        function sendSelectedCountries() {
            if (selectedCountries.length === 0) {
                alert("Sélectionne au moins un pays avant d'envoyer.");
                return;
            }
            // Création de l'objet JSON
            const data = {
                selectedCountries: selectedCountries
            };
            fetch("http://127.0.0.1:5000/receive_json", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            // Affichage du JSON dans la console (que tu pourras récupérer côté Python)
            console.log("JSON envoyé :", JSON.stringify(data));
        }

        // Appel de la fonction pour récupérer les pays et leurs drapeaux
        fetchCountries();
        document.getElementById("sendButtonPays").addEventListener("click", sendSelectedCountries);

</script>


<script>
</script>
</body>
</html>
