import subprocess
from flask import Flask, jsonify

app = Flask(__name__)

def start_script():
    subprocess.Popen(["python3", "../utilisateur/traitement.py"])
    
@app.route('/')
def hello_world():
    start_script()
    return jsonify({"message": "Script Python démarré en arrière-plan !"})

if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0", port=5000)
