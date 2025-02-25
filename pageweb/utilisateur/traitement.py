import pymysql
from flask import Flask, request, jsonify,send_file
from flask_cors import CORS 
from sklearn.cluster import KMeans
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
    print("JSON reçu :", data)
    print("La liste reçue : ",list(data.values()))
    pays_selected = list(data.values())[0]
    print(pays_selected[0])
    #Prendre la dernière année : 

    cursor.execute("SELECT pays.nom_pays,AVG(rang_bonheur) FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays AND pays.nom_pays <> '" + pays_selected[0] + "' GROUP BY pays.nom_pays")
    data_x = []
    data_y = []
    #cursor.execute("SELECT pays.nom_pays,AVG(rang_bonheur),AVG((corruption_politique * 100)) FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays AND pays.nom_pays <> '" + pays_selected[0] + "' INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays")
    cursor.execute("SELECT AVG(rang_bonheur),AVG((corruption_politique * 100)) FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays AND pays.nom_pays <> '" + pays_selected[0] + "' INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays")
    test = cursor.fetchall()
    print("Longueur du test : ",len(test))

    for data in list(test):
        data_x.append(data[0])
        data_y.append(data[1])

    inertias = []
    for i in range(1,11):
        kmeans = KMeans(n_clusters=i, random_state=1)
        kmeans.fit(test)
        inertias.append(kmeans.inertia_)

    plt.plot(range(1,11), inertias, marker='o')
    plt.title('Elbow method')
    plt.xlabel('Number of clusters')
    plt.ylabel('Inertia')
    plt.savefig("./elbow_method.png")
    plt.close() 

    kmeans = KMeans(n_clusters=3)
    kmeans.fit(test)
    plt.scatter(data_x, data_y, c=kmeans.labels_)
    #print("DATA X  : ",data_x)
    #print("DATA Y : ",data_y)
    plt.title('Bonheur/Corruption politique')
    plt.xlabel('Bonheur')
    plt.ylabel('Politique')
    plt.savefig("./Cluster.png")
    plt.close()

    cursor.execute("SELECT AVG(rang_bonheur),AVG((corruption_politique * 100)) FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays AND pays.nom_pays = '" + pays_selected[0] + "' INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays")
    selected_country = list(cursor.fetchall())
    #Prédiction du groupe auquel appartient le choix de l'user :
    cluster = kmeans.predict(selected_country)

    #On extrait les coordonnées des pts du même cluster :
    indices_cluster = np.where(kmeans.labels_ == cluster[0])[0]

    #On prend tous pts du cluster
    points_cluster = np.array(test)[indices_cluster]

    # On calcule les distances entre la donnée et tous les points du cluster
    distances = cdist(selected_country, points_cluster)

    #Et on trouve le voisin le plus proche
    index_voisin_plus_proche = np.argmin(distances)
    voisin_plus_proche = points_cluster[index_voisin_plus_proche]
    print(f"Le voisin le plus proche est : {voisin_plus_proche}")
    cursor.execute("SELECT pays.nom_pays,AVG(rang_bonheur) as bon,AVG((corruption_politique * 100)) as corrupt FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays INNER JOIN corruption ON corruption.id_pays = pays.id_pays GROUP BY pays.nom_pays HAVING AVG(rang_bonheur) =" + str(voisin_plus_proche[0]))
    country_predict = cursor.fetchone()
    print("Country predicted : ",country_predict[0])
    
    return jsonify({"status": "success", "message": "Données reçues avec succès", "data": data}), 200
app.run(debug=True, port=5000)
    
    
    