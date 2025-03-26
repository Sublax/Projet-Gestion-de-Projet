<?php
session_start();
include "../bd.php";
$bdd = getBD();

if (isset($_GET['id_pays'])) {
    $id_pays = (int)$_GET['id_pays'];
    $stmt = $bdd->prepare("SELECT sentiment, COUNT(*) AS count FROM avis WHERE id_pays = :id_pays AND (sentiment = 'positif' OR sentiment = 'negatif') GROUP BY sentiment");
    $stmt->execute([':id_pays' => $id_pays]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Erreur d'ID");
}

$labels = [];
$counts = [];
foreach ($data as $row) {
    $labels[] = ucfirst($row['sentiment']);
    $counts[] = $row['count'];
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
<header>
    <div class="menu-bar">
    <div class="menu-item">
    <?php
        if (isset($_SESSION['client'])) {
            echo '<a href="../questionnaire.php">';
        } else {
            echo '<a href="../connexion/login.php">';
        }
        ?>
        <img src="../images/images_ced/icone1.png" alt="Icone Questionnaire">
        </a>
        <p>Questionnaire</p>
    </div>
    <div class="menu-item">
    <a href="graph.php"><img src="../images/images_ced/icone2.png" alt="Icone Statistiques & Graphs"></a>
        <p>Statistiques & Graphs</p>
    </div>
    <div class="menu-item">
    <a href="forum.php"><img src="../images/images_ced/icone7.png" alt="Forum"></a>
       <p>Forum</p>
   </div>
    <div class="menu-item logo">
    <a href="../index.php"><img src="../images/images_ced/logo.png" alt="Logo"></a>
        
    </div>
    <div class="menu-item">
    <a href="../informations/informations.php"><img src="../images/images_ced/icone4.png" alt="Icone Informations"></a>
        <p>Informations</p>
    </div>
    <div class="menu-item">
    <a href="../informations/sources.php"><img src="../images/images_ced/icone5.png" alt="Icone Sources données"></a>
        <p>Sources données</p>
    </div>
    <div class="menu-item">
    <a href="../profil.php"><img src="../images/images_ced/icone6.png" alt="Icone Options"></a>
        <p>Profil</p>
    </div>
    </header>
    <h2 class='titre_graph'>Répartition des avis</h2>
    <div class="chart-container">
        <canvas id="pieChart"></canvas>
    </div>

    <script>
        const labels = <?php echo json_encode($labels); ?>;
        const data = <?php echo json_encode($counts); ?>;
        const total = data.reduce((acc, val) => acc + val, 0);

        const ctx = document.getElementById('pieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Répartition des avis',
                    data: data,
                    backgroundColor: ['#4CAF50', '#F44336'], // Vert pour positif, rouge pour négatif
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        formatter: (value) => {
                            const percentage = ((value / total) * 100).toFixed(1);
                            return percentage + '%';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
</body>
</html>
