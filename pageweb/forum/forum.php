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
<header>
    Saltu
</header>

<section class="forum">
    <h1>Forum - Liste des pays</h1>
    <p>Exprimez-vous !</p>
    <input type="text" id="recherchePays" placeholder="Rechercher un pays..." onkeyup="filterCountries()">
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
