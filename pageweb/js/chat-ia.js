import "dotenv/config";
import OpenAI from "openai";
import fs from "fs"; // For reading files

const openai = new OpenAI({ apiKey: process.env.OPENAI_API_KEY });

console.log("API Key loaded:", process.env.OPENAI_API_KEY ? "Yes" : "No");

// Function to read a file
function lireFichier(filePath) {
  try {
    if (!fs.existsSync(filePath)) {
      console.error(`❌ File not found: ${filePath}`);
      return null;
    }
    return fs.readFileSync(filePath, "utf-8"); // Load the code
  } catch (error) {
    console.error("❌ Error reading the file:", error);
    return null;
  }
}

// Function to send the PHP code to OpenAI for analysis
async function obtenirRéponseIA(messageUtilisateur) {
  // Read the PHP code
  const index = lireFichier("../index.php");
  const navbar = lireFichier("../navbar.php");
  const deconnection = lireFichier("../connexion/deconnection.php");
  const login = lireFichier("../connexion/login.php");
  const register = lireFichier("../connexion/register.php");
  const process_login = lireFichier("../connexion/process_login.php");
  const process_register = lireFichier("../connexion/process_register.php");
  const forum = lireFichier("../forum/forum.php")
  const informations = lireFichier("../informations/informations.php")
  const sources = lireFichier("../informations/sources.php")
  const profil = lireFichier("../utilisateur/profil.php")

  const codePHP = [index, navbar, deconnection, login, register, process_login, process_register, forum, informations, sources, profil]
                    .filter(content => content !== null)
                    .join("\n");

  if (!codePHP) {
    return "Je ne peux pas accéder aux informations techniques pour le moment.";
  }

  // Send the code to OpenAI as a structured prompt
  try {
    const completion = await openai.chat.completions.create({
      model: "gpt-4o-mini",
      max_tokens: 150, // 🔹 Limite la réponse à 150 tokens
      temperature: 0.5, // 🔹 Contrôle la diversité des réponses
      top_p: 0.9, // 🔹 Ajuste la sélection des tokens générés
      stop: ["\n\n"], // 🔹 Stoppe la réponse après une phrase complète
      messages: [
        { role: "system", content: "Vous êtes un assistant qui guide l'utilisateur dans les fonctionalités du site" },
        { role: "system", content: `Code source PHP:\n\n${codePHP}` },
        { role: "user", content: messageUtilisateur }
      ],
    });

    return completion.choices[0].message.content;
  } catch (error) {
    console.error("❌ Error calling OpenAI:", error);
    return "Désolé, une erreur s'est produite lors du traitement.";
  }
}

// Example test: Asking about a function in index.php
obtenirRéponseIA("Comment aller vers la page profil?")
  .then(response => console.log("Réponse IA:", response))
  .catch(error => console.error("❌ Error:", error));
