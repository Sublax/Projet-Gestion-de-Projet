<?php 
session_start();
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
}else{
    die("Erreur d'ID");
    exit();
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
    <?php
    // -- Partie Affichage du formulaire
    if(isset($_SESSION['id_client'])){
        $id_client = $_SESSION['id_client'];
        echo '<form action="" method="POST">
        <input type="text" id="commentaire" name="commentaire" placeholder="Ajoutez votre commentaire">
        <button type="submit">Envoyer</button>
        </form>';
    }else{
        echo 'Vous devez être connecté pour poster un commentaire !';
    }

    // -- Partie Insertion base de données
    if(isset($_POST["commentaire"])){
        $commentaire = htmlspecialchars($_POST['commentaire']);
        $stmt = $bdd->prepare("INSERT INTO avis (id_client,id_pays,avis,date) VALUES (:id_client,:id_pays,:avis,NOW());");
        $stmt->execute([
            ':id_client' => $id_client,
            ':id_pays' => $id_pays,
            ':avis' => $commentaire
        ]);
        echo '<p> Votre commentaire a été ajouté avec succès !</p>';
    }


    // -- Partie Affichage des commentaires de la bdd :
    $stmt = $bdd -> prepare('SELECT avis.*, clients.nom_utilisateur, pays.id_pays FROM avis INNER JOIN clients ON clients.id_client = avis.id_client INNER JOIN pays ON pays.id_pays = avis.id_pays WHERE pays.id_pays = :id_pays ORDER BY id_avis DESC;');
    $stmt->execute([':id_pays' => $id_pays]);
    if ($stmt->rowCount() == 0) {
        echo '<p> Aucun commentaire sur ce pays pour le moment ! :)</p>';
    }else{
        while($ligne = $stmt->fetch()){
            echo '<p> '. $ligne['date'] .' Commentaire écrit par '.$ligne['nom_utilisateur'] . ' :</p>';
            echo '<p>' .$ligne["avis"] . '</p>';
            echo '<br> <br>';
        }
    }

    ?>
</section>
</body>
</html>