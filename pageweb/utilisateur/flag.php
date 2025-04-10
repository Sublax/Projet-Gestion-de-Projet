<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet - Sélection pays</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<?php include '../navbar.php'; ?>
    <title>Boule de cristal des pays</title>
</head>
<body>
<h1 id="titreFlag"> 🔮 Prédiction des pays</h1>
    <div id="selectedCountriesContainer">
        <h3>Pays sélectionnés :</h3>
        <ul id="selectedCountriesList"></ul>
    </div>

    <div>
        <input type="text" id="searchBar" placeholder="✨ Rechercher un pays..." />
        <p class="messageFlag">🌍 Sélectionnez 3 pays différents et des pays prédis apparaîtront !</p>
        <p class="messageFlag">⏳ Un temps de 10 secondes est prévu entre chaque requête, la première peut ne pas fonctionner.</p>
        <p class="messageFlag">📁 Vous retrouverez les résultats, et cette page sur <strong> votre profil !</strong></p>
        <p class="messageFlag"> Si vous souhaitez <strong>réinitialiser vos résultats</strong>,<a id="fermerSession"> cliquez-ici</a> !</p>
        <p class="messageFlag" id="messageErreurFermetureSessions"></p>
    </div>
    <div class="flag-container" id="flagContainer">Chargement...</div>
    <button id="sendButtonPays">Envoyer les pays sélectionnés</button>
    <div class="popup" id="popup">
        <img src="../images/tick.png">
        <h2 id="popupMessage"></h2>
        <ul id="paysPredis"></ul>
        <p id="errorPopup"></p>
        <button type="button" onclick="closePopup()"> OK !</button>
    </div>

    <script>
        let bouttonFermeture = document.getElementById("fermerSession");
        bouttonFermeture.addEventListener("click", () =>{
            fetch("fermesession.php",{
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                })
            .then(response => response.text())
            .then(data => {
            if(data.trim() === "0"){
                document.getElementById("messageErreurFermetureSessions").textContent = "Erreur : La session n'existait pas.";
            }else{
                // Sinon, procédez normalement (par exemple, rediriger ou afficher un message de succès)
                document.getElementById("messageErreurFermetureSessions").textContent = "La demande a été traitée avec succès ! ";
            }});
        });


        let popup = document.getElementById("popup");
        function closePopup(){
            /*
            Fonction pour fermer la popup et l'ouvrir quand requete
            */
            popup.classList.remove("open-popup");
        }

        // 
        async function fetchCountries() {
            /*
            Fonction pour récupérer les pays depuis l'API REST Countries (générée par IA et modifier)
            */
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
                    const response = await fetch('https://restcountries.com/v3.1/all?fields=name,flag,cca2');
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

    
    function displayFlags(countries) {
        /*
        Fonction pour afficher les drapeaux (générée par IA)
        */
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
        /*
        Fonction qui permet de mettre à jour la liste des sélections en haut de la page
        (générée par IA puis modifier par la suite).
        */
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


        
        function selectCountry(flagElement, country) {
            /*
            Fonction pour gérer la sélection/désélection d'un pays et accorde la limite pour l'utilisateur.

            */
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
            /*
            Fonction qui permet l'envoi de la requête au fichier python contenu dans railway
            */
            if (selectedCountries.length < 3) {
                alert("Veuillez sélectionner 3 pays avant d'envoyer.");
                return;
            }
            // Création de l'objet JSON
            const data = {
                selectedCountries: selectedCountries
            };
            fetch("https://projet-gestion-de-projet-production.up.railway.app/receive_json", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"                
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
            console.log("Réponse du serveur :", data); // Réponse du serv
            document.getElementById("errorPopup").innerHTML = ""; // On réinitialise le message...
            const paysPredis = document.getElementById("paysPredis");
            paysPredis.innerHTML = ""; //On efface la liste précédente
            //Si on a bien les pays prédis : 
            if(data.status =="success"){
                var  messagePopup = "Voici les pays prédis :"

            //Ajouter les pays à la liste
            data.data.forEach(country => {
                fetch("ajoutsession.php",{
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ country: country })
                })
                .then(response => response.json())
                .then(data => {
                console.log(data.message);
                })
                const liste = document.createElement("li");
                liste.textContent = country; // Ajouter le pays à l'élément de la liste
                paysPredis.appendChild(liste);
            });
            }else{ // SInon erreur :
                var messagePopup = "Désolé, il y a eu une erreur."
                if(data.posCountry == "1"){
                    popup.querySelector("#errorPopup").textContent = "Veuillez changer le 1er pays.";
                }else if(data.posCountry == "2"){
                    popup.querySelector("#errorPopup").textContent = "Veuillez changer le 2e pays.";
                }else if(data.posCountry == "3"){
                    popup.querySelector("#errorPopup").textContent = "Veuillez changer le 3e pays.";
                }
            }
            popup.querySelector("#popupMessage").textContent = messagePopup;
            popup.classList.add("open-popup"); //On affiche la popup

            })
            console.log("JSON envoyé :", JSON.stringify(data));
        }

        function deselectCountry(countryName) {
            /*
            Fonction qui désélectionne,un pays depuis la liste
            */
            selectedCountries = selectedCountries.filter(c => c !== countryName);
            updateSelectedList();
            refreshFlagSelection();
        }

        
        function refreshFlagSelection() {
            // Fonction pour mettre à jour l'affichage des drapeaux après une désélection (générée par IA et modifier)
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

        // On ajoute un timer afin d'éviter de multiples requêtes, et surtout,
        // pour laisser le temps à la première requête de relancer l'hébergeur...
        let bouttonEnvoi = document.getElementById("sendButtonPays");
        let bouttonText = bouttonEnvoi.textContent;

        bouttonEnvoi.addEventListener("click", () =>{
            sendSelectedCountries();
            bouttonEnvoi.disabled = true;
            bouttonEnvoi.textContent = "Veuillez patienter...";

            setTimeout(() => {
                bouttonEnvoi.disabled = false;
                bouttonEnvoi.textContent = bouttonText;
            },7000);
        });

        

</script>

</body>
</html>
