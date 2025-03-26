import "dotenv/config";
import OpenAI from "openai";
import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

// Pour que __dirname fonctionne en ESM (type: "module")
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Instanciation du client OpenAI avec l'API key
const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY,
});

// Fonction pour lire un fichier avec un chemin relatif à ce fichier JS
function lireFichier(cheminRelatif) {
  const cheminComplet = path.join(__dirname, cheminRelatif);
  try {
    if (!fs.existsSync(cheminComplet)) {
      console.error(`❌ Fichier introuvable : ${cheminComplet}`);
      return null;
    }
    return fs.readFileSync(cheminComplet, "utf-8");
  } catch (error) {
    console.error("❌ Erreur de lecture du fichier :", error);
    return null;
  }
}

// Fonction principale pour obtenir la réponse de l'IA
export async function obtenirRéponseIA(messageUtilisateur) {
  // Lecture des fichiers PHP
  const index           = lireFichier("../index.php");
  const navbar          = lireFichier("../navbar.php");
  const deconnection    = lireFichier("../connexion/deconnection.php");
  const login           = lireFichier("../connexion/login.php");
  const register        = lireFichier("../connexion/register.php");
  const process_login   = lireFichier("../connexion/process_login.php");
  const process_register= lireFichier("../connexion/process_register.php");
  const forum           = lireFichier("../forum/forum.php");
  const informations    = lireFichier("../informations/informations.php");
  const sources         = lireFichier("../informations/sources.php");
  const profil          = lireFichier("../utilisateur/profil.php");

  // Concaténer le contenu de tous les fichiers PHP dans une chaîne
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

  // Filtrage préliminaire : bloquer les questions non autorisées
  const motsInvalides = /(recette|tarte|sport|football|musique|film|politique|météo|sprinteur|etc)/i;
  if (motsInvalides.test(messageUtilisateur)) {
    return "Désolé, je ne peux répondre qu'aux questions relatives à l'utilisation de ce site web.";
  }

  // Prompt système très strict
  const systemPrompt = `
Vous êtes un assistant technique STRICTEMENT RESTREINT.

Règles OBLIGATOIRES (sans exception) :

- Vous devez répondre UNIQUEMENT en citant directement le CODE PHP fourni ci-dessous.
- Vous ne pouvez JAMAIS inventer, suggérer ou ajouter d’autres informations.
- Si la réponse n'est pas explicitement et précisément disponible dans le CODE PHP fourni, répondez TOUJOURS exactement la phrase suivante, sans AUCUNE exception :
"Désolé, je ne peux répondre qu'aux questions relatives à l'utilisation de ce site web."

Voici le CODE PHP à utiliser exclusivement comme source :
${codePHP}

FIN DU CODE PHP. Aucune autre information que ce code n’est valide.
  `;

  try {
    // Appel à l'API OpenAI
    const completion = await openai.chat.completions.create({
      model: "gpt-4o-mini",
      max_tokens: 80,
      temperature: 0,
      top_p: 0.9,
      messages: [
        { role: "system", content: systemPrompt },
        { role: "user", content: messageUtilisateur },
      ],
    });

    let reponseIA = completion.choices[0].message.content;

    // Filtrage postérieur de la réponse
    const motsInvalidesDansReponse = /(recette|tarte|ingrédients|sport|musique|film|préparation|etc)/i;
    if (motsInvalidesDansReponse.test(reponseIA)) {
      reponseIA = "Désolé, je ne peux répondre qu'aux questions relatives à l'utilisation de ce site web.";
    }

    return reponseIA;
  } catch (error) {
    console.error("❌ Erreur lors de l'appel à OpenAI:", error);
    return "Désolé, une erreur s'est produite lors du traitement.";
  }
}
