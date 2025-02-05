import os
import json
import pandas as pd

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
