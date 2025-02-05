import plotly.express as px
import pandas as pd
import os
import json

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

current_dir = os.path.dirname(os.path.abspath(__file__))
output_file = os.path.join(current_dir, "map.html")

fig = px.choropleth(
    df,
    locations="country_name",
    locationmode="country names",
    color="suitability_score",
    hover_name="country_name",
    custom_data=["country_name"],  # Pass country name for click events
    color_continuous_scale="Viridis",
    title="Country Suitability Based on Questionnaire"
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
    title=dict(
        text="Country Suitability Based on Questionnaire",
        x=0.5,
        xanchor="center",
        font=dict(size=20)
    ),
    margin={"r": 0, "t": 50, "l": 0, "b": 0},
    coloraxis_colorbar=dict(
        title="Suitability Score",
        tickvals=[10, 30, 50, 70, 90],
        ticktext=["Very Low", "Low", "Medium", "High", "Very High"]
    )
)

custom_js_graphs = """
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapElement = document.querySelector('.js-plotly-plot');

        if (mapElement) {
            mapElement.on('plotly_click', function (eventData) {
                const country = eventData.points[0].customdata[0];
                console.log(`Selected country: ${country}`);

                // Send the country name to the PHP backend
                fetch('process_country.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ country: country })
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error("Failed to process the country.");
                    }
                })
                .then(data => {
                    console.log(data.message);
                    // Redirect to the generated country page
                    window.location.href = `country.php?country=${encodeURIComponent(country)}`;
                })
                .catch(error => console.error("Error:", error));
            });
        } else {
            console.error("Map element not found.");
        }
    });
</script>
"""

with open(output_file, "w", encoding="utf-8") as f:
    f.write(fig.to_html(full_html=True))
    f.write(custom_js_graphs)
