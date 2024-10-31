import pandas as pd 
import os 


current_dir = os.path.dirname(os.path.abspath(__file__))
religion_path = os.path.join(current_dir,'../../data/raw/Religion/religion2.csv')
countries_path = os.path.join(current_dir, '../../data/processed/Pays/countries.csv')
result_religion_save_path = os.path.join(current_dir,'../../data/processed/Religion/religion_final.csv')

religion2 = pd.read_csv(religion_path)
countries = pd.read_csv(countries_path)

religion2.drop(columns=['Code'], inplace=True)
countries.rename(columns={'Name': 'Entity'}, inplace=True)
religion2.rename(columns={"Importance": "Very Important in life"}, inplace=True)

# Remplacer 'Entity' par 'id_country' dans religion2
religion2 = religion2.merge(countries[['Entity', 'id_country']], on='Entity', how='left')

# S'assurer que 'Entity' et 'Year' est un entier
religion2.dropna(subset=['id_country', 'Year'], inplace=True)
religion2['Year'] = religion2['Year'].astype(int)
religion2['id_country'] = religion2['id_country'].astype(int)
religion2 = religion2[religion2['Not very important in life'] != "https://ourworldindata.org/grapher/how-important-religion-is-in-your-life"]
religion2.drop(columns=['Entity'], inplace=True)

cols = ['id_country'] + [col for col in religion2.columns if col != 'id_country']
religion2 = religion2[cols]

# Sauvegarder le fichier final de religion2
religion2.to_csv(result_religion_save_path, index=True,index_label="id_religion")



