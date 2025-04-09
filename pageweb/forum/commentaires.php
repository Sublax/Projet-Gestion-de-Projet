<?php 
include '../navbar.php';
include '../bd.php';
$bdd = getBD();
if(isset($_GET['id_pays'])){
    // On verifie si l'ID donné en lien existe vraiment
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
    <!--<div class="menu-item">
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
    </header>-->


<section class="forum">
<h1> Bienvenue sur le forum <?php echo '' . $nom_pays .'' ?> </h1>   
<div class="graph_avis">
    <a href="graph_avis.php?id_pays=<?php echo $id_pays; ?>">
        <button>Voir la répartition des avis de ce pays</button>
    </a>
</div>
    <?php
   // -- Partie Affichage du formulaire
   if (isset($_SESSION['client'])) {
       $id_client = $_SESSION['client'];
       echo '<form action="" method="POST">
           <input type="text" class="recherchePays" id="commentaire" name="commentaire" placeholder="Ajoutez votre commentaire">
           <button type="submit">Envoyer</button>
       </form>';
   } else {
       echo 'Vous devez être connecté pour poster un commentaire !';
   }
   
   // -- Partie Insertion base de données

if (isset($_POST["commentaire"])) {
    $commentaire = htmlspecialchars($_POST['commentaire']);

    // Appel à l'API FastAPI pour sentiment et aspect
    $apiUrl = 'http://127.0.0.1:8000/analyser';
    $data = json_encode(['texte' => $commentaire]);

    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => $data,
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($apiUrl, false, $context);
    $result = json_decode($response, true);

    // Vérification de la toxicité    
    if ($result['toxique']) {
        echo '<p style="color: red;"> Ce commentaire a été bloqué car il contient un contenu inapproprié .</p>';
        exit();
    }

// Récupération des résultats de l'API
    $sentiment = $result['sentiment'] ?? "inconnu";
    $aspect = $result['aspect'] ?? "inconnu"; 
        $stmt = $bdd->prepare("INSERT INTO avis (id_client, id_pays, avis, sentiment, aspect,  date) VALUES (:id_client, :id_pays, :avis, :sentiment, :aspect,  NOW());");
        $stmt->execute([
            ':id_client' => $id_client,
            ':id_pays' => $id_pays,
            ':avis' => $commentaire,
            ':sentiment' => $sentiment,
            ':aspect' => $aspect
            
        ]);
        echo '<p>Votre commentaire a été ajouté avec succès !</p>';

        header("Location: commentaires.php?id_pays=$id_pays");
        exit();
    }

   
    // -- Partie Affichage des commentaires de la bdd :
    $stmt = $bdd -> prepare('SELECT avis.*, clients.nom_utilisateur, pays.id_pays FROM avis INNER JOIN clients ON clients.id_client = avis.id_client INNER JOIN pays ON pays.id_pays = avis.id_pays WHERE pays.id_pays = :id_pays ORDER BY id_avis DESC;');
    $stmt->execute([':id_pays' => $id_pays]);
    if ($stmt->rowCount() == 0) {
        echo '<p> Aucun commentaire sur ce pays pour le moment ! :)</p>';
    }else{
        while($ligne = $stmt->fetch()){
            //On formate pour montrer seulement la date, heure:min
            $date_formate = date("d/m/Y H:i", strtotime($ligne['date']));
            $sentiment_color = ($ligne['sentiment']== "positif") ?"green" : "red";
            echo '<p> '. $date_formate.' Commentaire écrit par '.$ligne['nom_utilisateur'] . ' :</p>';
            echo '<p style="color: ' . $sentiment_color . ';">' . ucfirst($ligne["avis"]) .'</p>';
            echo '<br> <br>';
        }
    }


    ?>
</section>
</body>
<footer>
    <p>&copy; 2024 Payspédia. Tous droits réservés.</p>
</footer>
</html>