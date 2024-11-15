<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>

    <!-- Menu superieur -->
    <header>
    <div class="menu-bar">
    <div class="menu-item">
    <?php
        if (isset($_SESSION['client'])) {
            echo '<a href="questionnaire.php">';
        } else {
            echo '<a href="connexion/login.php">';
        }
        ?>
        <img src="images/images_ced/icone1.png" alt="Icone Questionnaire">
        </a>
        <p>Questionnaire</p>
    </div>
    <div class="menu-item">
    <a href="graph.php"><img src="images/images_ced/icone2.png" alt="Icone Statistiques & Graphs" ></a>
        <p>Statistiques & Graphs</p>
    </div>
    <div class="menu-item">
    <a href="forum/forum.php"><img src="images/images_ced/icone7.png" alt="Forum"></a>
       <p>Forum</p>
   </div>
    <div class="menu-item logo">
    <a href="index.php"><img src="images/images_ced/icone3.png" alt="Logo"></a>
        
    </div>
    <div class="menu-item">
    <a href="informations/informations.php"><img src="images/images_ced/icone4.png" alt="Icone Informations"></a>
        <p>Informations</p>
    </div>
    <div class="menu-item">
    <a href="informations/sources.php"><img src="images/images_ced/icone5.png" alt="Icone Sources données"></a>
        <p>Sources données</p>
    </div>
    <div class="menu-item">
    <a href="profil.php"><img src="images/images_ced/icone6.png" alt="Icone Options"></a>
        <p>Profil</p>
    </div>
    </header>


    <!-- Background Video Section -->
    <div class="video-background">

        <video autoplay muted loop id="backgroundVideo">
            <source src="./images/map2.mp4" type="video/mp4">
            Votre navigateur ne supporte pas le contenu.
        </video>
        <!-- Essaye le questionnaire logic -->
    <?php
    if (isset($_SESSION['client'])) {
        echo '<div class="hero">';
        echo '<a href="questionnaire.html" class="start-button">Essaye le questionnaire !</a>';
        echo '</div>';
    } else {
        echo '<div class="hero">';
        echo '<a href="connexion/login.php" class="start-button">Essaye le questionnaire !</a>';
        echo '</div>';
    }
    ?>
    </div>

    <!-- Contenu principal -->
    <main>
        <div class="section">
        <h2>Qui sommes-nous ?</h2>
        <p>Nous sommes un groupe de 4 étudiants en Licence MIASHS et nous voulons proposer à quiconque de pouvoir simplifier sa recherche de voyage en permettant de donner un avis externe selon vos goûts. Nous affichons aussi des statistiques et graphiques permettant de se faire sa propre idée d'où partir. Les sources sont à votre disposition dans la page “Source données”.</p>
        </div>

        <div class="section">
        <h2>Notre objectif</h2>
        <p>Ce site web est à but non-lucratif, nous voulons donner un accès gratuit à un regroupement d’information sur des destinations dans le monde. Afin de ne plus perdre de temps à faire des recherches, nous souhaitons vous accompagner dans cette démarche. Nous axons avant tout, notre travail sur la fiabilité et la sécurité.</p>
        </div>

        <div class="testimonial-section">
        <div class="testimonial">
            <img src="./images/default_user.jpg" alt="User Icon" class="user-icon">
            <h3>A visité : France</h3>
            <p>Très joli pays, je ne regrette pas mon voyage, merci !</p>
        </div>
        <div class="testimonial">
            <img src="./images/default_user.jpg" alt="User Icon" class="user-icon">
            <h3>A visité : Thaïlande</h3>
            <p>Un pays sans commune mesure. Un vrai spectacle du début à la fin. Les massages thaïlandais sont les meilleurs, mais je déconseille quand même Pattaya.</p>
        </div>
    </div>

    <div class="contact-section">
    <h1> Une question ? Contactez-nous !</h1>
        <div class="question">
        <?php if (isset($_SESSION['client'])): ?>
            <form id= "sendMessageForm" action="contact/message.php" method="post">

                <label for="objet">Objet:</label>
                <input type="text" id="objet" name="objet" required><br><br>

                <label for="msg">Message:</label>
                <textarea id="msg" name="msg" rows="4" cols="50" required></textarea><br><br>

                <input type="submit" onclick="confirmSendMessage()" value="Envoyer">
                <p> 
                <?php
                if(isset($_SESSION["messageSendTrue"])){
                    echo '<p id="messageSendTrue"> Message envoyé ! </p>';
                    unset($_SESSION["messageSendTrue"]);
                }
                ?></p>
            </form>
        <?php else: ?>
            <p>Pour envoyer un message, vous devez être connecté.</p>
            <a href="connexion/login.php">Se connecter</a>
        <?php endif; ?>
        </div>
    </div>
    </main>

</body>
<footer>
    <p>&copy; 2024 Payspédia. Tous droits réservés.</p>
</footer>
</html>

<script>
function confirmSendMessage() {
confirm("Voulez-vous envoyer ce message ?")
}
</script>