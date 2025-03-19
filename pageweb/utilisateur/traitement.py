#===================================
# A FAIRE ; 
# -> Passer de R2 à Rn pour les variables (ttes les inclure) (DONE)
# -> Faire le clustering qu'une seule fois et pas à chaque fois (DONE)
# --> Le CSV oublie une ligne car il a été une fois sans Chili, il faudrait prendre tous les pays et enlever la ligne correspondant au pays sélectionné. (DONE)
# -> Renvoyer le résultat
# -> Attention aux injections SQL
# -> Ne plus passer par l'execution manuel du python mais plutôt par l'execution via le site web du script. Puis renvoyer le résultat.
# -> Faire le barycentre des clusters pour le choix des trois pays et comparer à la moyenne des trois pays
# -> Faire en sorte que s'il n'y a pas 3 pays avec le même clusters bah qu'il le mette avec le cluster le plus proche
#===================================
import pymysql
from flask import Flask, request, jsonify
from flask_cors import CORS 
from sklearn.cluster import KMeans
from sklearn.impute import SimpleImputer
import matplotlib.pyplot as plt
from scipy.spatial.distance import cdist
import numpy as np
import pandas as pd
from pathlib import Path #On peut l'optimiser en utilisant try: except: mais méthode conseillé par geeksforgeeks.org
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import silhouette_score
import random
from sklearn.decomposition import PCA
import plotly.express as px


connection = pymysql.connect(host="localhost", port=3306, user="root", passwd="root", database="bdprojet")
cursor = connection.cursor()

app = Flask(__name__)
CORS(app)
REQUEST_TABLE= """
SELECT 
    pays.id_pays,
    a.avg_score_bonheur, 
    a.avg_generosite, 
    b.avg_liberte_express, 
    b.avg_corrupt,
    c.taux_crime,
    d.avg_GDP,
    d.avg_pop,
    avg_educ_prim,
    avg_educ_sec,
    avg_hivermin,
    avg_etemax,
    avg_imp, avg_pimp,
    last_espvie,
    avg_migration,
    avg_transport,
    avg_ssemploif,avg_ssemploih
                """

JOINT_TABLE = """
FROM 
	(SELECT id_pays,AVG(score_bonheur) as avg_score_bonheur, AVG(generosite) as avg_generosite FROM bonheur GROUP BY id_pays) as a 
LEFT JOIN 
	(SELECT id_pays,AVG(corruption.liberte_expression) as avg_liberte_express, AVG(corruption.corruption_politique) as avg_corrupt FROM corruption GROUP BY id_pays) as b ON a.id_pays = b.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(crime.taux) as taux_crime FROM crime GROUP BY id_pays) as c ON a.id_pays = c.id_pays
LEFT JOIN
	(SELECT id_country, AVG(economie.`Gross Domestic Product (GDP)`) as avg_GDP, AVG(economie.Population) as avg_pop FROM economie GROUP BY id_country) as d ON d.id_country = a.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(education.taux_classe_primaire) as avg_educ_prim, AVG(education.taux_classe_secondaire) as avg_educ_sec FROM education GROUP BY id_pays) as e ON e.id_pays = a.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(meteo.hiver_tmin) as avg_hivermin, AVG(meteo.ete_tmax) as avg_etemax FROM meteo GROUP BY id_pays) as f ON f.id_pays = a.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(religion.important) as avg_imp, AVG(religion.pas_important) as avg_pimp FROM religion GROUP BY id_pays) as g ON g.id_pays = a.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(sante.esperance_vie) as last_espvie FROM sante GROUP BY id_pays) as h ON h.id_pays = a.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(tourisme.inbound_arrival) as avg_migration FROM tourisme GROUP BY id_pays) as i ON i.id_pays = a.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(transport.taux_acces_transport) as avg_transport FROM transport GROUP BY id_pays) as j ON j.id_pays = a.id_pays
LEFT JOIN
	(SELECT id_pays, AVG(travail.sans_emploi_femme) as avg_ssemploif, AVG(travail.sans_emploi_homme) as avg_ssemploih FROM travail GROUP BY id_pays) as k ON k.id_pays = a.id_pays
INNER JOIN pays ON a.id_pays = pays.id_pays
                """
