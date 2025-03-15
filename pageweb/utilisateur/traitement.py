#===================================
# A FAIRE ; 
# -> Passer de R2 à Rn pour les variables (ttes les inclure) (DONE)
# -> Faire le clustering qu'une seule fois et pas à chaque fois (DONE)
# --> Le CSV oublie une ligne car il a été une fois sans Chili, il faudrait prendre tous les pays et enlever la ligne correspondant au pays sélectionné. (DONE)
# -> Renvoyer le résultat
# -> Attention aux injections SQL
# -> Ne plus passer par l'execution manuel du python mais plutôt par l'execution via le site web du script. Puis renvoyer le résultat.
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

connection = pymysql.connect(host="localhost", port=3306, user="root", passwd="root", database="bdprojet")
cursor = connection.cursor()

app = Flask(__name__)
CORS(app)
REQUEST_TABLE= """
SELECT 
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
        create_data()
    DATA = []


    DATA_ENT = pd.read_csv(req)
    
    #Complétion par moyenne global :
    imputer = SimpleImputer(strategy='mean')
    DATA_ENT = imputer.fit_transform(DATA_ENT)
    
    #On standardise les données : 
    scaler = StandardScaler()
    DATA_ENT = scaler.fit_transform(DATA_ENT)
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
    for i in range(2,40):
        kmeans = KMeans(n_clusters=i, random_state=1)
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
    kmeans = KMeans(n_clusters=3)
    kmeans.fit(DATA_ENT)
    plt.scatter(DATA[0], DATA[1], c=kmeans.labels_)
    plt.title(f'Bonheur/Corruption politique X = {NBRE_LIST}')
    plt.xlabel('Moyenne du bonheur')
    plt.ylabel('Échelle de corruption')
    plt.savefig(f"/home/sublax/Documents/L3_MIASHS/S2/GestionProjet/cluster_X{NBRE_LIST}.png")
    #plt.close()
    # /////////////////////////////////////////////////////////////////////////////////////
    # ///////////////////////////////// FIN -VISUALISATION ////////////////////////////////
    # /////////////////////////////////////////////////////////////////////////////////////
    
    cursor.execute(REQUEST_TABLE + JOINT_TABLE + 
                """ 
                WHERE pays.nom_pays = '""" + pays_selected[0] + """' 
                """)
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
    kmeans = KMeans(n_clusters=4, random_state=1).fit(DATA_ENT)
    selected_country = np.array(requete)
    
    #Prédiction du groupe auquel appartient le choix de l'user :
    cluster = kmeans.predict(selected_country)
    print("Cluster trouvé : ",cluster)
    print(kmeans.labels_)

    
    #On extrait les coordonnées des pts du même cluster :
    indices_cluster = np.where(kmeans.labels_ == cluster[0])[0]
    print("INDICES CLUSTER :", indices_cluster)
    random_selected = random.sample(list(indices_cluster),3)

    pays_predicted = []
    for i in range(len(random_selected)):
    #REPOSE SUR UNE HYPOTHÈSE : 
    #   - Les requêtes sont effectués dans le même ordre que l'id pays inscrit ici.
        #cursor.execute("SELECT pays.nom_pays,AVG(score_bonheur) as bon,AVG((corruption_politique * 100)) as corrupt FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays HAVING AVG(score_bonheur) =" + str(points_cluster[0]))
        cursor.execute("SELECT pays.nom_pays FROM pays WHERE id_pays="+ str(random_selected[i]))
        pays_predicted.append(cursor.fetchone())
    print("Country predicted : ",pays_predicted)
    
    return jsonify({"status": "success", "message": "Données reçues avec succès", "data": pays_predicted}), 200







def create_data():
    """
    Fonction qui créer la base de donnée permettant d'effectuer les KMEAN
    ça évite surtout de se taper une grosse requête à chaque fois que l'utilisateur veut avoir sa prédiction.
    """
    cursor.execute(REQUEST_TABLE + JOINT_TABLE)
    
    #On prend les DATA ENTières (elles nous serviront de complétion par moyenne)
    DATA_ENT = cursor.fetchall()
    print("DATA ENTIERE AV. TRANSFO : ",DATA_ENT[0])
    columns = ['avg_score_bonheur', 'avg_generosite', 'avg_libExpress','avg_corruption', 'taux_crime', 
           'avgGDP', 'avgPOP', 'avg_taux_classe_primaire', 'avg_taux_classe_secondaire',
           "avg_HiverMin","avg_EteMax", "religionImportant", "religionPasImp","EsperanceVie","avgMigration","avgTransport","avgUnemployedWomen","avgUnemployedMen"]

    # Crée un DataFrame pandas à partir des données récupérées
    df = pd.DataFrame(DATA_ENT, columns=columns)

    # Enregistre le DataFrame dans un fichier CSV
    df.to_csv('./REQUETE_ENT.csv', index=False)
            

app.run(debug=True, port=5000)
    
    
    