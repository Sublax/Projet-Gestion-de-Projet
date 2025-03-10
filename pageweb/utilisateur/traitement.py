#===================================
# A FAIRE ; 
# -> Passer de R2 à Rn pour les variables (ttes les inclure)
# -> Faire le clustering qu'une seule fois et pas à chaque fois
# -> Erreur quand pays sans données sur un domaine
# -> Renvoyer le résultat
# -> Ne plus passer par l'execution manuel du python mais plutôt par l'execution via le site web du script. Puis renvoyer le résultat.
#===================================
import pymysql
from flask import Flask, request, jsonify,send_file
from flask_cors import CORS 
from sklearn.cluster import KMeans
from sklearn.impute import SimpleImputer
import matplotlib.pyplot as plt
from scipy.spatial.distance import cdist
import numpy as np

connection = pymysql.connect(host="localhost", port=3306, user="root", passwd="root", database="bdprojet")
cursor = connection.cursor()

app = Flask(__name__)
CORS(app)

@app.route('/receive_json', methods=['POST'])
def receive_json():
    # Récupère le JSON envoyé dans le corps de la requête
    data = request.get_json()
    if not data:
        return jsonify({"error": "Aucun JSON reçu"}), 400
    
    # Affiche dans la console le JSON reçu
    print("[DEBUG] JSON reçu :", data)
    print("[DEBUG] La liste reçue : ",list(data.values()))
    pays_selected = list(data.values())[0]
    print(pays_selected[0])
    #Prendre la dernière année : 

    #data_x = []
    #data_y = []
    #data_z = []
    #data_diet = []
    DATA = []
    REQUEST_TABLE= """
                SELECT AVG(score_bonheur), AVG(generosite),
                AVG((corruption_politique * 100)), 
                AVG((cleanfuelandcookingequipment)), MAX(costhealthydiet),
                MAX(crime.taux),
                AVG(taux_classe_primaire),AVG(taux_classe_secondaire)
                FROM bonheur 
                """


    JOINT_TABLE = """
                LEFT JOIN pays ON bonheur.id_pays = pays.id_pays
                LEFT JOIN corruption ON corruption.id_pays = pays.id_pays 
                LEFT JOIN agroalimentaire ON agroalimentaire.id_pays = pays.id_pays 
                LEFT JOIN crime ON crime.id_pays = pays.id_pays
                LEFT JOIN education ON education.id_pays = pays.id_pays
                LEFT JOIN meteo ON meteo.id_pays = pays.id_pays
                
                """

    #cursor.execute("SELECT pays.nom_pays,AVG(rang_bonheur),AVG((corruption_politique * 100)) FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays AND pays.nom_pays <> '" + pays_selected[0] + "' INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays")
    cursor.execute(REQUEST_TABLE + JOINT_TABLE +
                   """
                   WHERE pays.nom_pays <> '""" + pays_selected[0] + """' 
                   GROUP BY pays.nom_pays""")
    #On prend les DATA ENTières
    DATA_ENT = cursor.fetchall()
    print("DATA ENTIERE AV. TRANSFO : ",DATA_ENT[0])

    imputer = SimpleImputer(strategy='mean')
    DATA_ENT = imputer.fit_transform(DATA_ENT)

    print("DATA ENTIERE APR. TRANSFO : ",DATA_ENT[0])
    #Ici on fait en sorte qu'au lieu d'ajouter des listes à la main, ça les créer automatiquement en tant que sous liste
    REQUEST_LIST = list(DATA_ENT)
    NBRE_LIST = len(REQUEST_LIST[0])
    for i in range(NBRE_LIST):
        DATA.append([])
    for data in REQUEST_LIST:
        for ite_data in range(len(data)):
            DATA[ite_data].append(data[ite_data])
        

    #On créer le plot de la méth du coude
    inertias = []
    for i in range(1,11):
        kmeans = KMeans(n_clusters=i, random_state=1)
        kmeans.fit(DATA_ENT)
        inertias.append(kmeans.inertia_)

    #plt.plot(range(1,11), inertias, marker='o')
    #plt.title(f'Elbow method X = {NBRE_LIST}')
    #plt.xlabel('Number of clusters')
    #plt.ylabel('Inertie')
    #plt.savefig(f"/home/sublax/Documents/L3_MIASHS/S2/GestionProjet/coude_X{NBRE_LIST}.png")
    #plt.close() 

    #Puis on réalise les KMEANS, même si ici cela ne servait qu'en 2D, c'est utile pour voir l'évolution du projet et des groupes justement.
    kmeans = KMeans(n_clusters=3)
    kmeans.fit(DATA_ENT)
    plt.scatter(DATA[0], DATA[1], c=kmeans.labels_)
    #print("DATA X  : ",data_x)
    #print("DATA Y : ",data_y)
    #plt.title(f'Bonheur/Corruption politique X = {NBRE_LIST}')
    #plt.xlabel('Moyenne du bonheur')
    #plt.ylabel('Échelle de corruption')
    #plt.savefig(f"/home/sublax/Documents/L3_MIASHS/S2/GestionProjet/cluster_X{NBRE_LIST}.png")
    #plt.close()

    cursor.execute(REQUEST_TABLE + JOINT_TABLE + 
                """
                WHERE pays.nom_pays = '""" + pays_selected[0] + """'
                GROUP BY pays.nom_pays """)
    #Si on reçoit aucune donnée :
    requete = cursor.fetchall()
    print(f"////// PAYS SELECTIONNE : {pays_selected[0]} ")
    print(f"REQUETE AVANT MODIF : {requete[0]}")
    #requete = imputer.transform(requete)
    print(f"REQUETE APRES MODIF : {requete[0]}")
    #print(f"[DEBUG] Requête : {requete}")
    if len(requete) == 0:
        print("[ERREUR] : Len(requete) = ",len(requete))
        return jsonify({"status": "failed", "message": "Des données sont manquantes"}), 404

    
    selected_country = np.array(requete)
    
    #Prédiction du groupe auquel appartient le choix de l'user :
    cluster = kmeans.predict(selected_country)

    #On extrait les coordonnées des pts du même cluster :
    indices_cluster = np.where(kmeans.labels_ == cluster[0])[0]

    #On prend tous pts du cluster
    points_cluster = np.array(DATA_ENT)[indices_cluster]

    # On calcule les distances entre la donnée et tous les points du cluster
    distances = cdist(selected_country, points_cluster)

    #Et on trouve le voisin le plus proche
    index_voisin_plus_proche = np.argmin(distances)
    print("VOISIN PLUS PROCHE :",index_voisin_plus_proche)
    voisin_plus_proche = points_cluster[index_voisin_plus_proche]
    print(f"Le voisin le plus proche est : {voisin_plus_proche}")
    cursor.execute("SELECT pays.nom_pays,AVG(score_bonheur) as bon,AVG((corruption_politique * 100)) as corrupt FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays HAVING AVG(score_bonheur) =" + str(voisin_plus_proche[0]))
    country_predict = cursor.fetchone()
    print("Country predicted : ",country_predict[0])
    
    return jsonify({"status": "success", "message": "Données reçues avec succès", "data": country_predict[0]}), 200
app.run(debug=True, port=5000)
    
    
    