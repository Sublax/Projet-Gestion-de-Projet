from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from transformers import pipeline

# Créer l'application FastAPI
app = FastAPI()

# Charger le modèle de détection de toxicité
#print("Chargement du modèle de détection de toxicité...")
analyse_toxicite = pipeline("text-classification", model="unitary/toxic-bert")

# Modèle de données pour les avis
class Avis(BaseModel):
    texte: str

@app.post("/detecter_toxicite/")
async def detecter_toxicite(avis: Avis):
    try:
        # Analyser la toxicité du texte
        resultat = analyse_toxicite(avis.texte)[0]
        label = resultat['label']  # "LABEL_0" = Non offensif, "LABEL_1" = Offensif
        score = resultat['score']

        # Déterminer la nature du contenu
        if label == "LABEL_1":
            return {"toxique": True, "score": score}
        else:
            return {"toxique": False, "score": score}

    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# Lancer le serveur avec : uvicorn nom_du_fichier:app --reload