@app.route('/receive_json', methods=['POST'])
def receive_json():
    """
    Fonction principale qui traite les Kmeans
    """
    
    # //////////////////////////////////////////////////////////////////////////////////////
    # /////////////////////////////////// PRÉ-TRAITEMENT ///////////////////////////////////
    # //////////////////////////////////////////////////////////////////////////////////////
    # Récupère le JSON envoyé dans le corps de la requête
    data = request.get_json()
    if not data:
        return jsonify({"error": "Aucun JSON reçu"}), 400
    
    # Affiche dans la console le JSON reçu
    print("[DEBUG] JSON reçu :", data)
    pays_selected = list(data.values())[0]
    
    #On vérifie si les données existent : 
    req = Path("./REQUETE_ENT.csv")
    if not req.exists():
        create_data()

    #On reprend les données traitées (complétions + standardisation): 
    DATA_ENT = pd.read_csv(req)
    #DATA_ENT = np.array(DATA_ENT)
    
    # /////////////////////////////////////////////////////////////////////////////////////
    # /////////////////////////////////// VISUALISATION ///////////////////////////////////
    # /////////////////////////////////////////////////////////////////////////////////////
    #Ici on fait en sorte qu'au lieu d'ajouter des listes à la main, ça les créer automatiquement en tant que sous liste
    #create_visu(DATA_ENT)
    
    # /////////////////////////////////////////////////////////////////////////////////////
    # ///////////////////////////////// PARTIE KMEANS /////////////////////////////////////
    # /////////////////////////////////////////////////////////////////////////////////////
    
    
    #cursor.execute(REQUEST_TABLE + JOINT_TABLE + 
    #            """ 
    #            WHERE pays.nom_pays = '""" + pays_selected[0] + """' 
    #            """)
    #requete = cursor.fetchall()


    #PROBLEME CORRESPONDANCE PAYS => ID_PAYS
    #On récupère l'ID du pays sélectionné : 
    cursor.execute("SELECT id_pays FROM pays WHERE pays.nom_pays = '" + pays_selected[0] + "'")
    id_pays_selected = cursor.fetchone()
    if id_pays_selected == None:
        return jsonify({"status": "failed", "message": "Aucun pays sous ce nom dans la BDD"}), 401
    id_pays_selected = id_pays_selected[0]
    print("Id du pays sélectionné : ",id_pays_selected)
    
    
    #Trouver une correspondace entre le pays et la liste DATA_ENT
    requete = (DATA_ENT.loc[DATA_ENT['id_pays'] == id_pays_selected])
    
    #On enlève la colonne id_pays
    requete = requete.loc[:,requete.columns != 'id_pays'].to_numpy()
    
    #Si le pays n'est pas trouvé : 
    if len(requete) == 0:
        print("[ERREUR] : Len(requete) = ",len(requete))
        return jsonify({"status": "failed", "message": "Des données sont manquantes"}), 404

    #On enlève le pays sélectionné par l'utilisateur pour éviter que ça recommande le même : 
    DATA_ENT = (DATA_ENT[DATA_ENT.id_pays != id_pays_selected])
    # On retire la colonne id_pays
    DATA_ENT = DATA_ENT.loc[:,DATA_ENT.columns != 'id_pays'].to_numpy()
    
    #=======================
    #   Méthode des KMEANS
    #=======================
    kmeans = KMeans(n_clusters=9, init="k-means++")
    kmeans.fit(DATA_ENT)
    print(f"LE PAYS CHOISI EST : {pays_selected}")

    print("Voici les données de la requête : ",requete)
    #Prédiction du groupe auquel appartient le choix de l'user :
    cluster = kmeans.predict(requete)
    print("Cluster trouvé : ",cluster)
    print(kmeans.labels_)
    print("LOngueur labels : ",len(kmeans.labels_))
    #On extrait les coordonnées des pts du même cluster :
    indices_cluster = np.where(kmeans.labels_ == cluster[0])[0]
    print("INDICES CLUSTER :", indices_cluster)
    
    #On sélectionne 3 pays aléatoirement dans ce cluster : 
    random_selected = random.sample(list(indices_cluster),3)

    pays_predicted = []
    for i in range(len(random_selected)):
    #REPOSE SUR UNE HYPOTHÈSE : 
    #   - Les requêtes sont effectués dans le même ordre que l'id pays inscrit ici.
        #cursor.execute("SELECT pays.nom_pays,AVG(score_bonheur) as bon,AVG((corruption_politique * 100)) as corrupt FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays HAVING AVG(score_bonheur) =" + str(points_cluster[0]))
        cursor.execute("SELECT pays.nom_pays FROM pays WHERE id_pays="+ str(random_selected[i]))
        pays_predicted.append(cursor.fetchone())
    print("Country predicted : ",pays_predicted)
    
    pca = PCA(n_components=2)
    components = pca.fit_transform(DATA_ENT)
    plt.figure(figsize=(8, 6))
    plt.scatter(components[:, 0], components[:, 1], alpha=0.8, c=kmeans.labels_, edgecolors='k',cmap='tab10')

    variance_expl = pca.explained_variance_ratio_ * 100
    # Ajouter des labels
    plt.xlabel('Composante principale 1 (' + str(round(variance_expl[0],2)) + '%)')
    plt.ylabel('Composante principale 2 (' + str(round(variance_expl[1],2)) + '%)')
    plt.title('Projection PCA des données')
    plt.grid(True)
    plt.savefig(f"/home/sublax/Documents/L3_MIASHS/S2/GestionProjet/PCA_XD.png")
    plt.close()
    return jsonify({"status": "success", "message": "Données reçues avec succès", "data": pays_predicted}), 200





