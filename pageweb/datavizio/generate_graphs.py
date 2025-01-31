import sys
import pandas as pd
import pymysql
import os
import plotly.express as px
from urllib.parse import quote

# -- Graph types --
def multiple_boxplot(df, columns, title, x_title, y_title, graph_name):
    """
    ==========================================================
    Role : Creates multiple boxplots for selected columns
    ==========================================================
    Params : 
        - df (dataframe) --> dataframe you're working on
        - columns (list) --> list of column names to create boxplots
        - title (str) --> title of the graph
        - x_title (str) --> x-axis title
        - y_title (str) --> y-axis title
        - graph_name (str) --> destination file of the graph
    ==========================================================
    """
    # Reshape the DataFrame for boxplot visualization
    melted_df = df[columns].melt(var_name="Category", value_name="Values")
    
    # Create the boxplot
    fig = px.box(
        melted_df,
        x="Category",  # Categories (column names) on the x-axis
        y="Values",    # Values on the y-axis
        color="Category",  # Color by category
        title=title,
        boxmode="group",
    )
    
    # Customize layout
    fig.update_layout(
        showlegend=False,
        title=dict(
            text=title,
            x=0.5,
            font=dict(size=20)
        ),
        xaxis=dict(title=x_title),
        yaxis=dict(title=y_title),
        template="plotly_dark",
    )
    
    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, graph_name)
    
    # Writing the graph
    fig.write_html(output_file)
    

def multiple_line_plot(df, data_x, data_ys, title, x_title, y_title, graph_name):
    """
    ==========================================================
    Role : Creates a multiple line plot
    ==========================================================
    Params : 
        - df (dataframe) --> dataframe you're working on
        - data_x (str) --> name of the column to put in x-axis
        - data_ys (list) --> list of column names to plot as multiple lines
        - title (str) --> title of the graph
        - x_title (str) --> x-axis title
        - y_title (str) --> y-axis title
        - graph_name (str) --> name of the graph file
    ==========================================================
    """
    fig = px.line(
        df,
        x=data_x,
        y=data_ys,
        title="<b>" + title + "</b>",
        markers=True,  # Adds markers to the lines
        color_discrete_sequence=px.colors.qualitative.Plotly
    )
    
    fig.update_layout(
        showlegend=False,
        title_font_size=20,
        title_x=0.5,
        xaxis_title=x_title,
        yaxis_title=y_title,
        font=dict(size=14),
        plot_bgcolor="rgba(0,0,0,0)",  # Transparent background
        paper_bgcolor="rgba(255,255,255,1)",  # White canvas background
        xaxis=dict(showgrid=True, gridcolor="lightgrey"),  # Light gridlines for x-axis
        yaxis=dict(showgrid=True, gridcolor="lightgrey"),  # Light gridlines for y-axis
        margin=dict(l=40, r=40, t=50, b=50),  # Adjust margins
    )
    
    # Customize lines and markers
    fig.update_traces(
        line=dict(width=3),  # Line width
        marker=dict(size=8)  # Marker size
    )
    
    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, graph_name)
    
    # Writing the graph
    fig.write_html(output_file)
    print(f"Multiple line plot saved successfully as {output_file}")


def multiple_bar_plot(df, data_x, data_ys, title, x_title, y_title, graph_name):
    """
    ==========================================================
    Role : Creates a multiple bar plot
    ==========================================================
    Params : 
        - df (dataframe) --> dataframe you're working on
        - data_x (str) --> name of the column to put in x-axis
        - data_ys (list) --> list of column names to use as y-axis bars
        - title (str) --> title of the graph
        - x_title (str) --> x-axis title
        - y_title (str) --> y-axis title
        - graph_name (str) --> name of the graph file
    ==========================================================
    """
    fig = px.bar(
        df,
        x=data_x,
        y=data_ys,
        title="<b>" + title + "</b>",
        barmode='group',
        color_discrete_sequence=px.colors.qualitative.Plotly
    )
    
    fig.update_layout(
        showlegend=False,
        title_font_size=20,
        title_x=0.5,
        xaxis_title=x_title,
        yaxis_title=y_title,
        font=dict(size=14),
        plot_bgcolor="rgba(0,0,0,0)",  # Transparent background
        paper_bgcolor="rgba(255,255,255,1)",  # White canvas background
        xaxis=dict(showgrid=False),  # Remove vertical gridlines
        yaxis=dict(showgrid=True, gridcolor="lightgrey"),  # Light gridlines for y-axis
        margin=dict(l=40, r=40, t=50, b=50),  # Adjust margins
    )
    
    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, graph_name)
    
    # Writing the graph
    fig.write_html(output_file)
    print(f"Multiple bar plot saved successfully as {output_file}")


