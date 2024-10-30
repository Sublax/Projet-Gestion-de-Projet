import pandas as pd
import country_converter as coco

def standardisation(df,nom_col):
    """
    Fonction qui prend en paramètre le df et la colonne nom et la transforme en norme name_short (nom complet)
    puis supprime les lignes not found 
    """
    cc = coco.CountryConverter()
    df[nom_col] = cc.convert(df[nom_col],to = 'name_short')
    df = df[~df[nom_col].str.contains("not found")]
    return df

