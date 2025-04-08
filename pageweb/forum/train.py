'''
Auteur : Tojolalaina Randriamarotia
Sources : ChatGPT, StackOverflow, modèles Hugging Face 
Objectif : Ce script permet d'entrainer deux modèles de classification de texte
avec Transformers ( modèle pour l'analyse de sentiment et aspect) en utilisant 
les bibliothèques ci-dessous
Sortie : Deux Dossiers : model_sentiment/ et modele_aspect

'''

from datasets import Dataset
from transformers import AutoTokenizer, AutoModelForSequenceClassification, Trainer, TrainingArguments
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, precision_recall_fscore_support, confusion_matrix, ConfusionMatrixDisplay, classification_report
import matplotlib.pyplot as plt
import pandas as pd

#Chargement des données crées avec ChatGPT
df = pd.read_csv("train_pays.csv")

#Préparation des étiquettes
sentiment_map = {"négatif": 0, "positif": 1}
df["label"] = df["label"].map(sentiment_map)
df = df.dropna(subset=["label"])  # Supprimer les lignes incorrectes

aspect_list = sorted(df["aspect"].dropna().unique())
aspect_map = {a: i for i, a in enumerate(aspect_list)}
df["aspect"] = df["aspect"].map(aspect_map)
df = df.dropna(subset=["aspect"])

#Division du dataset pour séparer les données en 80% Entraînement et 20% Test 
# de manière stratifiée ( c-à-d équilibrée par classes)
df_sent_train, df_sent_test = train_test_split(df, test_size=0.2, stratify=df["label"], random_state=42)
df_aspect_train, df_aspect_test = train_test_split(df, test_size=0.2, stratify=df["aspect"], random_state=42)

#Tokenisation des texte 
model_name = "camembert-base"
tokenizer = AutoTokenizer.from_pretrained(model_name)

def tokenize(batch):
    return tokenizer(batch["texte"], padding=True, truncation=True)

#Création des datasets compatibles avec HuggingFace
dataset_sentiment = {
    "train": Dataset.from_pandas(df_sent_train[["texte", "label"]]),
    "test": Dataset.from_pandas(df_sent_test[["texte", "label"]]),
}
dataset_aspect = {
    "train": Dataset.from_pandas(df_aspect_train[["texte", "aspect"]].rename(columns={"aspect": "label"})),
    "test": Dataset.from_pandas(df_aspect_test[["texte", "aspect"]].rename(columns={"aspect": "label"})),
}

dataset_sentiment = {k: v.map(tokenize, batched=True) for k, v in dataset_sentiment.items()}
dataset_aspect = {k: v.map(tokenize, batched=True) for k, v in dataset_aspect.items()}

#Définition des métriques pour retourner:
# les mesures de performances pour chaque epoch dont : la précision
#le rappel, la F1 - Score
# Accuracy
def compute_metrics(eval_pred):
    logits, labels = eval_pred
    predictions = logits.argmax(-1)
    precision, recall, f1, _ = precision_recall_fscore_support(labels, predictions, average='weighted')
    acc = accuracy_score(labels, predictions)
    return {
        "accuracy": round(acc, 3),
        "precision": round(precision, 3),
        "recall": round(recall, 3),
        "f1": round(f1, 3)
    }

#Chargement du modèle de base qui est camemBERT
model_sent = AutoModelForSequenceClassification.from_pretrained(model_name, num_labels=2)
model_asp = AutoModelForSequenceClassification.from_pretrained(model_name, num_labels=len(aspect_list))

#Paramètres d'entraînement
training_args_sent = TrainingArguments(
    output_dir="./model_sentiment",
    evaluation_strategy="epoch",
    num_train_epochs=4,
    per_device_train_batch_size=8,
    per_device_eval_batch_size=8,
    weight_decay=0.01,
    logging_dir="./logs_sent"
)

training_args_asp = TrainingArguments(
    output_dir="./model_aspect",
    evaluation_strategy="epoch",
    num_train_epochs=4,
    per_device_train_batch_size=8,
    per_device_eval_batch_size=8,
    weight_decay=0.01,
    logging_dir="./logs_asp"
)

#entrainement avec Hugging Face Trainer
trainer_sent = Trainer(
    model=model_sent,
    args=training_args_sent,
    train_dataset=dataset_sentiment["train"],
    eval_dataset=dataset_sentiment["test"],
    tokenizer=tokenizer,
    compute_metrics=compute_metrics
)

trainer_asp = Trainer(
    model=model_asp,
    args=training_args_asp,
    train_dataset=dataset_aspect["train"],
    eval_dataset=dataset_aspect["test"],
    tokenizer=tokenizer,
    compute_metrics=compute_metrics
)

#Point de lancement de l'entraînement
trainer_sent.train()
trainer_asp.train()

#Evaluation des modèles avec un rapport de classification et la matrice de confusion
preds_sent = trainer_sent.predict(dataset_sentiment["test"])
y_true_sent = preds_sent.label_ids
y_pred_sent = preds_sent.predictions.argmax(axis=-1)

print("\n📊 Rapport de classification : Sentiment")
print(classification_report(y_true_sent, y_pred_sent, target_names=["négatif", "positif"]))

cm_sent = confusion_matrix(y_true_sent, y_pred_sent)
disp_sent = ConfusionMatrixDisplay(confusion_matrix=cm_sent, display_labels=["négatif", "positif"])
disp_sent.plot(cmap="Blues")
plt.title("Matrice de confusion - Sentiment")
plt.show()


preds_asp = trainer_asp.predict(dataset_aspect["test"])
y_true_asp = preds_asp.label_ids
y_pred_asp = preds_asp.predictions.argmax(axis=-1)

print("\n📊 Rapport de classification : Aspect")
print(classification_report(y_true_asp, y_pred_asp, target_names=[k for k in aspect_map.keys()]))

cm_asp = confusion_matrix(y_true_asp, y_pred_asp)
disp_asp = ConfusionMatrixDisplay(confusion_matrix=cm_asp, display_labels=[k for k in aspect_map.keys()])
disp_asp.plot(cmap="Greens", xticks_rotation=45)
plt.title("Matrice de confusion - Aspect")
plt.show()

#Sauvegarde des modèles fine-tunés au format Hugging Face
model_sent.save_pretrained("./model_sentiment")
tokenizer.save_pretrained("./model_sentiment")

model_asp.save_pretrained("./model_aspect")
tokenizer.save_pretrained("./model_aspect")