def create_visu(DATA_ENT):
    """
    Fonction qui prend en entrée le tableau des données et créer des visuels en fonction.
    Notamment Graph Coude + Score Silhouette
    
    Sortie : 
        - Score Silhouette
        - Graphique coude
    
    """
    DATA = []
    REQUEST_LIST = list(DATA_ENT)
    NBRE_LIST = len(REQUEST_LIST[0])
    for i in range(NBRE_LIST):
        DATA.append([])
    for data in REQUEST_LIST:
        for ite_data in range(len(data)):
            DATA[ite_data].append(data[ite_data])
        
    #On créer le plot de la méth du coude + silhouette
    inertias = []
    for i in range(2,40):
        kmeans = KMeans(n_clusters=i)
        kmeans.fit(DATA_ENT)
        inertias.append(kmeans.inertia_)
        score = silhouette_score(np.array(DATA_ENT), kmeans.labels_)
        print(f"Score silhouette pour k={i}: {score:.4f}")

    plt.plot(range(2,40), inertias, marker='o')
    plt.title(f'Elbow method X = {NBRE_LIST}')
    plt.xlabel('Number of clusters')
    plt.ylabel('Inertie')
    plt.savefig(f"/home/sublax/Documents/L3_MIASHS/S2/GestionProjet/coude_X{NBRE_LIST}_new.png")
    plt.close() 

    #Puis on réalise les KMEANS, même si ici cela ne servait qu'en 2D, c'est utile pour voir l'évolution du projet et des groupes justement.
    kmeans = KMeans(n_clusters=9, random_state= 1)
    kmeans.fit(DATA_ENT)
    plt.scatter(DATA[0], DATA[1], c=kmeans.labels_)
    plt.title(f'Bonheur/Corruption politique X = {NBRE_LIST}')
    plt.xlabel('Moyenne du bonheur')
    plt.ylabel('Échelle de corruption')
    plt.savefig(f"/home/sublax/Documents/L3_MIASHS/S2/GestionProjet/cluster_X{NBRE_LIST}.png")
    plt.close()
    
def create_data():
    """
    Fonction qui créer la base de donnée permettant d'effectuer les KMEAN
    ça évite surtout d'avoir une grosse requête à chaque fois que l'utilisateur veut avoir sa prédiction.
    Compléte + centre et réduit les données.
    
    Sortie : 
        - DataFrame enregistré en .csv de toutes les données complétés et centrées/réduites.
    """
    cursor.execute(REQUEST_TABLE + JOINT_TABLE)
    
    #On prend les DATA ENTières (elles nous serviront de complétion par moyenne)
    DATA_ENT = cursor.fetchall()
    print("DATA ENTIERE AV. TRANSFO : ",DATA_ENT[0])
    columns = ['id_pays','avg_score_bonheur', 'avg_generosite', 'avg_libExpress','avg_corruption', 'taux_crime', 
           'avgGDP', 'avgPOP', 'avg_taux_classe_primaire', 'avg_taux_classe_secondaire',
           "avg_HiverMin","avg_EteMax", "religionImportant", "religionPasImp","EsperanceVie","avgMigration","avgTransport","avgUnemployedWomen","avgUnemployedMen"]

    imputer = SimpleImputer(strategy='mean')
    scaler = StandardScaler()
    
    #On fait un dataframe avec la complétion et le centrage/réduction
    df = pd.DataFrame(DATA_ENT, columns=columns)
    df.iloc[:,2:18]  = imputer.fit_transform(df.iloc[:,2:18])
    df.iloc[:,2:18] = scaler.fit_transform(df.iloc[:,2:18])
    #Sauvegarde le DataFrame dans un fichier CSV
    df.to_csv('./REQUETE_ENT.csv', index=False)
            

app.run(debug=True, port=5000)
    
    
    