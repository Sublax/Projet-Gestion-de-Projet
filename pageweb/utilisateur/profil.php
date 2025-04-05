<?php
include "../bd.php";
$bdd = getBD();
include '../navbar.php' ;
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

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_biography'])) {
        if (isset($_SESSION['client']) && isset($_POST['biographie'])) {
            $biography = trim($_POST['biographie']);
            $clientId = $_SESSION['client'];
    
            // Mettre à jour la biographie dans la base de données
            $stmt = $bdd->prepare('UPDATE info_clients SET biographie = :biography WHERE id_client = :id');
            $stmt->execute([
                ':biography' => $biography,
                ':id' => $clientId,
            ]);
    
        } else {
            echo "<p>Erreur : Vous devez être connecté pour modifier votre biographie.</p>";
        }
    }
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
<p> Si vous souhaitez prédire un pays selon vos goûts, <a href="./flag.php">cliquez-ici</a> !</p>
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
        <p>
        <?php
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $stmt = $bdd->prepare('SELECT nb_pays_visite
            FROM info_clients
            INNER JOIN clients ON info_clients.id_client = clients.id_client
            WHERE clients.nom_utilisateur = :username');
            $stmt->execute([
                ':username' => $username
            ]);
            $nbPaysVisite = $stmt->fetchColumn();
            echo $nbPaysVisite;
        }
        ?>
        </p>
    </div>
    <div class='stat-box'>
        <p>Vient de : </p>
        <p><?php echo $_SESSION['location'] ?></p>
    </div>
    <div class='stat-box'>
        <p>Pays prédis : </p>
        <p><?php 
        if(isset($_SESSION["pays_predis"])){
            $pays_predis = $_SESSION["pays_predis"];
            if(!empty($pays_predis)){
                foreach($pays_predis as $key){
                    echo "<p>" . $key . "</p>";
                }
            }else{
                echo "Aucun";
            }
        }?></p>
    </div>
</div>

<!-- Modifier la biographie -->
<form method="POST" action="profil.php">
    <textarea id ='biography' name="biographie" placeholder="Écrivez votre biographie..."><?php 
        // Afficher la biographie actuelle s'il y en a une
        if (isset($_SESSION['client'])) {
            $stmt = $bdd->prepare('SELECT biographie FROM info_clients WHERE id_client = :id');
            $stmt->execute([':id' => $_SESSION['client']]);
            $currentBiography = $stmt->fetchColumn();
            echo htmlspecialchars($currentBiography);
        }
    ?></textarea>
    <button id ="save" type="submit" name="save_biography" >Sauvegarder</button>
</form>
<button class='logout-button'> <a class="logout-button" href="../connexion/deconnection.php">Déconnexion </a></button> 
</div>
</div>
</div>
    <?php
    }else{
        echo "<p class= 'creaprofil' >Vous n'avez pas encore de profil ?<a href='../connexion/register.php'> Créez-en un ! </a><a href='../connexion/login.php'> Ou connectez-vous </a></p>";
    }
            ?>

<?php include '../chat-ia.php'; ?>
</body>
</html>
