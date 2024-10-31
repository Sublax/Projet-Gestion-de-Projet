<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet</title>
    <link rel="stylesheet" href="./styles/style_max.css">
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
        <nav class="top-menu">
            <ul>
                <li><a href="questionnaire.php">Questionnaire</a></li>
                <li><a href="graph.php">Graphiques &amp; Graphs</a></li>
                <li><a href="forum/forum.php">Forum</a></li>
                <li><a href="index.php"><img src="logo.png" alt="Logo" class="logo"></a></li>
                <li><a href="informations/informations.php">Informations</a></li>
                <li><a href="data.php">Sources données</a></li>
                <li><a href="options.php">Options</a></li>
            </ul>
        </nav>
    </header>
    <div class="background-image">
    <?php
    if (isset($_SESSION['client'])) {
        echo '<a href="questionnaire.php" class="start-button">Essaye le questionnaire !</a>';
    } else {
        echo '<a href="connexion/login.php" class="start-button">Essaye le questionnaire !</a>';
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

    <div class="container">
        <div class="review-card">
            <img src="user-icon.png" alt="User Icon" class="user-icon">
            <h3>A visité : France</h3>
            <p>Très joli pays, je ne regrette pas mon voyage, merci !</p>
        </div>
        <div class="review-card">
            <img src="user-icon.png" alt="User Icon" class="user-icon">
            <h3>A visité : Thaïlande</h3>
            <p>Un pays sans commune mesure. Un vrai spectacle du début à la fin. Les massages thaïlandais sont les meilleurs, mais je déconseille quand même Pattaya.</p>
        </div>
    </div>

    <div class="contactus">
    <h1> Une question ? Contactez-nous !</h1>
        <div class="question">
        <?php if (isset($_SESSION['client'])): ?>
            <form action="contact/message.php" method="post">
                <label for="n">Nom:</label>
                <input type="text" id="n" name="n" value="<?php echo $prenom; ?>" readonly><br><br>

                <label for="mail">Adresse e-mail:</label>
                <input type="email" id="mail" name="mail" value="<?php echo $email; ?>" readonly><br><br>

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
