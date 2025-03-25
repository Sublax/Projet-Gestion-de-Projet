import "dotenv/config";
import OpenAI from "openai";
import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

// Esta parte es para que __dirname funcione en un ESM (type: "module")
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Instanciamos el cliente de OpenAI con la API key de tu .env
const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY,
});

// Función para leer archivos con ruta relativa a este archivo JS
function lireFichier(relPath) {
  // El 'relPath' se interpretará desde la carpeta "js/" donde está chat-ia.js
  // con path.join(__dirname, relPath) subimos niveles si es necesario.
  const fullPath = path.join(__dirname, relPath);
  try {
    if (!fs.existsSync(fullPath)) {
      console.error(`❌ File not found: ${fullPath}`);
      return null;
    }
    return fs.readFileSync(fullPath, "utf-8");
  } catch (error) {
    console.error("❌ Error reading the file:", error);
    return null;
  }
}

// Exportamos la función principal que invoca a OpenAI
export async function obtenirRéponseIA(messageUtilisateur) {
  // Ajusta las rutas para subir un nivel (../) y así llegar a tus .php
  const index        = lireFichier("../index.php");
  const navbar       = lireFichier("../navbar.php");
  // Si tienes una carpeta "connexion" al mismo nivel que "js", sube dos niveles
  // o ajusta según tu estructura real.
  const deconnection     = lireFichier("../connexion/deconnection.php");
  const login            = lireFichier("../connexion/login.php");
  const register         = lireFichier("../connexion/register.php");
  const process_login    = lireFichier("../connexion/process_login.php");
  const process_register = lireFichier("../connexion/process_register.php");
  // Y así con el resto de tus archivos .php:
  const forum         = lireFichier("../forum/forum.php");
  const informations  = lireFichier("../informations/informations.php");
  const sources       = lireFichier("../informations/sources.php");
  const profil        = lireFichier("../utilisateur/profil.php");

  // Une el contenido de todos los archivos en un solo string
  const codePHP = [
    index, navbar, deconnection, login, register,
    process_login, process_register, forum,
    informations, sources, profil
  ]
    .filter(Boolean)
    .join("\n");

  if (!codePHP) {
    return "Je ne peux pas accéder aux informations techniques pour le moment.";
  }

  try {
    // Llamada a la API de OpenAI
    const completion = await openai.chat.completions.create({
      model: "gpt-4o-mini", 
      max_tokens: 150,
      temperature: 0.5,
      top_p: 0.9,
      messages: [
        {
          role: "system",
          content: "Vous êtes un assistant qui guide l'utilisateur dans les fonctionalités du site."
        },
        { role: "system", content: `Code source PHP:\n\n${codePHP}` },
        { role: "user", content: messageUtilisateur },
      ],
    });

    return completion.choices[0].message.content;
  } catch (error) {
    console.error("❌ Error calling OpenAI:", error);
    return "Désolé, une erreur s'est produite lors du traitement.";
  }
}
