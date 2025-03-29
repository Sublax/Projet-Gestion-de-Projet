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
        echo '<a href="dataviz/questionnaire.php" class="start-button">Essaye le questionnaire !</a>';
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
                <!-- Imagen debajo del párrafo -->
                <img src="images/images_ced/chatbot-image.png" alt="ChatBot Assistant" class="chatbot-image">
                <button class="next-button" onclick="startChat()">Suivant</button>
            </div>

            <!-- Interfaz del chat -->
            <div class="chat-interface" id="chat-interface" style="display: none;">
                <!-- Contenedor de mensajes -->
                <div id="chat-messages" class="chat-messages">
                    <p class="bot-message"><strong>Bonjour, comment puis-je vous aider ?</strong></p>
                </div>
            </div>

            <!-- Input de chat (ahora separado) -->
            <div class="chat-input" id="chat-input" style="display: none;">
                <input type="text" id="chat-input-field" placeholder="Écrivez votre message..." />
                <button onclick="sendMessage()">Envoyer</button>
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
    document.getElementById("chat-intro").style.display = "none"; 
    document.getElementById("chat-interface").style.display = "flex"; 
    document.getElementById("chat-input").style.display = "flex";   
  }

  async function sendMessage() {
    const inputField = document.getElementById("chat-input-field");
    const message = inputField.value.trim();

    if (message !== "") {
      const chatMessages = document.getElementById("chat-messages");

      // Mostrar mensaje del usuario
      const userMessage = document.createElement("p");
      userMessage.textContent = message;
      userMessage.classList.add("user-message");
      chatMessages.appendChild(userMessage);

      // Hacemos la petición al servidor Node, que internamente llama a obtenerRéponseIA
      setTimeout(async () => {
        const botMessageElem = document.createElement("p");
        botMessageElem.classList.add("bot-message");

        try {
          // Ajusta la URL si tu server corre en otro puerto o ruta
          const res = await fetch("http://localhost:3000/api/chat", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message })
          });

          const data = await res.json();
          // data.response contiene el texto que Node generó con OpenAI
          botMessageElem.textContent = data.response;
        } catch (error) {
          botMessageElem.textContent = "Error: " + error.message;
        }

        chatMessages.appendChild(botMessageElem);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }, 1000);

      // Limpiar el campo de entrada y hacer scroll
      inputField.value = "";
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  }

  document.getElementById("chat-input-field").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {  
      event.preventDefault(); 
      sendMessage();
    }
  });
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