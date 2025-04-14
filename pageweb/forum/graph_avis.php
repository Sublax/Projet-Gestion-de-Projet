<?php
include '../../navbar.php';
include '../../bd.php';
$bdd = getBD();

if (!isset($_GET['id_pays'])) {
    die("Erreur d'ID");
}

$id_pays = (int)$_GET['id_pays'];

// Récupérer tous les aspects distincts pour initialisation
$stmt_aspects = $bdd->prepare("SELECT DISTINCT aspect FROM avis WHERE id_pays = :id_pays");
$stmt_aspects->execute([':id_pays' => $id_pays]);
$aspect_list = $stmt_aspects->fetchAll(PDO::FETCH_COLUMN);

$aspects = [];
$total_positif = 0;
$total_negatif = 0;

foreach ($aspect_list as $asp) {
    $asp_clean = strtolower(trim($asp ?? ''));
    $aspects[$asp_clean] = ['positif' => 0, 'négatif' => 0];
}

// Récupérer les compteurs groupés
$stmt = $bdd->prepare("
    SELECT aspect, sentiment, COUNT(*) AS count
    FROM avis
    WHERE id_pays = :id_pays
    GROUP BY aspect, sentiment
");
$stmt->execute([':id_pays' => $id_pays]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach ($data as $row) {
    $asp = strtolower(trim($row['aspect'] ?? ''));
    $sent = strtolower(trim($row['sentiment'] ?? ''));
    $sent = str_replace(['é','è','ê','ë'], 'e', $sent); 
    $sent = str_replace(["\n", "\r", "\t", "\0", "\x0B"], '', $sent); 
    $count = (int)$row['count'];

    if (!isset($aspects[$asp])) {
        $aspects[$asp] = ['positif' => 0, 'negatif' => 0];
    }

    if (in_array($sent, ['positif', 'negatif'])) {
        $aspects[$asp][$sent] += $count;
        if ($sent === 'positif') $total_positif += $count;
        if ($sent === 'negatif') $total_negatif += $count;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Répartition des avis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>

<h2 class='titre_graph'>Répartition des avis par aspect</h2>

<p class="intro-texte">
    Cette page montre la répartition des avis des utilisateurs classés par aspect. 
    Chaque graphique représente la part d’avis positifs et négatifs, 
    accompagnée du nombre total d’avis pour une meilleure interprétation.
</p>

<!-- Graphique global -->
<?php if ($total_positif + $total_negatif > 0): ?>
<div class="chart-card" style="max-width: 500px; margin: 0 auto;">
    <h3>Vue globale des sentiments</h3>
    <canvas id="chart_global"></canvas>
</div>
<script>
    const ctxGlobal = document.getElementById('chart_global').getContext('2d');
    const dataGlobal = [<?php echo $total_positif; ?>, <?php echo $total_negatif; ?>];
    const totalGlobal = dataGlobal.reduce((a, b) => a + b, 0);

    new Chart(ctxGlobal, {
        type: 'pie',
        data: {
            labels: ['Positif', 'Négatif'],
            datasets: [{
                data: dataGlobal,
                backgroundColor: ['#4CAF50', '#F44336']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                datalabels: {
                    formatter: (value) => {
                        const percentage = ((value / totalGlobal) * 100).toFixed(1);
                        return percentage + '%\n(' + value + ' avis)';
                    },
                    color: '#fff',
                    font: { weight: 'bold' }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
<?php endif; ?>

<!--  Camemberts par aspect -->
<div class="chart-grid">
<?php foreach ($aspects as $aspect => $sentiments): ?>
    <?php
        $total = ($sentiments['positif'] ?? 0) + ($sentiments['negatif'] ?? 0);
        if ($total === 0) continue;
    ?>
    <div class="chart-card">
        <h3><?php echo ucfirst(htmlspecialchars($aspect)); ?></h3>
        <canvas id="chart_<?php echo md5($aspect); ?>"></canvas>
    </div>
    <script>
        (function() {
            const labels = ['Positif', 'Négatif'];
            const data = [<?php echo $sentiments['positif']; ?>, <?php echo $sentiments['negatif']; ?>];
            const total = data.reduce((a, b) => a + b, 0);

            const ctx = document.getElementById('chart_<?php echo md5($aspect); ?>').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#4CAF50', '#F44336']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        datalabels: {
                            formatter: (value) => {
                                const percentage = ((value / total) * 100).toFixed(1);
                                return percentage + '%\n(' + value + ' avis)';
                            },
                            color: '#fff',
                            font: { weight: 'bold' }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        })();
    </script>
<?php endforeach; ?>
</div>

</body>
</html>
