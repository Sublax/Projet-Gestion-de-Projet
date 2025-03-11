#===================================
# A FAIRE ; 
# -> Passer de R2 à Rn pour les variables (ttes les inclure) (DONE)
# -> Faire le clustering qu'une seule fois et pas à chaque fois (DONE)
# --> Le CSV oublie une ligne car il a été une fois sans Chili, il faudrait prendre tous les pays et enlever la ligne correspondant au pays sélectionné.
# -> Renvoyer le résultat
# -> Attention aux injections SQL
# -> Ne plus passer par l'execution manuel du python mais plutôt par l'execution via le site web du script. Puis renvoyer le résultat.
#===================================
import pymysql
from flask import Flask, request, jsonify, redirect, url_for
from flask_cors import CORS 
from sklearn.cluster import KMeans
from sklearn.impute import SimpleImputer
import matplotlib.pyplot as plt
from scipy.spatial.distance import cdist
import numpy as np
import pandas as pd
from pathlib import Path #On peut l'optimiser en utilisant try: except: mais méthode conseillé par geeksforgeeks.org


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
    req = Path("./REQUETE_ENT.csv")
    if not req.exists():
        create_data(pays_selected[0])
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
    DATA_ENT = pd.read_csv(req)
    
    #Complétion par moyenne global :
    imputer = SimpleImputer(strategy='mean')
    DATA_ENT = imputer.fit_transform(DATA_ENT)

    print("DATA ENTIERE APR. TRANSFO : ",DATA_ENT[0])
    
    # /////////////////////////////////////////////////////////////////////////////////////
    # /////////////////////////////////// VISUALISATION ///////////////////////////////////
    # /////////////////////////////////////////////////////////////////////////////////////
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
    # /////////////////////////////////////////////////////////////////////////////////////
    # ///////////////////////////////// FIN -VISUALISATION ////////////////////////////////
    # /////////////////////////////////////////////////////////////////////////////////////
    
    cursor.execute(REQUEST_TABLE + JOINT_TABLE + 
                """
                WHERE pays.nom_pays = '""" + pays_selected[0] + """'
                GROUP BY pays.nom_pays """)
    requete = cursor.fetchall()
    
    #Si le pays n'est pas trouvé : 
    if len(requete) == 0:
        print("[ERREUR] : Len(requete) = ",len(requete))
        return jsonify({"status": "failed", "message": "Des données sont manquantes"}), 404

    #On retire les NoneType en complétant par la moyenne des données : 
    requete = imputer.transform(requete)
    
    #Attention, il faut enlever le pays sélectionner dans toutes les données : 
    #print("[DEBUG --] Data entière : ",DATA_ENT)
    #print("[DEBUG --] Data requete :",requete[0])
    #Ici, on va chercher la récurrence qui existe déjà pour savoir quel pays enlevé de nos données.
    #(ON AURAIT PU FAIRE PAR NOM DE PAYS ET ENSUITE RETIRER LES NOMS DE PAYS)
    found = False
    for i in range(len(DATA_ENT)):
        if not found:
            if np.allclose(DATA_ENT[i],requete[0],atol=1e-8): #ATTENTION ICI ON FAIT UN RAPPROCHEMENT PAR 1e-8 !!!!
                DATA_ENT = np.delete(DATA_ENT,i,axis=0) #On supprime l'occurence
                found = True
        else:
            break
    #=======================
    
    #=======================
    #   Méthode des KMEANS
    #=======================
    kmeans = KMeans(n_clusters=3)
    kmeans.fit(DATA_ENT)
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
    
    #Pour retrouver le pays qui est prédit (grâce au score bonheur)
    cursor.execute("SELECT pays.nom_pays,AVG(score_bonheur) as bon,AVG((corruption_politique * 100)) as corrupt FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays HAVING AVG(score_bonheur) =" + str(voisin_plus_proche[0]))
    country_predict = cursor.fetchone()
    print("Country predicted : ",country_predict[0])
    
    return jsonify({"status": "success", "message": "Données reçues avec succès", "data": country_predict[0]}), 200







def create_data():
    """
    Fonction qui créer la base de donnée permettant d'effectuer les KMEAN
    ça évite surtout de se taper une grosse requête à chaque fois que l'utilisateur veut avoir sa prédiction.
    """
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
                GROUP BY pays.nom_pays
                """

    cursor.execute(REQUEST_TABLE + JOINT_TABLE)
    
    #On prend les DATA ENTières (elles nous serviront de complétion par moyenne)
    DATA_ENT = cursor.fetchall()
    print("DATA ENTIERE AV. TRANSFO : ",DATA_ENT[0])
    columns = ['avg_score_bonheur', 'avg_generosite', 'avg_corruption', 'avg_cleanfuelandcookingequipment', 
           'max_costhealthydiet', 'max_crime_taux', 'avg_taux_classe_primaire', 'avg_taux_classe_secondaire']

    # Crée un DataFrame pandas à partir des données récupérées
    df = pd.DataFrame(DATA_ENT, columns=columns)

    # Enregistre le DataFrame dans un fichier CSV
    df.to_csv('./REQUETE_ENT.csv', index=False)
            

app.run(debug=True, port=5000)
    
    
    