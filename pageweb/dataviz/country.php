<?php
$country = $_GET['country'] ?? null;

if (!$country) {
    echo "Country not specified.";
    exit;
}

// Sanitize the country input to prevent directory traversal or XSS attacks
$country_safe = htmlspecialchars(basename($country));

// Path to the graph directory
$graph_dir = "static/graphs/";

// Define sections and find matching graphs
$sections = [
    "agroalimentaire" => [
        "barplots" => glob($graph_dir . "agroalimentaire_barplot_*.html"),
        "boxplots" => glob($graph_dir . "agroalimentaire_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "agroalimentaire_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "agroalimentaire_piechart_*.html"),
    ],
    "bonheur" => [
        "barplots" => glob($graph_dir . "bonheur_barplot_*.html"),
        "boxplots" => glob($graph_dir . "bonheur_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "bonheur_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "bonheur_piechart_*.html"),
    ],
    "corruption" => [
        "barplots" => glob($graph_dir . "corruption_barplot_*.html"),
        "boxplots" => glob($graph_dir . "corruption_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "corruption_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "corruption_piechart_*.html"),
    ],
    "crime" => [
        "barplots" => glob($graph_dir . "crime_barplot_*.html"),
        "boxplots" => glob($graph_dir . "crime_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "crime_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "crime_piechart_*.html"),
    ],
    "economie" => [
        "barplots" => glob($graph_dir . "economie_barplot_*.html"),
        "boxplots" => glob($graph_dir . "economie_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "economie_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "economie_piechart_*.html"),
    ],
    "education" => [
        "barplots" => glob($graph_dir . "education_barplot_*.html"),
        "boxplots" => glob($graph_dir . "education_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "education_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "education_piechart_*.html"),
    ],
    "meteo" => [
        "barplots" => glob($graph_dir . "meteo_barplot_*.html"),
        "boxplots" => glob($graph_dir . "meteo_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "meteo_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "meteo_piechart_*.html"),
    ],
    "religion" => [
        "barplots" => glob($graph_dir . "religion_barplot_*.html"),
        "boxplots" => glob($graph_dir . "religion_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "religion_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "religion_piechart_*.html"),
    ],
    "sante" => [
        "barplots" => glob($graph_dir . "sante_barplot_*.html"),
        "boxplots" => glob($graph_dir . "sante_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "sante_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "sante_piechart_*.html"),
    ],
    "social" => [
        "barplots" => glob($graph_dir . "social_barplot_*.html"),
        "boxplots" => glob($graph_dir . "social_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "social_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "social_piechart_*.html"),
    ],
    "tourisme" => [
        "barplots" => glob($graph_dir . "tourisme_barplot_*.html"),
        "boxplots" => glob($graph_dir . "tourisme_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "tourisme_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "tourisme_piechart_*.html"),
    ],
    "transport" => [
        "barplots" => glob($graph_dir . "transport_barplot_*.html"),
        "boxplots" => glob($graph_dir . "transport_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "transport_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "transport_piechart_*.html"),
    ],
    "travail" => [
        "barplots" => glob($graph_dir . "travail_barplot_*.html"),
        "boxplots" => glob($graph_dir . "travail_boxplot_*.html"),
        "lineplots" => glob($graph_dir . "travail_lineplot_*.html"),
        "piecharts" => glob($graph_dir . "travail_piechart_*.html"),
    ],
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques pour : <?php echo $country_safe; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .logo_site {
        position: absolute;
        width: 80px; 
        height: 80px; 
        z-index: 100;
        }
        .logo_site img {
        width: 80px; /* Taille réelle de l'image*/
        height: 80px;
        object-fit: contain;
        }
        .titre_page{
            text-align: center;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            position: relative;
        }
        nav {
            background-color: #34495e;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        section {
            display: none;
            padding: 20px;
        }
        section.active {
            display: block;
        }
        h2 {
            border-bottom: 2px solid #2c3e50;
            color: #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        iframe {
            border: none;
            margin-bottom: 20px;
            width: 100%;
            height: 600px;
        }
        .toggle-buttons {
            margin-bottom: 15px;
            text-align: center;
        }
        .toggle-buttons button {
            padding: 10px 15px;
            margin: 0 5px;
            font-size: 16px;
            cursor: pointer;
            background-color: #34495e;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .toggle-buttons button:hover {
            background-color: #2c3e50;
        }
        .hidden {
            display: none;
        }
        .indication{
            margin-left: 10px;
            font-size: 18px;
        }
        #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        flex-direction: column;
    }

