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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphs for <?php echo $country_safe; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
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
    </style>
    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('section');
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
    <header>
        <h1>Graphs for <?php echo $country_safe; ?></h1>
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
</body>
</html>
