import pandas as pd 
import os 

current_dir = os.path.dirname(os.path.abspath(__file__))
sante_path = os.path.join(current_dir,'../../data/raw/Sante/sante2.csv')
countries_path = os.path.join(current_dir, '../../data/processed/Pays/countries.csv')
result_sante_save_path = os.path.join(current_dir,'../../data/processed/Sante/sante_final.csv')

sante2 = pd.read_csv(sante_path)
countries = pd.read_csv(countries_path)


countries.rename(columns={'Name': 'Entity'}, inplace=True)

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

result_sante_save_path = os.path.join(current_dir,'../../data/processed/Sante/sante_final.csv')
sante2.to_csv(result_sante_save_path,index=True,index_label="id_sante")