def pie_chart(df, data_values, data_labels=None, title="Pie Chart", graph_name="piechart.html"):
    """
    ==========================================================
    Role : Creates a pie chart
    ==========================================================
    Params : 
        - df (dataframe) --> dataframe you're working on
        - data_values (str) --> name of the column with values for the pie slices
        - data_labels (str, optional) --> name of the column with labels for the pie slices (default: None)
        - title (str) --> title of the graph
        - graph_name (str) --> name of the graph file
    ==========================================================
    """
    if data_labels:
        fig = px.pie(
            df,
            values=data_values,
            names=data_labels,
            title="<b>" + title + "</b>",
            color_discrete_sequence=px.colors.sequential.Viridis
        )
    else:
        fig = px.pie(
            df,
            values=data_values,
            names=df.index,  # Use index as labels if data_labels is not provided
            title="<b>" + title + "</b>",
            color_discrete_sequence=px.colors.sequential.Viridis
        )
    
    fig.update_layout(
        title_font_size=20,
        title_x=0.5,
        margin=dict(l=40, r=40, t=50, b=50),  # Adjust margins
        showlegend=True,
    )
    
    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, graph_name)
    
    # Writing the graph
    fig.write_html(output_file)
    print(f"Pie chart saved successfully as {output_file}")



def barplot(df, data_x, data_y, title, x_title, y_title, graph_name):
    """
    ==========================================================
    Role : Creates a barplot
    ==========================================================
    Params : 
        - df (dataframe) --> dataframe you're working on
        - data_x (str) --> name of the column to put in x-axis
        - data_y (str) --> name of the column to put in y-axis
        - x_title (str) --> x-axis title
        - y_title (str) --> y-axis title
        - output_file (str) --> destination file of the graph
        - graph_name (str) --> name of the graph file
    ==========================================================
    """
    # Customize the bar chart
    fig = px.bar(
            df, 
            x=data_x, 
            y=data_y, 
            title="<b>" + title + "</b>", 
            color=data_y, 
            color_continuous_scale=px.colors.sequential.Viridis
    )
    # Update layout for a cleaner look
    fig.update_layout(
        showlegend=False,
        title_font_size=20,
        title_x=0.5,  # Center the title
        xaxis_title=x_title,
        yaxis_title=y_title,
        font=dict(size=14),
        plot_bgcolor="rgba(0,0,0,0)",  # Transparent background
        paper_bgcolor="rgba(255,255,255,1)",  # White canvas background
        xaxis=dict(showgrid=False),  # Remove vertical gridlines
        yaxis=dict(showgrid=True, gridcolor="lightgrey"),  # Light gridlines for y-axis
        margin=dict(l=40, r=40, t=50, b=50),  # Adjust margins
    )  
    
    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, graph_name)
    
    # Writing the graph
    fig.write_html(output_file)
    
def lineplot(df, data_x, data_y, title, x_title, y_title, graph_name):
    """
    ==========================================================
    Role : Creates a line plot
    ==========================================================
    Params : 
        - df (dataframe) --> dataframe you're working on
        - data_x (str) --> name of the column to put in x-axis
        - data_y (str) --> name of the column to put in y-axis
        - title (str) --> title of the graph
        - x_title (str) --> x-axis title
        - y_title (str) --> y-axis title
        - graph_name (str) --> name of the graph file
    ==========================================================
    """
    # Customize the line chart
    fig = px.line(
        df, 
        x=data_x, 
        y=data_y, 
        title="<b>" + title + "</b>",
        markers=True,  # Adds markers to the lines
        color_discrete_sequence=px.colors.qualitative.Plotly  # Beautiful color palette
    )
    
    # Update layout for a cleaner look
    fig.update_layout(
        showlegend=False,
        title_font_size=20,
        title_x=0.5,  # Center the title
        xaxis_title=x_title,
        yaxis_title=y_title,
        font=dict(size=14),
        plot_bgcolor="rgba(0,0,0,0)",  # Transparent background
        paper_bgcolor="rgba(255,255,255,1)",  # White canvas background
        xaxis=dict(showgrid=True, gridcolor="lightgrey"),  # Light gridlines for x-axis
        yaxis=dict(showgrid=True, gridcolor="lightgrey"),  # Light gridlines for y-axis
        margin=dict(l=40, r=40, t=50, b=50),  # Adjust margins
    )
    
    # Customize lines and markers
    fig.update_traces(
        line=dict(width=3),  # Line width
        marker=dict(size=8)  # Marker size
    )

    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, graph_name)
    
    # Writing the graph
    fig.write_html(output_file)
    print(f"Line plot saved successfully as {output_file}")

    
