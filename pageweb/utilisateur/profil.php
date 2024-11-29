<?php
session_start();
include "../bd.php";
$bdd = getBD();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../styles/styles.css">
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
            <a href="../graph.php"><img src="../images/images_ced/icone2.png" alt="Icone Statistiques & Graphs"></a>
                <p>Statistiques & Graphs</p>
            </div>
            <div class="menu-item">
            <a href="../forum/forum.php"><img src="../images/images_ced/icone7.png" alt="Forum"></a>
               <p>Forum</p>
           </div>
            <div class="menu-item logo">
            <a href="../index.php"><img src="../images/images_ced/icone3.png" alt="Logo"></a>
                
            </div>
            <div class="menu-item">
            <a href="informations.php"><img src="../images/images_ced/icone4.png" alt="Icone Informations"></a>
                <p>Informations</p>
            </div>
            <div class="menu-item">
            <a href="sources.php"><img src="../images/images_ced/icone5.png" alt="Icone Sources données"></a>
                <p>Sources données</p>
            </div>
            <div class="menu-item">
            <a href="./profil.php"><img src="../images/images_ced/icone6.png" alt="Icone Options"></a>
                <p>Profil</p>
            </div>
            </header>
   
    <?php
    if(isset($_SESSION['client'])){    
    echo '<div class="container">';

        echo'<div class="profile">';
            // Photo
            echo "<div class='profile-photo'>
                    <p>".htmlspecialchars($_SESSION['username'])."</p>
                    <p>'s photo</p>
                    </div>";
                
            // Username
            echo "<div class='username'
                    <h1>". htmlspecialchars($_SESSION['username']) ."</h1>
                    </div>"; ?>
<div class='stats'>
    <div class='stat-box'>
        <p>Posts</p>
        <p>
        <?php
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $stmt = $bdd->prepare('SELECT COUNT(*)
            FROM avis
            INNER JOIN clients ON avis.id_client = clients.id_client
            WHERE clients.nom_utilisateur = :username');
            $stmt->execute([
                ':username' => $username
            ]);
            $postCount = $stmt->fetchColumn();
            echo $postCount;
        }


        ?></p>
    </div>
    <div class='stat-box'>
        <p>Pays visités</p>
        <p>10</p>
    </div>
    <div class='stat-box'>
        <p>Vient de : </p>
        <p><?php echo $_SESSION['location'] ?></p>
    </div>
</div>

<!-- Modifier la biographie -->
<textarea placeholder='Écrivez votre biographie...'></textarea>
<button class='logout-button'> <a href="../connexion/deconnection.php">Déconnexion </a></button> 
</div>
</div>
</div>
    <?php
    }else{
        echo "<p>Vous n'avez pas encore de profil !</p>";
        echo "<p><a href='../connexion/register.php'> Créez-en un ! </a></p>";
    }
            ?>


</body>
</html>
