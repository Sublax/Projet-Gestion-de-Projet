<?php include 'navbar.php' ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques Filtrés par Pays</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles/styles.css">
    <?php include 'navbar.php' ?>

</head>
<body>
    <h2>Présentation des données</h2>
    <p>Si vous souhaitez faire des graphes personnalisées : <a href= "./graph_max.php"> cliquez ici </a> </p>
    <label for="countrySelect">Sélectionner un pays :</label>
    <select id="countrySelect"></select>

    <div class="chart-container">
    <canvas id="barChart" height="500" width="400"></canvas>
    <canvas id="lineChart" height="500" width="400"></canvas>
    <canvas id="pieChart" height="500" width="400"></canvas>
    </div>
    
    <div class="chart-container">
    <canvas id="radarChart" height="750" width="550"></canvas>
    <canvas id="polarChart" height="750" width="550"></canvas>
    </div>

    <div class="chart-container">
    <canvas id="lineChart2" height="500" width="400"></canvas>
    <canvas id="doughnutChart" height="500" width="400"></canvas>
    <canvas id="doughnutChart2" height="500" width="400"></canvas>

    </div>

    <script>
        let barChart, lineChart, pieChart, radarChart, polarChart,lineChart2,doughnutChart,doughnutChart2;

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
                    /*
                    if(data.barChart.length === 0){
                        document.querySelector("#barChart").replaceWith(createErrorMessage("Aucune donnée disponible pour ce pays."));
                    }
                    else if(data.lineChart.length === 0 ){
                        document.querySelector("#lineChart").innerHTML = "<p style='color: red; text-align: center;'>Aucune donnée disponible pour ce pays.</p>";
                    }
                    else if(data.pieChart.length === 0){
                        document.querySelector("#pieChart").replaceWith(createErrorMessage("Aucune donnée disponible pour ce pays."));
                    }*/
                    updateCharts(data);
                })
                .catch(error => console.error("Erreur de chargement des données :", error));
        }

        function updateCharts(data) {
            // Graphique en Barres
            if (barChart) barChart.destroy();
            const barCtx = document.getElementById('barChart').getContext('2d');
            barChart = new Chart(barCtx, 
            {
                type: 'bar',
                data: {
                    labels: data.barChart.map(item => item.annee),
                    datasets: [
                    {
                        type: "bar",
                        label: 'Évolution du score de bonheur',
                        data: data.barChart.map(item => item.score_bonheur),
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        type: 'line',
                        label: 'Score de la générosité',
                        data: data.barChart.map(item => item.generosite * 10),
                        borderColor: 'red',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4 // Pour une courbe plus lisse
                    }
                ]
                },
                
                options: { 
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Score du bonheur enregistrée par année',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    }
                }
            });

            // Graphique en Ligne
            if (lineChart) lineChart.destroy();
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: data.lineChart.map(item => item.annee),
                    datasets: [{
                        label: `${document.getElementById('countrySelect').value} - Morts`,
                        data: data.lineChart.map(item => item.mort),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: `${document.getElementById('countrySelect').value} - Naissances`,
                        data: data.lineChart.map(item => item.naissance),
                        borderColor: 'rgba(54, 162, 235, 1)', 
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Nombre de naissance/mort par année',
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
                            text: 'Part de la religion dans le pays',
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
        

        if (radarChart) radarChart.destroy();
            const radarCtx = document.getElementById('radarChart').getContext('2d');
            radarChart = new Chart(radarCtx, {
                type: 'radar',
                data: {
                    labels: data.radarChart.map(item => item.annee),
                    datasets: [
                        {
                        label: 'Temperature été',
                        data: data.radarChart.map(item => item.ete_tavg),
                        backgroundColor: 'rgba(255, 165, 0, 0.2)',
                        borderColor: 'rgba(255, 165, 0, 1)',
                        borderWidth: 1
                        },
                        {
                        label: 'Temperature printemps',
                        data: data.radarChart.map(item => item.printemps_tavg),
                        backgroundColor: 'rgba(60, 179, 113, 0.2)',
                        borderColor: 'rgba(60, 179, 113, 1)',
                        borderWidth: 1
                        },
                        {
                        label: 'Temperature automne',
                        data: data.radarChart.map(item => item.automne_tavg),
                        backgroundColor: 'rgba(139, 69, 19, 0.2)',
                        borderColor: 'rgba(139, 69, 19, 1)',
                        borderWidth: 1
                        },
                        {
                        label: 'Temperature hiver',
                        data: data.radarChart.map(item => item.hiver_tavg),
                        backgroundColor: 'rgba(0, 191, 255, 0.5)',
                        borderColor: 'rgba(0, 191, 255, 1)',
                        borderWidth: 1
                        }
                    ]
                },
                options: { 
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Température moyenne par saison',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    },
                    scales: {
                        r: {
                            pointLabels: {
                                font: { size: 15 },
                                color: '#000'
                            },
                            suggestedMin: -10,
                            suggestedMax: 35.
                        }
                    }
                }
            });

            if (polarChart) polarChart.destroy();
            const polarCtx = document.getElementById('polarChart').getContext('2d');
            const dataSaison = {
                ete: [],
                printemps: [],
                automne: [],
                hiver: []
            };
            // Regrouper les températures par saison
            data.polarChart.forEach(item => {
                dataSaison.ete.push(item.ete_tavg);
                dataSaison.printemps.push(item.printemps_tavg);
                dataSaison.automne.push(item.automne_tavg);
                dataSaison.hiver.push(item.hiver_tavg);
            });
            
            polarChart = new Chart(polarCtx, {
                type: 'polarArea',
                data: {
                    //labels: ["Été","Printemps","Automne","Hiver"],
                    datasets: [
                        {
                        label: '2018',
                        data: [dataSaison.ete[0], dataSaison.printemps[0], dataSaison.automne[0], dataSaison.hiver[0]],
                        backgroundColor: 'rgba(255, 165, 0, 0.2)',
                        borderColor: 'rgba(255, 165, 0, 1)',
                        borderWidth: 1
                        },
                        {
                        label: '2019',
                        data: [dataSaison.ete[1], dataSaison.printemps[1], dataSaison.automne[1], dataSaison.hiver[1]],
                        backgroundColor: 'rgba(60, 179, 113, 0.2)',
                        borderColor: 'rgba(60, 179, 113, 1)',
                        borderWidth: 1
                        },
                        {
                        label: '2020',
                        data: [dataSaison.ete[2], dataSaison.printemps[2], dataSaison.automne[2], dataSaison.hiver[2]],
                        backgroundColor: 'rgba(139, 69, 19, 0.2)',
                        borderColor: 'rgba(139, 69, 19, 1)',
                        borderWidth: 1
                        },
                        {
                        label: '2021',
                        data: [dataSaison.ete[3], dataSaison.printemps[3], dataSaison.automne[3], dataSaison.hiver[3]],
                        backgroundColor: 'rgba(0, 191, 255, 0.2)',
                        borderColor: 'rgba(0, 191, 255, 1)',
                        borderWidth: 1
                        },
                        {
                        label: '2022',
                        data: [dataSaison.ete[4], dataSaison.printemps[4], dataSaison.automne[4], dataSaison.hiver[4]],
                        backgroundColor: 'rgba(0, 191, 255, 0.2)',
                        borderColor: 'rgba(0, 191, 255, 1)',
                        borderWidth: 1
                        }
                    ]
                },
                options: { 
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Écart de température entre années et saisons',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    },
                    scales: {
                        r: {
                            pointLabels: {
                                font: { size: 15 },
                                color: '#000'
                            },
                            suggestedMin: -10,
                            suggestedMax: 35.
                        }
                    }
                }
            });

            if (lineChart2) lineChart2.destroy();
            const line2Ctx = document.getElementById('lineChart2').getContext('2d');
            lineChart2 = new Chart(line2Ctx, {
                type: 'line',
                data: {
                    labels: data.lineChart2.map(item => item.annee),
                    datasets: [{
                        label: `${document.getElementById('countrySelect').value} - Femmes`,
                        data: data.lineChart2.map(item => item.sans_emploi_femme),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: `${document.getElementById('countrySelect').value} - Hommes`,
                        data: data.lineChart2.map(item => item.sans_emploi_homme),
                        borderColor: 'rgba(54, 162, 235, 1)', 
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Évolution du taux de travail',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    }
                }
            });

            if (doughnutChart) doughnutChart.destroy();
            const doughCtx = document.getElementById('doughnutChart').getContext('2d');
            doughnutChart = new Chart(doughCtx, {
                type: 'doughnut',
                data: {
                    labels: ["Acces (en %)","Non accès (en %)"],
                    datasets: [
                        {
                        data: data.doughnutChart.flatMap(item=>[item.taux_acces_transport,100-item.taux_acces_transport]),                    
                        backgroundColor: ['#D6955B', '#e5e7e6'],
                        borderColor: 'rgba(255, 255, 255, 0.5)',
                        borderWidth: 1
                        }
                ]
                },
                options: {
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Accès aux transports en commun selon les années',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    }
                }
            });

            if (doughnutChart2) doughnutChart2.destroy();
            const doughCtx2 = document.getElementById('doughnutChart2').getContext('2d');
            doughnutChart2 = new Chart(doughCtx2, {
                type: 'doughnut',
                data: {
                    labels: ['Corruption politique', 'Vanité politique', 'Taux de criminalité', 'Reste criminalité'], 
                    datasets: [
                        {
                        label: "Corruption politique (en %)",
                        data: data.doughnutChart2.flatMap(item=>[item.corruption_politique,100-item.corruption_politique]),            
                        backgroundColor: ['#A7001E', '#7AA95C'],
                        borderColor: 'rgba(255, 255, 255, 0.5)',
                        borderWidth: 1
                        },
                        {
                        label: "Taux de criminalité (en %)",
                        data: data.doughnutChart2.flatMap(item=>[item.taux_crime,100-item.taux_crime]),                    
                        backgroundColor: ['#1E0F1C', '#7AA95C'],
                        borderColor: 'rgba(255, 255, 255, 0.5)',
                        borderWidth: 1
                        },

                ]
                },
                options: {
                    responsive: false,
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Taux de corruption et de crimininalisation en une année',
                            font: {
                                size: 18 
                            },
                            color: '#333' 
                        }
                    }
                }
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
    margin-bottom: 50px;
    margin-top: 20px;
    }
    h2{
        margin-top: 50px;
    }
    body{
        margin-left: 20px;
    }
</style>