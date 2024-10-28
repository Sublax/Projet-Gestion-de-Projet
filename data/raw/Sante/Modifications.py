import pandas as pd 

religion2 = pd.read_csv("c:/Users/tojol/Desktop/data_ced/religion2.csv")
sante2 = pd.read_csv("c:/Users/tojol/Desktop/data_ced/Sante2.csv")
countries = pd.read_csv("c:/Users/tojol/Desktop/data_ced/countries.csv")

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
religion2.to_csv("c:/Users/tojol/Desktop/data_ced/religion_final.csv", index=True,index_label="id_religion")

# Supprimer la colonne "Code_x"
sante2.drop(columns=['Code_x'], inplace=True)

# Renommer les colonnes
sante2.rename(columns={
    'Deaths - Sex: all - Age: all - Variant: estimates': 'Morts',
    'Deaths - Sex: all - Age: all - Variant: medium': 'Morts_estimées',
    'Births - Sex: all - Age: all - Variant: estimates': 'Naissances',
    'Births - Sex: all - Age: all - Variant: medium': 'Naissances_estimées'
}, inplace=True)

sante2 = sante2.merge(countries[['Entity', 'id_country']], on='Entity', how='left')

# Remplir les valeurs manquantes avec 0 avant la conversion en entier
#sante2['Year'] = sante2['Year'].astype(int)
#sante2['id_country'] = sante2['id_country'].fillna(300).astype(int)
sante2.dropna(subset=['id_country', 'Year'], inplace=True)
sante2['id_country'] = sante2['id_country'].astype(int)
sante2['Year'] = sante2['Year'].astype(int)
sante2.drop(columns=['Entity'], inplace=True)
cols = ['id_country'] + [col for col in sante2.columns if col != 'id_country']
sante2 = sante2[cols]

sante2.to_csv("c:/Users/tojol/Desktop/data_ced/sante_final.csv",index=True,index_label="id_sante")



