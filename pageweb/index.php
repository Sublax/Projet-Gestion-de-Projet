<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet</title>
    <link rel="stylesheet" href="./styles/styles.css">
</head>
<body>
<?php
// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['client'])) {
    $prenom = isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) : '';
    $email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; // Supposant que l'email est stocké dans la session
    echo "Connexion réussie, $prenom !";
} else {
    echo "Veuillez vous connecter pour accéder à toutes les fonctionnalités.";
}
?>
    <!-- Menu superieur -->
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

    <!-- Background Video Section -->
    <div class="video-background">
        <video autoplay muted loop id="backgroundVideo">
            <source src="map_video.mp4" type="video/mp4">
            Your browser does not support the video tag.
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
            <img src="user-icon.png" alt="User Icon" class="user-icon">
            <h3>A visité : France</h3>
            <p>Très joli pays, je ne regrette pas mon voyage, merci !</p>
        </div>
        <div class="testimonial">
            <img src="user-icon.png" alt="User Icon" class="user-icon">
            <h3>A visité : Thaïlande</h3>
            <p>Un pays sans commune mesure. Un vrai spectacle du début à la fin. Les massages thaïlandais sont les meilleurs, mais je déconseille quand même Pattaya.</p>
        </div>
    </div>

    <div class="contact-section">
    <h1> Une question ? Contactez-nous !</h1>
        <div class="question">
        <?php if (isset($_SESSION['client'])): ?>
            <form action="contact/message.php" method="post">

                <label for="objet">Objet:</label>
                <input type="text" id="objet" name="objet" required><br><br>

                <label for="msg">Message:</label>
                <textarea id="msg" name="msg" rows="4" cols="50" required></textarea><br><br>

                <input type="submit" value="Envoyer">
            </form>
        <?php else: ?>
            <p>Pour envoyer un message, vous devez être connecté.</p>
            <a href="connexion/login.php">Se connecter</a>
        <?php endif; ?>
        </div>
    </div>
    </main>

</body>
</html>