def boxplot(df, data, title, x_title, y_title, graph_name):
    """
    ==========================================================
    Role : Creates a boxplot
    ==========================================================
    Params : 
        - df (dataframe) --> dataframe you're working on
        - data (str) --> name of the column with values
        - x_title (str) --> x-axis title
        - y_title (str) --> y-axis title
        - output_file (str) --> destination file of the graph
    ==========================================================
    """
    # Create a boxplot for a single column
    fig = px.box(
        df,
        y=data,
        title=title,
        boxmode="group",
    )
    # Customize layout
    fig.update_layout(
        showlegend=False,
        title=dict(
            text="Visual Boxplot Example",
            x=0.5,
            font=dict(size=20)
        ),
        xaxis=dict(title=x_title),
        yaxis=dict(title=y_title),
        template="plotly_dark",
    )  
    
    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, graph_name)
    
    # Writing the graph
    fig.write_html(output_file)
    
# -- Function to connect to the database --
def connect_to_database():
    """
    ============================
        Connection database
    ============================
    """
    print("Attempting to connect to the database...")
    try:
        connection = pymysql.connect(
            host="localhost",      # MySQL server host
            user="root",           # MySQL username
            password="root",  # MySQL password
            database="bdprojet",  # Your database name
            cursorclass=pymysql.cursors.DictCursor  # Fetch rows as dictionaries
        )
        print("Database connection successful!")
        return connection
    except pymysql.MySQLError as e:
        print(f"Error connecting to the database: {e}")
        return None

# -- Fetch data from the database for the map --
def fetch_data(query):
    """
    ==================================================================
    Role : Gets the data into a df
    ==================================================================
    Params : query (str) --> query to execute for fetching data
    ==================================================================
    Workflow : 
        - connection database
        - executes query for getting the data from the database
        - transform it into a df
    ==================================================================
    """
    print(f"Executing query: {query}")
    connection = connect_to_database()
    if not connection:
        print("Failed to establish database connection.")
        return None

    try:
        cursor = connection.cursor()
        cursor.execute(query)
        rows = cursor.fetchall()
        print(f"Query executed successfully. Rows fetched: {len(rows)}")
        return pd.DataFrame(rows)  # Convert to DataFrame for easier handling
    except pymysql.MySQLError as e:
        print(f"Error fetching data: {e}")
        return None
    finally:
        print("Closing database connection.")
        connection.close()

