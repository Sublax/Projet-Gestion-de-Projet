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
    <form method="GET">
        <label for="tableSelect">Sélectionnez une table :</label>
        <select id="tableSelect" name="table" onchange="this.form.submit()">
            <option value="">-- Sélectionnez une table --</option>
            <?php 
            // Asegúrate de incluir nom_pays.php para usar getTableData
            require_once 'nom_pays.php';

            // Usamos getTableData() para obtener los países
            $tables = getAllTables(); // Obtener las tablas disponibles
            foreach ($tables as $table): ?>
                <option value="<?= htmlspecialchars($table) ?>" <?= isset($tableName) && $tableName === $table ? 'selected' : '' ?>>
                    <?= htmlspecialchars($table) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($tableName): ?>
        <h2>Table Sélectionnée : <?= htmlspecialchars($tableName) ?></h2>

        <?php if (!empty($years)): ?>
            <form method="GET">
                <input type="hidden" name="table" value="<?= htmlspecialchars($tableName) ?>">
                <label for="yearSelect">Sélectionnez une année :</label>
                <select id="yearSelect" name="year" onchange="this.form.submit()">
                    <option value="">-- Sélectionnez une année --</option>
                    <?php foreach ($years as $year): ?>
                        <option value="<?= htmlspecialchars($year) ?>" <?= isset($selectedYear) && $selectedYear == $year ? 'selected' : '' ?>>
                            <?= htmlspecialchars($year) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>

        <?php if ($selectedYear): ?>
            <div class="checkbox-group">
                <h3>Pays Disponibles pour l'Année : <?= htmlspecialchars($selectedYear) ?></h3>
                <?php
                // Obtener los países usando getTableData de nom_pays.php
                $data = getTableData($tableName);
                $countries = array_unique(array_column($data, 'nom_pays')); // Obtener los países únicos
                foreach ($countries as $country): ?>
                    <label>
                        <input type="checkbox" name="countries[]" value="<?= htmlspecialchars($country) ?>" class="country-checkbox">
                        <?= htmlspecialchars($country) ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <p class="selected-countries">Pays sélectionnés : <span id="selectedCountries">Aucun</span></p>

            <h3>Indicateurs Disponibles</h3>
            <form id="chartForm">
                <label for="columnSelect">Sélectionnez un indicateur :</label>
                <select id="columnSelect" name="column">
                    <?php foreach ($columns as $column): ?>
                        <?php if (!preg_match('/^id_/', $column) && $column != 'annee' && $column != 'nom_pays'): ?>
                            <option value="<?= htmlspecialchars($column) ?>"><?= htmlspecialchars($column) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="generateChart">Générer le Graphique</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <canvas id="customChart" width="800" height="400"></canvas>
</section>

<script>
    let chart = null;

    // Actualizar la lista de países seleccionados
    document.querySelectorAll('.country-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedCountries = Array.from(document.querySelectorAll('.country-checkbox:checked'))
                .map(input => input.value);
            document.getElementById('selectedCountries').textContent = selectedCountries.length > 0
                ? selectedCountries.join(', ')
                : 'Aucun';
        });
    });

    // Generar el gráfico personalizado
    document.getElementById('generateChart').addEventListener('click', () => {
        const selectedColumn = document.getElementById('columnSelect').value;

        // Obtener los países seleccionados
        const selectedCountries = Array.from(document.querySelectorAll('.country-checkbox:checked'))
            .map(input => input.value);

        // Filtrar los datos según los países y la columna seleccionada
        const filteredData = <?= json_encode(getTableData($tableName)); ?>.filter(row =>
            selectedCountries.includes(row.nom_pays) && row.<?= htmlspecialchars($yearColumn); ?> == <?= json_encode($selectedYear); ?>
        );

        // Extraer las etiquetas (nombres de los países) y los valores (indicadores seleccionados)
        const labels = filteredData.map(row => row.nom_pays);
        const values = filteredData.map(row => parseFloat(row[selectedColumn]) || 0);

        // Generar el gráfico
        if (chart) chart.destroy(); // Eliminar el gráfico anterior
        const ctx = document.getElementById('customChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: `${selectedColumn} (${<?= json_encode($selectedYear); ?>})`,
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    });
</script>

    
</body>
</html>
