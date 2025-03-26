from fastapi import FastAPI
from pydantic import BaseModel
from transformers import pipeline

# Créer l'application FastAPI
app = FastAPI()

# Charger le modèle de sentiment
analyser_sentiment = pipeline("sentiment-analysis", model="nlptown/bert-base-multilingual-uncased-sentiment")

# Modèle de données pour les avis
class Avis(BaseModel):
    texte: str

@app.post("/analyser_avis/")
async def analyser_avis(avis: Avis):
    resultat = analyser_sentiment(avis.texte)[0]
    label = resultat['label']
    # Convertir les labels "1 star" à "5 stars" en "positif" ou "négatif"
    sentiment = "positif" if label in ["4 stars", "5 stars"] else "négatif"
    return {"label": sentiment, "score": resultat['score']}