# -- Main script to fetch data and generate the map --
def generate_graph(domain, country_name):
    """
    ==============================================================
    Role : Generates a specific graph
    ==============================================================
    Workflow : 
        - sets the specific query based on the country name
        - fetches data into a df using the specific query
        - creates the graph
        - saves it as html file
    ==============================================================
    """
    print(f"Starting graph generation for domain: {domain}, country: {country_name}")
    
    # Query creation
    if domain == "economie":
        query = f""" 
                SELECT * 
                FROM {domain}, pays 
                WHERE {domain}.id_country = pays.id_pays
                AND pays.nom_pays LIKE LOWER("{country_name}")
                """
    else :
        query = f""" 
                SELECT * 
                FROM {domain}, pays 
                WHERE {domain}.id_pays = pays.id_pays
                AND pays.nom_pays LIKE LOWER("{country_name}")
                """
    print(f"Generated query: {query}")
    
    # Fetch data from the database
    print("Fetching data from the database...")
    df = fetch_data(query)
    
    if df is None:
        print("Error: Could not fetch data from the database.")
        return
    if df.empty:
        print(f"No data found for domain: {domain}, country: {country_name}. Exiting...")
        return
    
    print(f"Data fetched successfully. DataFrame shape: {df.shape}")
    print(f"Data preview:\n{df.head()}")

    # Path for saving the graph
    output_dir = os.path.join(os.path.dirname(__file__), "static", "graphs")
    os.makedirs(output_dir, exist_ok=True)
    output_file = os.path.join(output_dir, f"{country_name}_graphs.html")
    print(f"Output file path set to: {output_file}")
    
    # Generate the graphs based on domains
    print(f"Generating graph for domain: {domain}...")
    match domain:
        
        case "agroalimentaire":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                try:
                    barplot(
                        df, 
                        "annee", 
                        "costhealthydiet", 
                        "Evolution du cout d'une diète saine", 
                        "Année", 
                        "Prix", 
                        "agroalimentaire_barplot_1.html"
                    )
                    
                    lineplot(
                        df, 
                        "annee", 
                        "costhealthydiet", 
                        "Evolution du cout d'une diète saine", 
                        "Année", 
                        "Prix", 
                        "agroalimentaire_lineplot_1.html"
                    )
                    
                    barplot(
                        df, 
                        "annee", 
                        "cleanfuelandcookingequipment", 
                        "Evolution des résources propres pour cuisiner", 
                        "Année", 
                        "Score", 
                        "agroalimentaire_barplot_2.html"
                    )
                    
                    lineplot(
                        df, 
                        "annee", 
                        "cleanfuelandcookingequipment", 
                        "Evolution des résources propres pour cuisiner", 
                        "Année", 
                        "Score", 
                        "agroalimentaire_lineplot_2.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        ["cleanfuelandcookingequipment", "costhealthydiet"],
                        "Comparaison des Indicateurs : \n Accès aux Combustibles Propres et Équipements de Cuisine vs Coût d'une Alimentation Saine",
                        "Indicateurs",
                        "Valeurs",
                        "agroalimentaire_boxplot_3.html"
                    )
                    
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        
        case "bonheur":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                try:
                    
                    barplot(
                        df,
                        "annee",
                        "score_bonheur",
                        "Evolution Score Bonheur",
                        "Annees",
                        "Score Bonheur",
                        "bonheur_barplot_1.html"
                    )
                    
                    barplot(
                        df,
                        "annee",
                        "generosite",
                        "Evolution Générosité",
                        "Annees",
                        "Score Générosité",
                        "bonheur_barplot_2.html"
                    )
                    
                    lineplot(
                        df,
                        "annee",
                        "score_bonheur",
                        "Evolution Score Bonheur",
                        "Annees",
                        "Score Bonheur",
                        "bonheur_lineplot_1.html"
                    )
                    
                    lineplot(
                        df,
                        "annee",
                        "generosite",
                        "Evolution Générosité",
                        "Annees",
                        "Score Generosite",
                        "bonheur_lineplot_2.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        ["generosite", "score_bonheur"],
                        "Comparaison des indicateurs : bonheur vs generosité",
                        "Indicateurs",
                        "Valeurs",
                        "bonheur_boxplot_3.html"
                    )
                    
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
                    
        case "corruption":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                try:
                    multiple_line_plot(
                        df,
                        "annee",
                        ["liberte_expression", "corruption_politique", "rule_of_law"],
                        "Evolution indicateurs corruption d'un pays",
                        "Années",
                        "Valeurs",
                        "corruption_lineplot_1.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        ["liberte_expression", "corruption_politique", "rule_of_law"],
                        "Comparaison Indicateurs corruption d'un pays",
                        "Indicateurs",
                        "Valeurs",
                        "corruption_boxplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
                    
        case "crime":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                try:
                    lineplot(
                    df,
                    "annee",
                    "taux",
                    "Evolution Taux Crimes",
                    "Années",
                    "Taux Crime",
                    "crime_lineplot_1.html"
                    )
                    barplot(
                        df,
                        "annee",
                        "count",
                        "Evolution Nombre de Crimes",
                        "Années",
                        "Nombre",
                        "crime_barplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "economie":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    indices = [
                                "AMA exchange rate",
                                "IMF based exchange rate",
                                "Per capita GNI",
                                "Agriculture, hunting, forestry, fishing (ISIC A-B)",
                                "Construction (ISIC F)",
                                "Exports of goods and services",
                                "Final consumption expenditure",
                                "General government final consumption expenditure",
                                "Gross capital formation",
                                "Household consumption expenditure",
                                "Imports of goods and services",
                                "Manufacturing (ISIC D)",
                                "Mining, Manufacturing, Utilities (ISIC C-E)",
                                "Other Activities (ISIC J-P)",
                                "Total Value Added",
                                "Transport, storage and communication (ISIC I)",
                                "Wholesale, retail trade, restaurants and hotels (ISIC G-H)",
                                "Gross National Income(GNI) in USD",
                                "Gross Domestic Product (GDP)"
                            ]
                    
                    multiple_bar_plot(
                        df,
                        "Year",
                        indices,
                        "Evolution indices économiques",
                        "Années",
                        "Valeurs",
                        "economie_barplot_1.html"
                    )
                    
                    multiple_line_plot(
                        df,
                        "Year",
                        indices,
                        "Evolution indices économiques",
                        "Années",
                        "Valeurs",
                        "economie_lineplot_1.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        indices,
                        "Comparaison indices économiques",
                        "Indices",
                        "Valeurs",
                        "economie_boxplot_1.html"
                    )

                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "education":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                try:
                    multiple_line_plot(
                        df,
                        "annee",
                        ["taux_classe_primaire", "taux_classe_secondaire"],
                        "Evolution taux cycle primaire et secondaire",
                        "Années",
                        "Taux",
                        "education_lineplot_1.html"
                    )
                    
                    multiple_bar_plot(
                        df,
                        "annee",
                        ["taux_classe_primaire", "taux_classe_secondaire"],
                        "Evolution taux cycle primaire et secondaire",
                        "Années",
                        "Taux",
                        "education_barplot_1.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        ["taux_classe_primaire", "taux_classe_secondaire"],
                        "Comparaison taux cycles primaire et secondaire",
                        "Cycles",
                        "Valeurs",
                        "education_boxplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "meteo":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    seasons = ["automne", "hiver", "ete", "printemps"]
                    
                    for index, season in enumerate(seasons):
                        multiple_boxplot(
                            df,
                            [f"{season}_tmin", f"{season}_tavg", f"{season}_tmax"],
                            f"Comparaison Valeurs Temperatures {season}",
                            "Années",
                            "Temperature",
                            f"meteo_boxplot_{index}.html"
                        )
                        
                        multiple_line_plot(
                            df,
                            "annee",
                            [f"{season}_tmin", f"{season}_tavg", f"{season}_tmax"],
                            f"Evolution Temperature {season}",
                            "Années",
                            "Temperature",
                            f"meteo_lineplot_{index}.html"
                        )
                        
                        multiple_bar_plot(
                            df,
                            "annee",
                            [f"{season}_tmin", f"{season}_tavg", f"{season}_tmax"],
                            f"Comparaison temperatures pendant {season}",
                            "Temperatures",
                            "Valeurs",
                            f"meteo_barplot_{index}.html"
                        )
                    
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "religion":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                groups = ["important","plutot_important", "plutot_pas_important", "pas_important", "ne_sais_pas", "ne_se_prononce_pas"]
                try:
                    multiple_line_plot(
                        df,
                        "annee",
                        groups,
                        "Evolution importance religion",
                        "Année",
                        "Valeur",
                        "religion_lineplot_1.html"
                    )
                        
                    multiple_boxplot(
                        df,
                        groups,
                        "Comparaison indices d'importance religion",
                        "Année",
                        "Valeur",
                        "religion_boxplot_1.html"
                    )
                    
                    multiple_bar_plot(
                        df,
                        "annee",
                        groups,
                        "Evolution importance religion",
                        "Années",
                        "Valeur",
                        "religion_barplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
                    
        case "sante":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    lineplot(
                        df,
                        "annee",
                        "esperance_vie",
                        "Evolution esperance de vie",
                        "Année",
                        "Valeur",
                        "sante_lineplot_1.html"
                    )
                    
                    barplot(
                        df,
                        "annee",
                        "esperance_vie",
                        "Evolution esperance de vie",
                        "Année",
                        "Valeur",
                        "sante_barplot_1.html"
                    )
                    
                    boxplot(
                        df,
                        "esperance_vie",
                        "Structure valeurs esperance de vie",
                        "esperance de vie",
                        "Valeur",
                        "sante_boxplot_1.html"
                    )
                    
                    multiple_line_plot(
                        df,
                        "annee",
                        ["mort", "mort_estime", "naissance", "naissance_estimee"],
                        "Evolution et prediction indices démographiques",
                        "Années",
                        "Valeur",
                        "sante_lineplot_2.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        ["mort", "mort_estime", "naissance", "naissance_estimee"],
                        "Comparaison indices démographiques",
                        "x",
                        "y",
                        "sante_boxplot_2.html"
                    )
                    
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
                    
        case "social":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    multiple_bar_plot(
                        df,
                        "annee",
                        ["salaire_min_annuel", "salaire_min_heure", "acces_elect"],
                        "Evolution indices sociales",
                        "Années",
                        "Valeurs",
                        "social_barplot_1.html"
                    )
                    
                    multiple_line_plot(
                        df,
                        "annee",
                        ["salaire_min_annuel", "salaire_min_heure", "acces_elect"],
                        "Evolution indices sociales",
                        "Années",
                        "Valeurs",
                        "social_lineplot_1.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        ["salaire_min_annuel", "salaire_min_heure", "acces_elect"],
                        "Comparaison indices sociales",
                        "Années",
                        "Valeurs",
                        "social_boxplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
                    
        case "tourisme":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    barplot(
                        df,
                        "annee",
                        "inbound_arrival",
                        "Evolution Arrivées",
                        "Années",
                        "Valeurs",
                        "tourisme_barplot_1.html"
                    )
                    
                    lineplot(
                        df,
                        "annee",
                        "inbound_arrival",
                        "Evolution Arrivées",
                        "Années",
                        "Valeurs",
                        "tourisme_lineplot_1.html"
                    )
                    
                    boxplot(
                        df,
                        "inbound_arrival",
                        "Structure valeurs arrivées",
                        "Arrivées",
                        "Valeurs",
                        "tourisme_boxplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
                    
        case "transport":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    barplot(
                        df,
                        "annee",
                        "taux_acces_transport",
                        "Evolution taux accées transport",
                        "Années",
                        "Valeur",
                        "transport_barplot_1.html"
                    )
                    
                    lineplot(
                        df,
                        "annee",
                        "taux_acces_transport",
                        "Evolution taux accées transport",
                        "Années",
                        "Valeur",
                        "transport_lineplot_1.html"
                    )
                    
                    boxplot(
                        df,
                        "taux_acces_transport",
                        "Structure valeurs taux accées transport",
                        "Taux Accées Transport",
                        "Valeurs",
                        "transport_boxplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
                    
        case "travail":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    multiple_bar_plot(
                        df,
                        "annee",
                        ["sans_emploi_femme", "sans_emploi_homme"],
                        "Evolution taux chomage par genre",
                        "Année",
                        "Valeur",
                        "travail_barplot_1.html"
                    )
                    
                    multiple_line_plot(
                        df,
                        "annee",
                        ["sans_emploi_femme", "sans_emploi_homme"],
                        "Evolution taux chomage par genre",
                        "Année",
                        "Valeur",
                        "travail_lineplot_1.html"
                    )
                    
                    multiple_boxplot(
                        df,
                        ["sans_emploi_femme", "sans_emploi_homme"],
                        "Comparaison taux chomage par genre",
                        "Genres",
                        "Valeur",
                        "travail_boxplot_1.html"
                    )
                    
                    barplot(
                        df,
                        "annee",
                        "population",
                        "Evolution population",
                        "Année",
                        "Valeur",
                        "travail_barplot_2.html"
                    )
                    
                    lineplot(
                        df,
                        "annee",
                        "population",
                        "Evolution population",
                        "Année",
                        "Valeur",
                        "travail_lineplot_2.html"
                    )
                    
                    boxplot(
                        df,
                        "population",
                        "Structure valeurs population",
                        "",
                        "Valeurs",
                        "travail_boxplot_2.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        
    
    print("Graph generation completed successfully.")

if __name__ == "__main__":
    # Getting the country name
    if len(sys.argv) != 2:
        print("Usage: python generate_graphs.py <country_name>")
        sys.exit(1)
    country_name = quote(sys.argv[1])
    
    # Generating graphs
    print("Starting graph generation...")
    domains = ["agroalimentaire", 
               "bonheur", 
               "corruption", 
               "crime", 
               "economie", 
               "education", 
               "meteo", 
               "religion", 
               "sante", 
               "social", 
               "tourisme", 
               "transport", 
               "travail"]
    
    for domain in domains :
        generate_graph(domain, country_name)
