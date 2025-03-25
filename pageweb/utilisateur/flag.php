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
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            cursor: pointer;
            display: inline-block;
            position: relative;
            border-radius: 8px; /* Coins arrondis pour un effet plus doux */
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        /* Effet au survol */
        .flag-item:hover {
            transform: scale(1.1); /* Légèrement plus grand pour attirer l'attention */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Ombre subtile pour effet de profondeur */
        }

        /* Effet de sélection */
        .flag-item.selected {
            opacity: 1; /* On garde l'opacité normale */
            border: 3px solid #007BFF; /* Contour bleu lumineux pour indiquer la sélection */
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.5); /* Effet lumineux */
            transform: scale(1.1); /* Mise en avant */
        }   

        .country-name {
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.6); /* Fond semi-transparent */
            color: white;
            font-size: 9px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            white-space: nowrap;
        }
        .flag-item:hover {
            border-color: #333;
        }
/* Bandeau des pays sélectionnés */
#selectedCountriesContainer {
    position: sticky;
    top: 95px; /* Ajuste la hauteur pour laisser de la place au logo */
    width: 100%;
    background: #F0F0F0;
    padding: 15px 20px;
    text-align: center;
    color: black;
    font-weight: bold;
    font-size: 18px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-bottom: 2px solid #ddd; /* Légère séparation */
}

/*Liste des pays sélectionnés */
#selectedCountriesList {
    list-style: none;
    padding: 0;
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    border-radius: 0 0 10px 10px;
}

/* Style des pays sélectionnés (colorés) */
.selected-item {
    background: linear-gradient(45deg, red, blue);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: bold;
    transition: transform 0.2s, box-shadow 0.2s;
}

/* Effet au survol */
.selected-item:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* Drapeaux dans la liste */
.selected-item img {
    width: 20px;
    height: 15px;
    border-radius: 3px;
}
.flag-item {
    width: 100px;
    height: 70px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    border: 2px solid transparent;
    cursor: pointer;
    position: relative;
    margin: 5px;
}

.flag-item.selected {
    border-color: red;
}

.country-name {
    background: rgba(0, 0, 0, 0.6);
    color: white;
    font-size: 12px;
    text-align: center;
    width: 100%;
    padding: 2px;
    position: absolute;
    bottom: 0;
}

#searchBar {
    width: 100%;
    max-width: 300px;
    padding: 12px 20px;
    margin-top: 65px;
    margin-left: 70px;
    border: 2px solid #ddd;
    border-radius: 50px; /* Bordure arrondie pour un look moderne */
    font-size: 16px;
    font-family: 'Arial', sans-serif;
    background-color: #f5f5f5;
    color: #333;
    outline: none;
    transition: all 0.3s ease-in-out; /* Transition fluide pour les changements */
}

/* Effet de focus élégant */
#searchBar:focus {
    border-color: #4CAF50; 
    box-shadow: 0 0 8px rgba(76, 175, 80, 0.5); 
    background-color: #ffffff;
    transition: all 0.3s ease-in-out; /* Transition fluide */
    color: #333; 
}

/* Style de texte au focus pour un effet moderne */
#searchBar::placeholder {
    color: #888; /* Couleur du placeholder */
    transition: color 0.3s ease-in-out;
}
    </style>
</head>
<body>
    <div id="selectedCountriesContainer">
        <h3>Pays sélectionnés :</h3>
        <ul id="selectedCountriesList"></ul>
    </div>


    <div>
    <input type="text" id="searchBar" placeholder="Rechercher un pays..." />
    </div>
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

// Fonction pour afficher les drapeaux
function displayFlags(countries) {
    const searchQuery = document.getElementById('searchBar').value.toLowerCase();
    const filteredCountries = countries.filter(country => 
        country.name.common.toLowerCase().includes(searchQuery)
    );
    
    const flagContainer = document.getElementById('flagContainer');
    flagContainer.innerHTML = ''; // Vider le conteneur avant d'ajouter de nouveaux drapeaux

    // Trier les pays par ordre alphabétique
    filteredCountries.sort((a, b) => a.name.common.localeCompare(b.name.common));

    filteredCountries.forEach(country => {
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

function updateSelectedList() {
    const selectedList = document.getElementById("selectedCountriesList");
    selectedList.innerHTML = ""; // Vide la liste avant de la remplir

    selectedCountries.forEach(countryName => {
        const listItem = document.createElement("li");
        listItem.classList.add("selected-item");

        // Trouver les données du pays pour récupérer son code
        const cachedCountries = JSON.parse(localStorage.getItem("countriesFlags")) || [];
        const countryData = cachedCountries.find(c => c.name.common === countryName);
        
        if (countryData && countryData.cca2) {
            const flagImg = document.createElement("img");
            flagImg.src = `https://flagcdn.com/w40/${countryData.cca2.toLowerCase()}.png`;
            flagImg.alt = `Drapeau de ${countryName}`;

            listItem.appendChild(flagImg); // Ajoute le drapeau avant le texte
        }

        // Ajout du nom du pays
        const textNode = document.createTextNode(countryName);
        listItem.appendChild(textNode);

        // Ajout de l’événement pour désélectionner
        listItem.onclick = () => deselectCountry(countryName);

        selectedList.appendChild(listItem);
    });
}
        // Fonction pour gérer la sélection/désélection d'un pays
        function selectCountry(flagElement, country) {
            if (selectedCountries.includes(country.name.common)) {
                deselectCountry(country.name.common);
            } else {
                if (selectedCountries.length < 3) {
                    flagElement.classList.add('selected');
                    selectedCountries.push(country.name.common);
                    updateSelectedList();
                } else {
                    alert("Tu peux sélectionner au maximum 3 pays.");
                }
            }
        }
        function sendSelectedCountries() {
            if (selectedCountries.length < 3) {
                alert("Veuillez sélectionner 3 pays avant d'envoyer.");
                return;
            }
            // Création de l'objet JSON
            const data = {
                selectedCountries: selectedCountries
            };
            fetch("projet-gestion-de-projet-production.up.railway.app/receive_json", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            console.log("JSON envoyé :", JSON.stringify(data));
        }

        // Fonction pour désélectionner un pays depuis la liste
        function deselectCountry(countryName) {
            selectedCountries = selectedCountries.filter(c => c !== countryName);
            updateSelectedList();
            refreshFlagSelection();
        }

        // Fonction pour mettre à jour l'affichage des drapeaux après une désélection
        function refreshFlagSelection() {
            document.querySelectorAll(".flag-item").forEach(flagElement => {
                const countryName = flagElement.querySelector(".country-name").innerText;
                if (selectedCountries.includes(countryName)) {
                    flagElement.classList.add("selected");
                } else {
                    flagElement.classList.remove("selected");
                }
            });
        }
        // Appel de la fonction pour récupérer les pays et leurs drapeaux
        fetchCountries();
        // Fonction pour filtrer les pays en fonction de la recherche
        document.getElementById('searchBar').addEventListener('input', () => {
            const cachedCountries = JSON.parse(localStorage.getItem('countriesFlags')) || [];
            displayFlags(cachedCountries);
        });
        document.getElementById("sendButtonPays").addEventListener("click", sendSelectedCountries);

</script>


<script>
</script>
</body>
</html>
