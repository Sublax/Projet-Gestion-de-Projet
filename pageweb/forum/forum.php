<?php 
include "../bd.php";
$bdd = getBD();
include '../navbar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Forum - Où partir</title>
    <script src="./forum_script.js"></script>
    
</head>


<body>
<section class="forum">
    <h1>Forum - Liste des pays</h1>
    <p>Exprimez-vous !</p>
    <div class="field">
    <input type="text" id="recherchePays" placeholder="Rechercher un pays..." onkeyup="filterCountries()">
    <div class="line"></div>
    </div>

    <form method="post">
        <button type="submit" name="populaire">Populaire</button>
    </form>

    <?php 
    if (isset($_POST['populaire'])) {
        $sql = '
        SELECT p.id_pays, p.nom_pays, COUNT(avis.id_avis) AS nb_avis, MAX(avis.date) AS dernier_post
        FROM pays p
        LEFT JOIN avis ON p.id_pays = avis.id_pays
        GROUP BY p.id_pays, p.nom_pays
        ORDER BY nb_avis DESC
        LIMIT 5'; 
        $stmt = $bdd->query($sql);
        $pays_populaire = $stmt->fetch();
        if ($pays_populaire) {
            echo '<div class="country_list">';
            echo '<div class="section_pays">';
            echo '<a href="commentaires.php?id_pays=' . $pays_populaire['id_pays'] . '">' . $pays_populaire['nom_pays'] . '</a>';
            
            if (!empty($pays_populaire['dernier_post'])) {
                $date_formate = date("d/m/Y H:i", strtotime($pays_populaire['dernier_post']));
                echo '<em> dernier post : ' . $date_formate . '</em>';
            }
            echo '<span class="nbre_avis"> (' . $pays_populaire['nb_avis'] . ' posts)</span>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        $sql = '
        SELECT p.id_pays, p.nom_pays, COUNT(avis.id_avis) AS nb_avis, MAX(avis.date) AS dernier_post
        FROM pays p
        LEFT JOIN avis ON p.id_pays = avis.id_pays
        GROUP BY p.id_pays, p.nom_pays
        ORDER BY p.nom_pays';
        $stmt = $bdd->query($sql);
        while($ligne = $stmt -> fetch()){
            echo '<div class="country_list">';
            echo '<div class="section_pays">';
            echo '<a href="commentaires.php?id_pays=' . $ligne['id_pays'] . '"> ' .$ligne['nom_pays'] . '</a>';   
        if (!empty($ligne['dernier_post'])) {
            $date_formate = date("d/m/Y H:i", strtotime($ligne['dernier_post']));
            echo '<em> dernier post : ' . $date_formate . '</em>';
        }     
            echo '<span class="nbre_avis"> (' . $ligne['nb_avis'] . ' posts ) </span>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</section>
<?php include '../chat-ia.php'; ?>
</body>
<footer>
    <p>&copy; 2024 Payspédia. Tous droits réservés.</p>
</footer>
</html>