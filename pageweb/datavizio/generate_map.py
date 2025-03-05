import plotly.express as px
import pandas as pd
import os
import json
import numpy as np

# Getting json data
# Use absolute path to locate the JSON file
json_file = os.path.join(os.path.dirname(__file__), "country_scores.json")

# Verify if file exists before reading
if not os.path.exists(json_file):
    print(f"Error: JSON file not found at {json_file}")
else:
    try:
        with open(json_file, "r") as file:
            country_scores = json.load(file)
            countries = country_scores.keys()
            values = country_scores.values()

            df = pd.DataFrame({
                "country_name": list(countries),
                "suitability_score": list(values)
            })

            print(df)
    except json.JSONDecodeError:
        print("Error: Invalid JSON format!")

# Specifying where to create map.html
current_dir = os.path.dirname(os.path.abspath(__file__))
output_file = os.path.join(current_dir, "map.html")

# Customizing country colors
custom_colors = "RdYlGn"

# Compute dynamic tick values from your DataFrame
min_score = df["suitability_score"].min()
max_score = df["suitability_score"].max()
# Generate 5 evenly spaced tick values
tickvals = np.linspace(min_score, max_score, 5).tolist()
# Fabricating legend text
labels = ["❌ Very Low", "⚠️ Low", "😐 Medium", "✅ High", "🌟 Very High"]
ticktext = [f"{label} ({val:.1f})" for label, val in zip(labels, tickvals)]

# Compute top 10 countries (assuming higher scores are better)
top10_df = df.sort_values("suitability_score", ascending=False).head(10)
top10_html = "<div id='top-countries'><h2>Top 10 Countries</h2><ul>"
for _, row in top10_df.iterrows():
    top10_html += f"<li>{row['country_name']}: {row['suitability_score']:.1f}</li>"
top10_html += "</ul></div>"

fig = px.choropleth(
    df,
    locations="country_name",
    locationmode="country names",
    color="suitability_score",
    hover_name="country_name",
    custom_data=["country_name"],  # Pass country name for click events
    color_continuous_scale=custom_colors
)

fig.update_geos(
    projection_type="natural earth",
    showcountries=True,
    countrycolor="black",
    showcoastlines=True,
    showocean=True,
    oceancolor="lightblue",
    showframe=False
)

fig.update_layout(
    margin={"r": 0, "t": 50, "l": 0, "b": 0},
    coloraxis_colorbar=dict(
        title="🌡️ Suitability Score",
        tickvals=tickvals,
        ticktext=ticktext
    ),
    modebar=dict(
        orientation="h",  # Change to "h" for horizontal
        bgcolor="rgba(255,255,255,0.8)",  # Semi-transparent background
        color="black",  # Button color
        activecolor="blue"  # Button hover color
    ),
    geo=dict(
        projection_type="natural earth",  # Default projection
        showcountries=True,
        countrycolor="black",
        showcoastlines=True,
        showocean=True,
        oceancolor="lightblue",
        showframe=False
    ),
    updatemenus=[  # Adding buttons for continent zoom
        dict(
            type="buttons",
            direction="down",
            x=0.1,
            y=1.15,
            buttons=[
                dict(
                    label="🌍 World",
                    method="relayout",
                    args=[{"geo.center.lon": 0, "geo.center.lat": 20, "geo.zoom": 1}]
                ),
                dict(
                    label="🌎 North America",
                    method="relayout",
                    args=[{"geo.center.lon": -100, "geo.center.lat": 50, "geo.zoom": 2.5}]
                ),
                dict(
                    label="🌎 South America",
                    method="relayout",
                    args=[{"geo.center.lon": -60, "geo.center.lat": -15, "geo.zoom": 2.5}]
                ),
                dict(
                    label="🌍 Europe",
                    method="relayout",
                    args=[{"geo.center.lon": 10, "geo.center.lat": 50, "geo.zoom": 3}]
                ),
                dict(
                    label="🌍 Africa",
                    method="relayout",
                    args=[{"geo.center.lon": 20, "geo.center.lat": 0, "geo.zoom": 2.5}]
                ),
                dict(
                    label="🌏 Asia",
                    method="relayout",
                    args=[{"geo.center.lon": 100, "geo.center.lat": 40, "geo.zoom": 2.5}]
                ),
                dict(
                    label="🌏 Oceania",
                    method="relayout",
                    args=[{"geo.center.lon": 140, "geo.center.lat": -25, "geo.zoom": 3}]
                )
            ]
        )
    ]
)



# Generate the map as HTML
map_html = fig.to_html(full_html=False, include_plotlyjs='cdn')

