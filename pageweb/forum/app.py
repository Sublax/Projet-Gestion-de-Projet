'''
Auteur : Tojolalaina Randriamarotia
Sources : ChatGPT, StackOverflow, modèle Hugging Face 
Objectif : Script python basé sur FastAPI permettant de créer un serveur
API local pour l'application de 3 différents traitements : 
 - Filtrage à l'aide des mots interdits, 
 - Analyse de toxicité
 - Analyse de sentiment
 - Analyse d'aspect
Sortie : JSON 

'''
from fastapi import FastAPI
from pydantic import BaseModel
from transformers import pipeline
import uvicorn
import re

#Créatioin de l'application FastAPI
app = FastAPI()

# Chargement des modèles pré-entrainés
pipe_sent = pipeline("text-classification", model="./model_sentiment", tokenizer="./model_sentiment")
pipe_asp = pipeline("text-classification", model="./model_aspect", tokenizer="./model_aspect")
pipe_tox = pipeline("text-classification", model="unitary/toxic-bert")

# Dictionnaire des différents aspects
aspect_map = {
    0: "accueil", 1: "climat", 2: "coût de la vie", 3: "culture",
    4: "emploi", 5: "environnement", 6: "gastronomie", 7: "infrastructures",
    8: "logement", 9: "santé", 10: "sécurité", 11: "tourisme"
}

# Structure attendue en entrée dans la requête POST 
class Avis(BaseModel):
    texte: str

#Point d'entrée de l'API
@app.post("/analyser")
#Cette fonction s'active quand on fait un POST sur /analyser
#Reçoit un texte en entrée et retourne : son aspect, son sentiment, s'il est toxique
def analyser_texte(avis: Avis):
    texte = avis.texte.lower()
    
    mots_interdits = [
        r"\bconnard\b", r"\bconne\b", r"\babruti\b", r"\bdébile\b", r"\bcul\b", r"\bqueue\b",
    r"\bcrétin\b", r"\bgros con\b", r"\bgrosse conne\b",
    r"\benculé\b", r"\bsalope\b", r"\bpute\b", r"\bpétasse\b", r"\bpute à fric\b",
    r"\bpoufiasse\b", r"\bgrognasse\b", r"\bfdp\b", r"\bfils de pute\b", r"\bbatard\b",
    r"\benfoiré\b", r"\bsac à merde\b", r"\bmerdeux\b", r"\bcouillon\b",r"\bmerde\b",r"\bputain\b",r"\benculé\b",r"\bfdp\b",r"\bpute\b",r"\bgrosse\b",
    r"\bchibre\b",r"\barabe\b",r"\bnoire\b",r"\bnoir\b",r"\bchintok\b",r"\bqueue\b",r"\bchaudasse\b",r"\bsale\b",r"\bbabtou\b",r"\bbouffon\b",

    r"\bje vais te tuer\b", r"\bcrève\b", r"\btu mérites de mourir\b", r"\bnique ta mère\b",
    r"\bva crever\b", r"\bje vais te niquer\b", r"\bferme ta gueule\b",
    r"\bta gueule\b", r"\bva te faire foutre\b", r"\bje vais t'éclater\b", r"\bje vais t'égorger\b",

    r"\bpd\b", r"\bpédé\b", r"\btapette\b", r"\bgouine\b", r"\bsodomite\b",
    r"\bbite\b", r"\bchatte\b", r"\bniquer\b", r"\bbaiser\b", r"\bbranleur\b", r"\bbranleuse\b",

    r"\bbougnoule\b", r"\bnègre\b", r"\byoupin\b", r"\bnazi\b", r"\bface de rat\b",
    r"\brace de merde\b", r"\bsale arabe\b", r"\bsale noir\b", r"\bsale blanc\b",
    r"\bsale juif\b", r"\bmusulman de merde\b", r"\bputain de chrétien\b",

    r"\bhomophobe\b", r"\btransphobe\b", r"\bgros porc\b", r"\bsale gros\b",
    r"\bmongolien\b", r"\battardé\b", r"\bhandicapé mental\b", r"\bzingaro\b",
    r"\bmacaca\b", r"\braton\b",

    r"\bntm\b", r"\bzgeg\b", r"\btg\b", r"\btg sale\b", r"\benculé va\b", r"\bpute va\b"]


    for motif in mots_interdits:
        if re.search(motif, texte):
            return {
                "toxique": True,
                "raison": f"mot interdit détecté : {motif}"
            }
        
    # Analyse de toxicité
    tox = pipe_tox(texte)[0]
    toxique = tox['label'] == 'LABEL_1'
    tox_score = round(tox['score'], 3)

    # Si le texte est toxique, on ne fait pas le reste
    if toxique:
        return {
            "toxique": True,
            "score_toxicite": tox_score,
            "raison": "modèle de détection"
        }

    # Analyse  du sentiment
    sent = pipe_sent(texte)[0]
    sentiment = "positif" if sent["label"] == "LABEL_1" else "négatif"
    sent_score = round(sent["score"], 3)

    # Analyse de l'aspect du commentaire
    asp = pipe_asp(texte)[0]
    asp_index = int(asp["label"].replace("LABEL_", ""))
    aspect = aspect_map.get(asp_index, "inconnu")
    asp_score = round(asp["score"], 3)

    # Résultat final retourné à l'utilisateur 
    return {
        "toxique": False,
        "sentiment": sentiment,
        "aspect": aspect,
        "scores": {
            "sentiment": sent_score,
            "aspect": asp_score,
            "toxicite": tox_score
        }
    }

# Lancement du serveur en local
if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=8000)
