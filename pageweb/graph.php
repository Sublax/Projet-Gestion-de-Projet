<?php include 'navbar.php' ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques Filtrés par Pays</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles/styles.css">

    
</head>
<body>
    <h2>Démo de graphiques</h2>
    <label for="countrySelect">Sélectionner un pays :</label>
    <select id="countrySelect"></select>

    <div class="chart-container">
    <canvas id="barChart" height="300" width="300"></canvas>
    <canvas id="lineChart" height="300" width="300"></canvas>
    <canvas id="pieChart" height="300" width="300"></canvas>
    </div>
    <div class="chart-container">
    <canvas id="barChart" height="300" width="300"></canvas>
    <canvas id="lineChart" height="300" width="300"></canvas>
    <canvas id="pieChart" height="300" width="300"></canvas>
    </div>

    <script>
        let barChart, lineChart, pieChart;

        function fetchCountries() {
            fetch('data_graph.php')
                .then(response => response.json())
                .then(data => {
                    const countrySelect = document.getElementById('countrySelect');
                    
                    // Remplir la liste des pays
                    countrySelect.innerHTML = data.countries.map(country =>
                        `<option value="${country.nom_pays}">${country.nom_pays}</option>`
                    ).join('');

                    // Charger les graphiques pour le premier pays par défaut
                    fetchData(countrySelect.value);
                })
                .catch(error => console.error("Erreur de chargement des pays :", error));
        }

        function fetchData(country) {
            fetch(`data_graph.php?pays=${country}`)
                .then(response => response.json())
                .then(data => {
                    updateCharts(data);
                })
                .catch(error => console.error("Erreur de chargement des données :", error));
        }

        function updateCharts(data) {
            // Graphique en Barres
            if (barChart) barChart.destroy();
            const barCtx = document.getElementById('barChart').getContext('2d');
            barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: data.barChart.map(item => item.annee),
                    datasets: [{
                        label: 'Score du bonheur',
                        data: data.barChart.map(item => item.score_bonheur),
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: { 
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Score du bonheur enregistrée par années',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    }
                }
            });

            // Graphique en Ligne (ventes par pays)
            if (lineChart) lineChart.destroy();
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: data.lineChart.map(item => item.annee),
                    datasets: [{
                        label: `${document.getElementById('countrySelect').value}`,
                        data: data.lineChart.map(item => item.mort),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Nombre de morts par an',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    }
                }
            });

            // Graphique en Camembert
            if (pieChart) pieChart.destroy();
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const labels = data.pieChart.flatMap(item => [item.nom_pays + " - Important", item.nom_pays + " - Pas important"]);
            const values = data.pieChart.flatMap(item => [item.important, item.pas_important]);

            pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Comparaison Important / Pas Important",
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ]
                    },
                ]
                },
                options: {
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Graphique en camembert',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        },
                        legend: {
                            display: true,
                            position: 'right',
                            labels: {
                                font: { size: 14 },
                                color: '#000',
                                padding: 15,
                                boxWidth: 20
                            }
                    }}}
            });
        }

        // Charger la liste des pays et les données du premier pays
        fetchCountries();

        // Mettre à jour les données quand l'utilisateur change de pays
        document.getElementById('countrySelect').addEventListener('change', function () {
            fetchData(this.value);
        });
    </script>
</body>
</html>

<style>
    .chart-container {
    display: flex;
    justify-content: center; /* Centre les graphiques */
    gap: 200px;
    flex-wrap: wrap;
}
</style>