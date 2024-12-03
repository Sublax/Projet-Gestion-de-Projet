import sys
import pandas as pd
import pymysql
import os
import plotly.express as px
from urllib.parse import quote

# -- Graph types --
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
        - data_x (str) --> name of the column to put in x-axis
        - data_y (str) --> name of the column to put in y-axis
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
        title=dict(
            text="Visual Boxplot Example",
            x=0.5,
            font=dict(size=20)
        ),
        xaxis=dict(title=x_title),
        yaxis=dict(title=y_title),
        template="plotly_dark"
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
            database="bd_projet",  # Your database name
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
                        "title", 
                        "x_title", 
                        "y_title", 
                        "agroalimentaire_barplot_1.html"
                    )
                    
                    lineplot(
                        df, 
                        "annee", 
                        "cleanfuelandcookingequipment", 
                        "title", 
                        "x_title", 
                        "y_title", 
                        "agroalimentaire_lineplot_1.html"
                    )
                    
                    boxplot(
                        df,
                        "costhealthydiet",
                        "title", 
                        "x_title",
                        "y_title", 
                        "agroalimentaire_boxplot_1.html"
                    )
                    
                    boxplot(
                        df,
                        "cleanfuelandcookingequipment",
                        "title", 
                        "x_title",
                        "y_title", 
                        "agroalimentaire_boxplot_2.html"
                    )
                    
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        
        case "bonheur":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                try:
                    lineplot(
                        df,
                        "annee",
                        "score_bonheur",
                        "Lineplot Bonheur",
                        "Annees",
                        "Score Bonheur",
                        "bonheur_lineplot_1.html"
                    )
                    
                    lineplot(
                        df,
                        "annee",
                        "generosite",
                        "Lineplot Generosite",
                        "Annees",
                        "Generosite",
                        "bonheur_lineplot_2.html"
                    )
                    
                    boxplot(
                        df,
                        "score_bonheur",
                        "Boxplot Bonheur",
                        "x_title",
                        "y_title",
                        "bonheur_boxplot_1.html"
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
                    "title",
                    "x_title",
                    "y_title",
                    "corruption_barplot_1.html"
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
                    "title",
                    "x_title",
                    "y_title",
                    "crime_lineplot_1.html"
                    )
                    barplot(
                        df,
                        "annee",
                        "count",
                        "title",
                        "x_title",
                        "y_title",
                        "crime_lineplot_2.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "economie":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
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
                    "title",
                    "x_title",
                    "y_title",
                    "education_lineplot_1.html"
                    )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "meteo":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                
                try:
                    multiple_line_plot(
                        df,
                        "annee",
                        ["automne_tmin", "automne_tavg", "automne_tmax"],
                        "title",
                        "x_title",
                        "y_title",
                        "meteo_lineplot_1.html"
                    )
                
                    multiple_line_plot(
                        df,
                        "annee",
                        ["hiver_tmin", "hiver_tavg", "hiver_tmax"],
                        "title",
                        "x_title",
                        "y_title",
                        "meteo_lineplot_2.html"
                    )
                
                    multiple_line_plot(
                        df,
                        "annee",
                        ["ete_tmin", "ete_tavg", "ete_tmax"],
                        "title",
                        "x_title",
                        "y_title",
                        "meteo_lineplot_3.html"
                    )
                
                    multiple_line_plot(
                        df,
                        "annee",
                        ["printemps_tmin", "printemps_tavg", "printemps_tmax"],
                        "title",
                        "x_title",
                        "y_title",
                        "meteo_lineplot_4.html"
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
                        "title",
                        "x_title",
                        "y_title",
                        "religion_lineplot_1.html"
                    )
                    
                    for i in range(0, len(groups)):
                        boxplot(
                            df,
                            groups[i],
                            "title",
                            "x_title",
                            "y_title",
                            f"religion_boxplot_{i}.html"
                        )
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "sante":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "social":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "tourisme":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "transport":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
                    print(f"Graph saved successfully to {output_file}")
                except Exception as e:
                    print(f"Error while generating or saving graph: {e}")
        case "travail":
            if not df.empty:
                print("Generating bar chart for 'agroalimentaire' domain...")
                # Graphs here
                try:
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
        

"""
TO DO:
    - make function for:
        * multiple boxplot
    - decide graphs for 'economie'
    - finish graphs
"""
