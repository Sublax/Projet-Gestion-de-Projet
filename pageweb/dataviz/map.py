import plotly.express as px
import pandas as pd
from plotly.offline import plot


# Sample data for suitability scores with country names
df = pd.DataFrame({
    "country_name": ["France", "Germany", "United States", "Spain", "Romania"],
    "suitability_score": [10, 30, 50, 70, 90]    # Suitability scores based on questionnaire
})

# Create a choropleth map using country names
fig = px.choropleth(df, locations="country_name", locationmode="country names",
                    color="suitability_score", hover_name="country_name",
                    color_continuous_scale=[
        (0.0, "red"),   # Corresponds to the minimum suitability score (10)
        (0.5, "yellow"),   # Corresponds to the average suitability score (50)
        (1.0, "green")  # Corresponds to the maximum suitability score (90)
    ])

fig.update_geos(fitbounds="locations")

# Display the plot
plot(fig)