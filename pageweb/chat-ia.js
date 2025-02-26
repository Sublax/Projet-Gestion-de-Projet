import OpenAI from "openai";
import dotenv from "dotenv";

dotenv.config(); 

const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY, 
});

async function getAIResponse() {
  try {
    const completion = await openai.chat.completions.create({
      model: "gpt-4o-mini",
      messages: [{ role: "user", content: "Write a haiku about AI" }],
    });

    console.log(completion.choices[0].message.content);
  } catch (error) {
    console.error("Error al llamar a OpenAI:", error);
  }
}

getAIResponse();
