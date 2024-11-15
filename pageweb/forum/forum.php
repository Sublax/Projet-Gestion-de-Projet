<?php 
include "../bd.php";
$bdd = getBD();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Forum - Où partir</title>
</head>


<body>
    <!-- Menu superieur -->
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
    <a href="../index.php"><img src="../images/images_ced/icone3.png" alt="Logo"></a>
        
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


<section class="forum">
    <h1>Forum - Liste des pays</h1>
    <p>Exprimez-vous !</p>
    <div class="field">
    <input type="text" id="recherchePays" placeholder="Rechercher un pays..." onkeyup="filterCountries()">
    <div class="line"></div>
    </div>
    <?php 
    $sql = 'SELECT id_pays,nom_pays FROM pays';
    $stmt = $bdd->query($sql);
    while($ligne = $stmt -> fetch()){
        echo '<div class="country_list">';
        echo '<div class="section_pays">';
        echo '<a href="commentaires.php?id_pays=' . $ligne['id_pays'] . '"> ' .$ligne['nom_pays'] . '</a>';        
        echo '</div>';
        echo '</div>';
    }
    ?>
</section>


<script>
    function filterCountries() {
        // Récupération de la valeur de l'input et conversion en minuscule
        let input = document.getElementById('recherchePays').value.toLowerCase();
        // Sélection de tous les éléments de pays
        let countries = document.getElementsByClassName('country_list');
        
        // Boucle sur chaque pays pour vérifier s'il correspond à la recherche
        for (let i = 0; i < countries.length; i++) {
            let countryName = countries[i].getElementsByClassName('section_pays')[0].innerText.toLowerCase();
            // Affiche ou cache les pays selon la correspondance
            if (countryName.includes(input)) {
                countries[i].style.display = "";
            } else {
                countries[i].style.display = "none";
            }
        }
    }
</script>
</body>
<footer>
    <p>&copy; 2024 Payspédia. Tous droits réservés.</p>
</footer>
</html>