
import country_converter as coco 
import os 
import pandas as pd 
def standardisation(df,nom_col):
    """
    Fonction qui prend en paramètre le df et la colonne nom et la transforme en norme name_short (nom complet)
    puis supprime les lignes not found 
    """
    cc = coco.CountryConverter()
    df[nom_col] = cc.convert(df[nom_col],to = 'name_short')
    df = df[~df[nom_col].str.contains("not found")]
    return df

current_dir = os.path.dirname(os.path.abspath(__file__))
sante_path = os.path.join(current_dir,'sante.csv')
religion_path = os.path.join(current_dir,'religion.csv')

sante= pd.read_csv(sante_path)
religion = pd.read_csv(religion_path)

result_sante = standardisation(sante,"Entity") 
result_religion= standardisation(religion,"Entity")

result_sante.to_csv("c:/Users/tojol/Desktop/data_ced/sante2.csv", index= False)
result_religion.to_csv("c:/Users/tojol/Desktop/data_ced/religion2.csv", index= False)