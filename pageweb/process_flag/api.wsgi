import sys
import os

# Chemin vers ton projet
sys.path.insert(0, os.path.dirname(__file__))

# Importer l’application Flask
from flask_app import app as application
