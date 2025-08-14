<?php include 'navbar.php';
      include 'bd.php';
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
    <div class="video-background">
        <video autoplay muted loop id="backgroundVideo">
            <source src="./images/map3.mp4" type="video/mp4">
            Votre navigateur ne supporte pas le contenu.
        </video>
    <?php
    if (isset($_SESSION['client'])) {
        echo '<div class="hero">';
        echo '<a href="https://map-system-production.up.railway.app/questionnaire.php" class="start-button">Essaye le questionnaire !</a>';
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
        <p>Nous sommes un groupe de 4 étudiants en Licence MIASHS, tous originaire de pays différents et nous voulons proposer à quiconque de pouvoir simplifier sa recherche de voyage en permettant de donner un avis externe selon vos goûts.
        Nous affichons aussi des statistiques et graphiques permettant de se faire sa propre idée d'où partir.
        Les sources des données sont à votre disposition dans la page “Source données”.
    </p>
        </div>

        <div class="section">
        <h2>Notre objectif</h2>
        <p>Ce site web est à but non-lucratif, nous voulons donner un accès gratuit à un regroupement d’information sur des destinations dans le monde.
            Afin de ne plus perdre de temps à faire des recherches, nous souhaitons vous accompagner dans cette démarche. 
            Nous axons avant tout, notre travail sur la fiabilité et la sécurité.
            Le questionnaire vous permet de vous indiquez à titre informatif quel pays peut vous correspondre, bien sûr, il existe des biais dans nos données,
            nous vous conseillons tout de même de faire vos propres recherches ou de consulter les avis des autres utilisateurs sur le forum.
        </p>
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
            <form id= "sendMessageForm" class ="contact-form" action="contact/message.php" method="post">

                <label for="objet">Objet:</label>
                <input type="text" id="objet" name="objet" required><br><br>
                </input>
                <label for="msg">Message:</label>
                <textarea id="msg" name="msg" rows="4" cols="45" required></textarea><br><br>

                <input type="submit"  onclick="confirmSendMessage()" value="Envoyer">
                </input>
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
    <?php include 'chat-ia.php'; ?>


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
