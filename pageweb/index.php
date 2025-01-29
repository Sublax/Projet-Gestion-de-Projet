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
            echo '<a href="dataviz/questionnaire.php">';
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
    <a href="index.php"><img src="images/images_ced/logo.png" alt="Logo"></a>
        
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
    <a href="../utilisateur/profil.php"><img src="images/images_ced/icone6.png" alt="Icone Options"></a>
        <p>Profil</p>
    </div>
    </header>


    <div class="video-background">

        <video autoplay muted loop id="backgroundVideo">
            <source src="./images/map2.mp4" type="video/mp4">
            Votre navigateur ne supporte pas le contenu.
        </video>
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
    <div class="chatbot-container">
    <button class="chatbot-button" onclick="toggleChat()">💬</button>
    <div class="chatbox" id="chatbox">
        <button class="close-button" onclick="toggleChat()">✖</button>

        <!-- Presentación del chatbot -->
        <div class="chat-intro" id="chat-intro">
            <p>Bonjour, je suis <strong>ChatBot</strong> et je suis ici pour vous aider à naviguer sur notre site. 
            Si vous avez des questions, n'hésitez pas à me contacter. J'espère pouvoir vous être utile !</p>
            <button class="next-button" onclick="startChat()">Siguiente</button>
        </div>

        <!-- Interfaz del chat (inicialmente oculta) -->
        <div class="chat-interface" id="chat-interface" style="display: none;">
            <div id="chat-messages" class="chat-messages">
                <p>Bonjour, comment puis-je vous aider ?</p>
            </div>
            <div class="chat-input">
                <input type="text" id="chat-input-field" placeholder="Écrivez votre message..." />
                <button onclick="sendMessage()">Envoyer</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleChat() {
        var chatbox = document.getElementById("chatbox");
        var button = document.querySelector(".chatbot-button");
        
        if (chatbox.style.display === "none" || chatbox.style.display === "") {
            chatbox.style.display = "flex";
            button.style.display = "none";
        } else {
            chatbox.style.display = "none";
            button.style.display = "flex";
        }
    }

    function startChat() {
        document.getElementById("chat-intro").style.display = "none"; // Ocultar la intro
        document.getElementById("chat-interface").style.display = "flex"; // Mostrar el chat
    }

    function sendMessage() {
        var inputField = document.getElementById("chat-input-field");
        var message = inputField.value.trim();
        
        if (message !== "") {
            var chatMessages = document.getElementById("chat-messages");
            var newMessage = document.createElement("p");
            newMessage.textContent = message;
            chatMessages.appendChild(newMessage);
            
            inputField.value = "";
            chatMessages.scrollTop = chatMessages.scrollHeight; // Desplazar hacia abajo
        }
    }
</script>

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