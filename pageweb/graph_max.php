<?php
require_once 'bd.php';
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
            return !in_array($table, ['avis', 'clients','messages_contact','info_clients']);
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
    $data = getTableData($tableName); // Données avec `id_pays` transformé en `nom_pays`
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
    $tableName = $_GET['table']; // Table sélectionnée par l'utilisateur
    $columns = getTableColumns($tableName);
    $yearColumn = in_array('Year', $columns) ? 'Year' : 'annee';  // Sélectionner la colonne d'année
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .checkbox-group {
            overflow-y: auto;
            max-height: 300px;
            width: 500px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }
        .checkbox-group label {
            display: block;
            cursor: pointer;
        }
        .selected-countries {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Graphique Personnalisable</h1>

    <!-- Formulaire pour sélectionner une table -->
    <form method="GET">
        <label for="tableSelect">Sélectionnez une table :</label>
        <select id="tableSelect" name="table" onchange="this.form.submit()">
            <option value="">-- Sélectionnez une table --</option>
            <?php foreach (getAllTables() as $table): ?>
                <option value="<?= htmlspecialchars($table) ?>" <?= isset($tableName) && $tableName === $table ? 'selected' : '' ?>>
                    <?= htmlspecialchars($table) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($tableName): ?>
        <h2>Table Sélectionnée : <?= htmlspecialchars($tableName) ?></h2>

        <!-- Afficher le formulaire de filtres uniquement si des années sont disponibles -->
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
        <?php else: ?>
            <p>Aucune année trouvée dans la table sélectionnée.</p>
        <?php endif; ?>

        <?php if ($selectedYear): ?>
            <h3>Pays Disponibles pour l'Année : <?= htmlspecialchars($selectedYear) ?></h3>
            <div class="checkbox-group">
                <?php foreach ($countries as $country): ?>
                    <label>
                        <input type="checkbox" name="countries[]" value="<?= htmlspecialchars($country) ?>">
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
                        <?php 
                        // Filtrar columnas que no son identificadores
                        if (!preg_match('/^id_/i', $column) && !in_array($column, ['id_pays', 'nom_pays', 'annee', 'Year'])): ?>
                            <option value="<?= htmlspecialchars($column) ?>"><?= htmlspecialchars($column) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="generateChart">Générer le Graphique</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Conteneur du graphique -->
    <canvas id="myChart" width="800" height="400"></canvas>

    <script>
        let chart = null;

        // Mettre à jour la liste des pays sélectionnés
        document.querySelectorAll('.checkbox-group input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selectedCountries = Array.from(document.querySelectorAll('.checkbox-group input[type="checkbox"]:checked'))
                    .map(input => input.value);
                document.getElementById('selectedCountries').textContent = selectedCountries.length > 0
                    ? selectedCountries.join(', ')
                    : 'Aucun';
            });
        });

        document.getElementById('generateChart').addEventListener('click', () => {
            const selectedColumn = document.getElementById('columnSelect').value;

            const selectedCountries = Array.from(document.querySelectorAll('.checkbox-group input[type="checkbox"]:checked'))
                .map(input => input.value);

            const filteredData = <?= json_encode(getTableData($tableName)); ?>.filter(row =>
                selectedCountries.includes(row.nom_pays) && row.<?= htmlspecialchars($yearColumn); ?> == <?= json_encode($selectedYear); ?>
            );

            const labels = filteredData.map(row => row.nom_pays);
            const values = filteredData.map(row => parseFloat(row[selectedColumn]) || 0);

            if (chart) chart.destroy(); // Nettoyer l'ancien graphique
            const ctx = document.getElementById('myChart').getContext('2d');
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
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