custom_css = """
<style>
/* General Page Styling */
body {
    background: linear-gradient(to bottom, #ffffff, #e6f7ff);
    font-family: 'Arial', sans-serif;
    text-align: center;
    color: #333;
    margin: 0;
    padding: 0;
    position: relative; /* Needed for absolute positioning of child elements */
}

/* Page Title */
h1 {
    font-size: 28px;
    margin-top: 20px;
    font-weight: bold;
    color: #003366;
    margin-bottom: 10px;
}

/* Updated Map Container - Full Width */
#map-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100vw; /* Takes full screen width */
    height: 90vh; /* Increased height */
    padding: 10px;
    overflow: hidden; /* Prevents extra scrollbars */
}

/* Ensure the Plotly Graph Expands Properly */
.js-plotly-plot {
    width: 95vw !important;  /* Force full width */
    height: 85vh !important; /* Force full height */
    border-radius: 10px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    background: white;
    padding: 10px;
}

/* Top Countries List Positioned at Bottom Left */
#top-countries {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.9);
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
    max-height: 40vh;
    overflow-y: auto;
    text-align: left;
    z-index: 1000;
}
#top-countries h2 {
    margin: 0 0 5px 0;
    font-size: 18px;
}
#top-countries ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
#top-countries li {
    margin-bottom: 4px;
    font-size: 14px;
}

/* Button Container */
.button-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 10px;
}

/* Stylish Zoom Buttons */
.map-button {
    background: #0056b3;
    color: white;
    border: none;
    padding: 12px 18px;
    margin: 5px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.map-button:hover {
    background: #003366;
    transform: scale(1.05);
}

.footer {
    text-align: center;
    font-size: 14px;
    color: #666;
    margin-top: 20px;
}

/* Increase the modebar size */
.js-plotly-plot .modebar {
    background: rgba(255, 255, 255, 0.9) !important;  /* White background */
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
    transform: scale(1.2); /* Makes the entire bar bigger */
}

/* Make modebar buttons larger */
.js-plotly-plot .modebar-btn {
    font-size: 20px !important; /* Increase icon size */
    padding: 10px !important;  /* Increase button padding */
    width: 50px !important; /* Make buttons wider */
    height: 50px !important; /* Make buttons taller */
}

/* Change button hover effect */
.js-plotly-plot .modebar-btn:hover {
    background: rgba(0, 102, 255, 0.8) !important; /* Blue hover effect */
    color: white !important;
    transform: scale(1.1); /* Slight hover effect */
}

/* Ensure modebar is always visible */
.js-plotly-plot .modebar {
    opacity: 1 !important;
}

/* Smooth hover effect for the map */
.js-plotly-plot path {
    transition: filter 0.2s ease-in-out, transform 0.1s ease-in-out;
}

/* Apply glow effect when hovering */
.js-plotly-plot path:hover {
    filter: drop-shadow(0px 0px 5px rgba(0, 102, 255, 0.6));
    transform: scale(1.01); /* Slight zoom effect */
}


</style>
"""


custom_js_graphs = """
<script>

    document.addEventListener('DOMContentLoaded', function () {
    function initializePlotlyEvents() {
        const plotlyGraphDiv = document.querySelector(".plotly-graph-div"); // Select the Plotly div dynamically

        if (!plotlyGraphDiv) {
            console.error("Plotly graph not loaded yet. Retrying...");
            setTimeout(initializePlotlyEvents, 500); // Retry after 500ms
            return;
        }

        console.log("✅ Plotly graph detected. Attaching event listeners...");

        // Click event listener for the Plotly map
        plotlyGraphDiv.on('plotly_click', function (data) {
            if (data.points.length > 0) {
                let country = data.points[0].customdata[0]; // Get country name

                // Convert "Turkey" to "Türkiye"
                if (country === "Turkey") {
                    country = "Türkiye";
                }

                console.log(`🌍 Selected country: ${country}`);

                // Redirect to graph.php with selected country as a parameter
                window.location.href = `dashboard.php?country=${encodeURIComponent(country)}`;
            }
        });
    }

    // Start checking if the graph exists
    initializePlotlyEvents();
});

</script>
"""


# HTML Content with Embedded CSS and JavaScript
html_content = f"""
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Suitability Map</title>
    {custom_css}  
</head>
<body>

    <h1>🌍 Find Your Perfect Country ✈️</h1>

    <div id="map-container">{map_html}</div>

    <!-- Top 10 Countries List -->
    {top10_html}

    <div class="footer">
        <p>Data sourced from global statistics. Click on a country to see details.</p>
    </div>

    {custom_js_graphs}

</body>
</html>
"""

# Write the final HTML file
with open(output_file, "w", encoding="utf-8") as f:
    f.write(html_content)