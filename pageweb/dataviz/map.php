<!DOCTYPE html>
<html>
<head>
<title>Carte dans un encadré</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .text-section {
            padding: 20px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ccc;
        }
        .text-section h1 {
            margin-top: 0;
        }
        .map-section {
            height: 600px; /* Hauteur de la carte */
        }
        #map {
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Texte en haut -->
        <div class="text-section">
            <h1>Voici vos résultats :</h1>
            <p>
                Ces résultats proviennent de vos réponses au questionnaire, et d'un calcul de correspondance.
            </p>
        </div>
        <div class="map-section">
        <div id="map"></div>
    <script>
        // Initialisation de la carte
        const map = L.map('map').setView([20, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Données associées aux pays (exemple)
        const countryData = {
            "France": { value: 10, color: "blue" },
            "Germany": { value: 20, color: "green" },
            "Spain": { value: 15, color: "orange" },
            "Italy": { value: 5, color: "red" }
        };

        // Charger les données géographiques des pays (exemple avec un fichier GeoJSON)
        fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style: function(feature) {
                        const countryName = feature.properties.name;
                        const countryInfo = countryData[countryName];
                        return {
                            fillColor: countryInfo ? countryInfo.color : "gray", // Couleur du pays
                            fillOpacity: 0.7,
                            color: "white", // Bordure des pays
                            weight: 1
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const countryName = feature.properties.name;
                        const countryInfo = countryData[countryName];
                        // Redirection au clic
                        if (countryInfo) {
                            layer.on('click', function() {
                                // Envoyer une requête AJAX au script PHP pour créer des graphiques :
                                fetch('script_graphs.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({country: countryName }) // Envoie le nom des pays
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Réponse du serveur:', data);
                                    window.location.href = `country.php?country=${feature.properties.name}`;
                                })
                                .catch(error => {
                                    console.error('Erreur:', error);
                                });
                            });
                        }


                        // Infobulle au survol
                        layer.on('mouseover', function() {
                            layer.bindTooltip(
                                countryInfo ? `${countryName}: ${countryInfo.value}` : `${countryName}: Données non disponibles`,
                                { permanent: false, sticky: true }
                            ).openTooltip();
                        });

                        // Retirer l'infobulle quand la souris quitte le pays
                        layer.on('mouseout', function() {
                            layer.closeTooltip();
                        });
                    }
                }).addTo(map);
            });
    </script>
    </div>
</body>
</html>