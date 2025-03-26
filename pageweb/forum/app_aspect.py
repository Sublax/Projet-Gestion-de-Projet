from flask import Flask, request, jsonify
from transformers import pipeline

app = Flask(__name__)

# Chargement du modèle NLP pour l'analyse d'aspect et d'opinion
aspect_analyzer = pipeline("zero-shot-classification", model="facebook/bart-large-mnli")

@app.route('/analyser_aspect/', methods=['POST'])
def analyser_aspect():
    data = request.get_json()
    texte = data.get('texte', '')

    aspects = ["sécurité", "climat", "coût de la vie", "infrastructures", "tourisme", "emploi"]
    resultat = aspect_analyzer(texte, candidate_labels=aspects)

    # Prendre l'aspect avec le score le plus élevé
    aspect = resultat['labels'][0]
    score_aspect = resultat['scores'][0]

    # Déterminer si l'opinion est positive ou négative (simple analyse)
    opinion = "positive" if score_aspect >= 0.5 else "négative"

    return jsonify({'aspect': aspect, 'opinion': opinion})

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=8002)
