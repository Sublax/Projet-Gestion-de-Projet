import plotly.express as px
import pandas as pd
import os

# Sample data
df = pd.DataFrame({
    "country_name": ["France", "Germany", "Norway", "Spain", "Romania"],
    "suitability_score": [10, 30, 50, 70, 90]
})

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
