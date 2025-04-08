from transformers import pipeline

# Charger les modèles fine-tunés (chemins relatifs depuis le dossier actuel)
pipe_sent = pipeline("text-classification", model="./model_sentiment", tokenizer="camembert-base")
pipe_asp = pipeline("text-classification", model="./model_aspect", tokenizer="camembert-base")

# Phrase test — à remplacer dynamiquement ou par saisie utilisateur
texte = input("Entrez un avis utilisateur :\n")

# Prédictions
pred_sent = pipe_sent(texte)[0]
pred_asp = pipe_asp(texte)[0]

# Interprétation
sentiment = "positif" if pred_sent["label"] == "LABEL_1" else "négatif"
aspect_index = int(pred_asp["label"].replace("LABEL_", ""))  # ex: LABEL_4 → 4

# Si besoin : le même dictionnaire que dans train.py
aspect_map = {
    0: "accueil", 1: "climat", 2: "coût de la vie", 3: "culture",
    4: "emploi", 5: "environnement", 6: "gastronomie", 7: "infrastructures",
    8: "logement", 9: "santé", 10: "sécurité", 11: "tourisme"
}

# Résultat final
aspect = aspect_map.get(aspect_index, "inconnu")

# Affichage
print("\n📝 Texte :", texte)
print(f"📌 Aspect détecté    : {aspect} (score={pred_asp['score']:.2f})")
print(f"💬 Sentiment détecté : {sentiment} (score={pred_sent['score']:.2f})")
