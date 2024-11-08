<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<?php
session_start();
?>


<body>
    <header>
        <div class="menu-bar">
        <div class="menu-item">
            <img src="../images/images_ced/icone1.png" alt="Icone Questionnaire">
            <p>Questionnaire</p>
        </div>
        <div class="menu-item">
            <img src="images/images_ced/icone2.png" alt="Icone Statistiques & Graphs">
            <p>Statistiques & Graphs</p>
        </div>
        <div class="menu-item logo">
            <img src="images/images_ced/icone3.png" alt="Logo">
        </div>
        <div class="menu-item">
            <img src="images/images_ced/icone4.png" alt="Icone Informations">
            <p>Informations</p>
        </div>
        <div class="menu-item">
            <img src="images/images_ced/icone5.png" alt="Icone Sources données">
            <p>Sources données</p>
        </div>
        <div class="menu-item">
            <img src="images/images_ced/icone6.png" alt="Icone Options">
            <p>Options</p>
        </div>
        </header>
        
    <div class="container">

        <!-- Profile content -->
        <div class="profile">
            <?php
            // Photo
            echo "<div class='profile-photo'>
                    <p>".htmlspecialchars($_SESSION['username'])."</p>
                    <p>'s photo</p>
                    </div>";
                

            // Username
            echo "<div class='username'
                    <h1>". htmlspecialchars($_SESSION['username']) ."</h1>
                    </div>";
            ?>

                <div class="stats">
                    <div class="stat-box">
                        <p>Posts</p>
                        <p>3</p>
                    </div>
                    <div class="stat-box">
                        <p>Pays visités</p>
                        <p>10</p>
                    </div>
                </div>
                <textarea placeholder="Écrivez votre biographie..."></textarea>
                <button class="logout-button">Déconnexion</button>
            </div>
        </div>
    </div>
</body>
</html>
