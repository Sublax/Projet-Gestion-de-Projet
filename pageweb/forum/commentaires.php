<?php 
include "../bd.php";
$bdd = getBD();
if(isset($_GET['id_pays'])){
    // On verif si l'ID donné en lien existe vraiment
    $id_pays = (int)$_GET['id_pays'];
    $stmt = $bdd->prepare('SELECT nom_pays FROM pays WHERE id_pays = ?');
    $stmt->execute([$id_pays]);
    $ligne = $stmt->fetch();
    if($ligne){
        $nom_pays = $ligne['nom_pays'];

    }else{
        echo 'ID Incorrect';
        exit;
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Forum - <?php echo '' . $nom_pays .'' ?></title>
</head>
<body>
<section class="forum">
<h1> Bienvenue sur le forum <?php echo '' . $nom_pays .'' ?> </h1>
<input id="comments" placeholder="Ajoutez votre commentaire"> 

</section>
</body>
</html>