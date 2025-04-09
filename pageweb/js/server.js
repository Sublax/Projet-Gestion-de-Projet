import express from "express";
import cors from "cors";
import bodyParser from "body-parser";
import { obtenirRéponseIA } from "./chat-ia.js";

const app = express();
const port = 3000;

app.use(cors());
app.use(bodyParser.json());

// Endpoint para manejar el mensaje del usuario
app.post("/api/chat", async (req, res) => {
  try {
    const { message } = req.body;
    if (!message) {
      return res.status(400).json({ error: "No se recibió ningún mensaje." });
    }
    const response = await obtenirRéponseIA(message);
    return res.json({ response });
  } catch (error) {
    console.error("Error en /api/chat:", error);
    return res.status(500).json({ error: "Ocurrió un error procesando el mensaje." });
  }
});

app.listen(port, () => {
  console.log(`Server Node Running in http://localhost:${port}`);
});
