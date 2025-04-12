<!-- chatbot.php -->
<div class="chatbot-container">
  <button class="chatbot-button" onclick="toggleChat()">💬</button>
  <div class="chatbox" id="chatbox">
    <button class="close-button" onclick="toggleChat()">✖</button>

    <!-- Presentation du chatbot -->
    <div class="chat-intro" id="chat-intro">
      <p>Bonjour, je suis <strong>ChatBot</strong> je vous aide à naviguer sur notre site. 
      Si vous avez des questions par rapport aux pays, n'hésitez pas. Sinon,
        <a href="https://aichatsystem-production.up.railway.app/chat.php">accédez au chat plus général ! </a></p>
      </p>
      <!-- image chatbot -->
      <img src="/images/images_ced/chatbot-image.png" alt="ChatBot Assistant" class="chatbot-image">
      <button class="next-button" onclick="startChat()">Suivant</button>
    </div>

    <!-- interface du chat -->
    <div class="chat-interface" id="chat-interface" style="display: none;">
      <!-- Contenedor de mensajes -->
      <div id="chat-messages" class="chat-messages">
        <p class="bot-message"><strong>Bonjour, comment puis-je vous aider ?</strong>
        Si vous avez des questions par rapport aux pays, n'hésitez pas. Sinon,
        <a href="/ai_chat/chat.php">accédez au chat plus général ! </a></p>  
      </div>
    </div>

    <!-- input du chat -->
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

      // Hacer la petición al servidor Node que llama a obtenirRéponseIA
      setTimeout(async () => {
        const botMessageElem = document.createElement("p");
        botMessageElem.classList.add("bot-message");

        try {
            const res = await fetch("https://projet-gestion-de-projet-production-f29a.up.railway.app/api/chat", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message })
          });
          const data = await res.json();
          botMessageElem.textContent = data.response;
        } catch (error) {
          botMessageElem.textContent = "Error: " + error.message;
        }

        chatMessages.appendChild(botMessageElem);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }, 1000);

      // Limpiar el campo y actualizar el scroll
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