.loading-text {
    margin-bottom: 30px; /* Espace entre le texte et le spinner */
    font-size: 1.4em;
    color: #2c3e50;
    font-weight: bold;
    text-align: center;
    position: relative;
    top: -20px; /* Ajustement fin de la position */
}

.loading-spinner {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
    <script>
        // Ajout du spinner chargement : 
    document.addEventListener('DOMContentLoaded', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        const iframes = document.querySelectorAll('iframe');
        let loadedCount = 0;

        function checkAllLoaded() {
            loadedCount++;
            if (loadedCount === iframes.length) {
                // Ajout d'un léger délai pour une transition fluide
                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                }, 300);
            }
        }

        iframes.forEach(iframe => {
            // Vérifier si l'iframe est déjà chargée
            if (iframe.contentDocument && iframe.contentDocument.readyState === 'complete') {
                checkAllLoaded();
            } else {
                iframe.addEventListener('load', checkAllLoaded);
                iframe.addEventListener('error', checkAllLoaded); // Gérer les erreurs
            }
        });

        // Cas où il n'y a aucune iframe
        if (iframes.length === 0) {
            loadingOverlay.style.display = 'none';
        }
    });
    // Fin du spinner de chargement

        function showSection(sectionId) {
            const sections = document.querySelectorAll('section');
            document.getElementById("indication").style.visibility= "hidden";
            sections.forEach(section => section.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');
        }

        function toggleGraphs(sectionId, graphType) {
            const section = document.getElementById(sectionId);
            const graphs = section.querySelectorAll(`iframe`);
            graphs.forEach(graph => {
                if (graph.classList.contains(graphType)) {
                    graph.classList.remove('hidden');
                } else {
                    graph.classList.add('hidden');
                }
            });
        }
    </script>
</head>
<body>
<div id="loading-overlay">    
    <div class="loading-text">Veuillez patienter, chargement des graphiques...</div>
    <div class="loading-spinner"></div>
</div>
    <header>
    <div class="logo_site">
    <a href="../index.php"><img src="../images/images_ced/logo.png" alt="Logo"></a>
    </div>
        <h1 class="titre_page">Graphique pour : <?php echo $country_safe; ?></h1>
    </header>
    <nav>
        <?php foreach (array_keys($sections) as $section): ?>
            <a href="javascript:void(0)" onclick="showSection('<?php echo $section; ?>')">
                <?php echo ucfirst($section); ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <main>
        <?php foreach ($sections as $section => $types): ?>
            <section id="<?php echo $section; ?>">
                <h2><?php echo ucfirst($section); ?> Graphs</h2>
                <div class="toggle-buttons">
                    <button onclick="toggleGraphs('<?php echo $section; ?>', 'barplot')">Show Barplots</button>
                    <button onclick="toggleGraphs('<?php echo $section; ?>', 'lineplot')">Show Lineplots</button>
                    <button onclick="toggleGraphs('<?php echo $section; ?>', 'boxplot')">Show Boxplots</button>
                </div>

                <!-- BARPLOTS -->
                <?php if (!empty($types['barplots'])): ?>
                    <?php foreach ($types['barplots'] as $file): ?>
                        <iframe src="<?php echo htmlspecialchars($file); ?>" class="barplot"></iframe>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- BOXPLOTS -->
                <?php if (!empty($types['boxplots'])): ?>
                    <?php foreach ($types['boxplots'] as $file): ?>
                        <iframe src="<?php echo htmlspecialchars($file); ?>" class="boxplot hidden"></iframe>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- LINEPLOTS -->
                <?php if (!empty($types['lineplots'])): ?>
                    <?php foreach ($types['lineplots'] as $file): ?>
                        <iframe src="<?php echo htmlspecialchars($file); ?>" class="lineplot hidden"></iframe>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </main>
    <p id="indication"> Veuillez sélectionner un onglet afin de naviguer. </p>
</body>
</html>
