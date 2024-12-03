<?php
require "bd.php";
$bdd = getBD();
require_once 'nom_pays.php'; // Inclure nom_pays.php directement

// Fonction pour obtenir les colonnes d'une table
function getTableColumns($tableName) {
    try {
        $bdd = getBD();
        $query = $bdd->query("SHOW COLUMNS FROM $tableName");
        return $query->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}

// Fonction pour obtenir toutes les tables de la base de données
function getAllTables() {
    try {
        $bdd = getBD();
        $query = $bdd->query("SHOW TABLES");
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);

        // Exclure les tables 'avis' et 'clients'
        return array_filter($tables, function ($table) {
            return !in_array($table, ['avis', 'clients', 'messages_contact', 'info_clients','pays']);
        });
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}

// Fonction pour obtenir les années uniques d'une table
function getUniqueYears($tableName, $yearColumn = 'annee') {
    try {
        $bdd = getBD();
        $query = $bdd->query("SELECT DISTINCT $yearColumn FROM $tableName ORDER BY $yearColumn");
        return $query->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}

// Fonction pour obtenir les pays filtrés par année
function getCountriesByYear($tableName, $selectedYear, $yearColumn = 'annee') {
    $data = getTableData($tableName);
    return array_unique(
        array_column(
            array_filter($data, fn($row) => $row[$yearColumn] == $selectedYear),
            'nom_pays'
        )
    );
}

// Variables initiales
$tableName = null;
$columns = [];
$years = [];
$countries = [];
$selectedYear = null;

// Vérifier si une table a été sélectionnée
if (isset($_GET['table']) && $_GET['table'] !== '') {
    $tableName = $_GET['table'];
    $columns = getTableColumns($tableName);
    $yearColumn = in_array('Year', $columns) ? 'Year' : 'annee';
    $years = getUniqueYears($tableName, $yearColumn);

    if (isset($_GET['year']) && $_GET['year'] !== '') {
        $selectedYear = $_GET['year'];
        $countries = getCountriesByYear($tableName, $selectedYear, $yearColumn);
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique Personnalisable</title>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>

<body>
    <!-- Barre de menu -->
    <header>
        <div class="menu-bar">
            <div class="menu-item">
                <?php if (isset($_SESSION['client'])): ?>
                    <a href="../dataviz/questionnaire.php">
                <?php else: ?>
                    <a href="../connexion/login.php">
                <?php endif; ?>
                <img src="images/images_ced/icone1.png" alt="Icone Questionnaire">
                </a>
                <p>Questionnaire</p>
            </div>
            <div class="menu-item">
                <a href="../graph.php"><img src="images/images_ced/icone2.png" alt="Icone Statistiques & Graphs"></a>
                <p>Statistiques & Graphs</p>
            </div>
            <div class="menu-item">
                <a href="../forum/forum.php"><img src="images/images_ced/icone7.png" alt="Forum"></a>
                <p>Forum</p>
            </div>
            <div class="menu-item logo">
                <a href="../index.php"><img src="images/images_ced/logo.png" alt="Logo"></a>
            </div>
            <div class="menu-item">
                <a href="../informations/informations.php"><img src="images/images_ced/icone4.png" alt="Icone Informations"></a>
                <p>Informations</p>
            </div>
            <div class="menu-item">
                <a href="../informations/sources.php"><img src="images/images_ced/icone5.png" alt="Icone Sources données"></a>
                <p>Sources données</p>
            </div>
            <div class="menu-item">
                <a href="profil.php"><img src="images/images_ced/icone6.png" alt="Icone Options"></a>
                <p>Profil</p>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main>
        <h1>Graphiques avec Filtres</h1>

        <!-- Formulaire pour sélectionner les pays -->
        <form id="filterForm">
            <label for="countryFilter">Filtrer par pays :</label>
            <select id="countryFilter" multiple>
                <?php
                // Obtenez les noms des pays et leurs IDs
                $sql = "SELECT id_pays, nom_pays FROM pays";
                $result = $bdd->query($sql);
                while ($row = $result->fetch()) {
                    echo "<option value='{$row['id_pays']}'>{$row['nom_pays']}</option>";
                }
                ?>
            </select>
            <button type="button" id="applyFilter">Appliquer</button>
        </form>

        <!-- Graphique principal -->
        <div class="graphique">
            <canvas id="myChart"></canvas>
        </div>

        <!-- Deuxième graphique -->
        <div class="graphique">
            <canvas id="secondChart"></canvas>
        </div>
    </main>

    <script>
        // Configuration pour le premier graphique
        const data = {
            labels: [],
            datasets: [{
                label: 'Clean Fuel and Cooking Equipment',
                data: [],
                borderWidth: 1,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)'
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );

        // Gestionnaire d'événement pour le filtre de pays
        document.getElementById('applyFilter').addEventListener('click', function() {
            const selectedCountries = Array.from(document.getElementById('countryFilter').selectedOptions)
                .map(option => option.value);

            fetch('./get_filtered_data.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ countries: selectedCountries })
            })
            .then(response => response.json())
            .then(data => {
                myChart.data.labels = data.labels; // Années
                myChart.data.datasets[0].data = data.values; // Moyennes
                myChart.update();
            })
            .catch(error => console.error('Erreur lors de la récupération des données:', error));
        });

        // Configuration pour le deuxième graphique
        const secondData = {
            labels: [],
            datasets: [{
                label: 'Education primaire',
                data: [],
                borderWidth: 1,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)'
            }]
        };

        const secondConfig = {
            type: 'line',
            data: secondData,
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };

        const secondChart = new Chart(
            document.getElementById('secondChart'),
            secondConfig
        );

        // Gestionnaire d'événement pour le deuxième graphique
        document.getElementById('applyFilter').addEventListener('click', function() {
            const selectedCountries = Array.from(document.getElementById('countryFilter').selectedOptions)
                .map(option => option.value);

            fetch('./get_second_graph.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ countries: selectedCountries })
            })
            .then(response => response.json())
            .then(data => {
                secondChart.data.labels = data.labels;
                secondChart.data.datasets[0].data = data.values;
                secondChart.update();
            })
            .catch(error => console.error('Erreur pour le second graphique:', error));
        });
    </script>










<section class="graph-section">
    <h1>Graphique Personnalisable</h1>
    <form id="selectionForm">
        <label for="tableSelect">Sélectionnez une table :</label>
        <select id="tableSelect" name="table">
            <option value="">-- Sélectionnez une table --</option>
            <?php 
            $tables = getAllTables(); 
            foreach ($tables as $table): ?>
                <option value="<?= htmlspecialchars($table) ?>" <?= isset($tableName) && $tableName === $table ? 'selected' : '' ?>>
                    <?= htmlspecialchars($table) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if ($tableName): ?>
            <label for="yearSelect">Sélectionnez une année :</label>
            <select id="yearSelect" name="year">
                <option value="">-- Sélectionnez une année --</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?= htmlspecialchars($year) ?>" <?= isset($selectedYear) && $selectedYear == $year ? 'selected' : '' ?>>
                        <?= htmlspecialchars($year) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </form>

    <!-- El gráfico que se actualizará con AJAX -->
    <canvas id="customChart" width="800" height="400"></canvas>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {
    let chart = null;

    // Función para hacer la solicitud AJAX
    function fetchGraphData() {
        // Obtener los valores de los selects
        const selectedTable = document.getElementById('tableSelect').value;
        const selectedYear = document.getElementById('yearSelect').value;

        // Verificar si ambos valores están seleccionados
        if (!selectedTable || !selectedYear) {
            return; // Si no, no hacemos nada
        }

        // Crear un objeto con los parámetros que vamos a enviar
        const params = new URLSearchParams();
        params.append('table', selectedTable);
        params.append('year', selectedYear);

        // Usar fetch para hacer la solicitud AJAX
        fetch('get_third_graph_data.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: params.toString()
        })
        .then(response => response.json())  // Suponemos que la respuesta es JSON
        .then(data => {
            console.log('Datos recibidos:', data); // Mostrar los datos recibidos para ver su estructura

            // Si ya hay un gráfico, destruirlo antes de crear uno nuevo
            if (chart) chart.destroy(); 

            // Verificar si el canvas se encuentra correctamente
            const canvas = document.getElementById('customChart');
            console.log('Canvas:', canvas); // Verifica si el canvas es encontrado correctamente

            // Obtener el contexto del canvas
            const ctx = canvas.getContext('2d');

            // Verificar si los datos contienen las etiquetas y valores esperados
            if (!data.labels || !data.values) {
                console.error('Error: Los datos no contienen etiquetas o valores.');
                return;
            }

            // Crear un nuevo gráfico con los datos recibidos
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,  // Etiquetas de los países
                    datasets: [{
                        label: `${selectedTable} (${selectedYear})`,
                        data: data.values,  // Valores de los indicadores
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });

    }

    // Escuchar los cambios en los selects para actualizar el gráfico
    document.getElementById('tableSelect').addEventListener('change', fetchGraphData);
    document.getElementById('yearSelect').addEventListener('change', fetchGraphData);
});

</script>

</body>
</html>
