<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sources des données</title>
    <link rel="stylesheet" href="../styles/style_ced.css">
</head>
<body>
        <!-- Menu superieur -->
        <header>
            <div class="menu-bar">
            <div class="menu-item">
            <?php
                if (isset($_SESSION['client'])) {
                    echo '<a href="../questionnaire.php"</a>';
                } else {
                    echo '<a href="../connexion/login.php" </a>';
                }
                ?><img src="../images/images_ced/icone1.png" alt="Icone Questionnaire">
                <p>Questionnaire</p>
            </div>
            <div class="menu-item">
            <a href="../graph.php"><img src="../images/images_ced/icone2.png" alt="Icone Statistiques & Graphs">
                <p>Statistiques & Graphs</p>
            </div>
            <div class="menu-item">
            <a href="../forum/forum.php"><img src="../images/images_ced/icone7.png" alt="Forum"></a>
               <p>Forum</p>
           </div>
            <div class="menu-item logo">
            <a href="../index.php"><img src="../images/images_ced/icone3.png" alt="Logo">
                
            </div>
            <div class="menu-item">
            <a href="informations.php"><img src="../images/images_ced/icone4.png" alt="Icone Informations">
                <p>Informations</p>
            </div>
            <div class="menu-item">
            <a href="sources.php"><img src="../images/images_ced/icone5.png" alt="Icone Sources données">
                <p>Sources données</p>
            </div>
            <div class="menu-item">
            <a href="../profil.php"><img src="../images/images_ced/icone6.png" alt="Icone Options">
                <p>Profil</p>
            </div>
            </header>
        

    <main>
        <section class="sources">
            <h1>Sources de nos données</h1>
            <p>Afin de vous assurer au mieux de la fiabilité de nos informations, nous souhaitons répertorier les sources des données ici.</p>

            <div class="category">
                <h2>Agroalimentaire</h2>
                <ul>
                    <li><a href="https://databank.worldbank.org/source/world-development-indicators/Series/EG.CFT.ACCS.ZS">World Development Indicators</a></li>
                    <li><a href="https://databank.worldbank.org/source/food-prices-for-nutrition">Food Prices for Nutrition</a></li>
                </ul>
            </div>

            <div class="category">
                <h2>Économie</h2>
                <ul>
                    <li><a href="https://ourworldindata.org/grapher/gdp-per-capita-worldbank">GDP per capita</a></li>
                    <li><a href="https://worldpopulationreview.com/country-rankings/minimum-wage-by-country">Minimum wage by country</a></li>
                    <li><a href="https://databank.worldbank.org/CEI/id/4f696fdf">World Bank CEI Data</a></li>
                </ul>
            </div>

            <div class="category">
                <h2>Transport</h2>
                <ul>
                    <li><a href="https://ourworldindata.org/grapher/share-with-convenient-access-to-public-transport">Access to public transport</a></li>
                </ul>
            </div>

            <div class="category">
                <h2>Travail</h2>
                <ul>
                    <li><a href="https://ourworldindata.org/grapher/share-with-convenient-access-to-public-transport">Access to public transport</a></li>
                </ul>
            </div>

            <div class="category">
                <h2>Climat</h2>
                <ul>
                    <li><a href="https://ourworldindata.org/grapher/share-with-convenient-access-to-public-transport">Access to public transport</a></li>
                </ul>
            </div>

            <div class="category">
                <h2>Religion</h2>
                <ul>
                    <li><a href="https://ourworldindata.org/grapher/share-with-convenient-access-to-public-transport">Access to public transport</a></li>
                </ul>
            </div>

            <div class="category">
                <h2>Bonheur</h2>
                <ul>
                    <li><a href="https://ourworldindata.org/grapher/share-with-convenient-access-to-public-transport">Access to public transport</a></li>
                </ul>
            </div>
            
            <div class="category">
                <h2>Santé</h2>
                <ul>
                    <li><a href="https://ourworldindata.org/grapher/share-with-convenient-access-to-public-transport">Access to public transport</a></li>
                </ul>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Payspédia. Tous droits réservés.</p>
    </footer>
</body>
</html>
