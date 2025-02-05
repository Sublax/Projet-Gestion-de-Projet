<?php

function calculateMeanValue($data, $measure) {
    $countryValue = [];

    foreach ($data as $row) {
        $country = $row['nom_pays']; // Extract country name
        $value = $row[$measure]; // Extract measure

        // Initialize array for each country
        if (!isset($countryValue[$country])) {
            $countryValue[$country] = ['total_value' => 0, 'count' => 0];
        }

        // Sum up population values and count the entries
        $countryValue[$country]['total_value'] += $value;
        $countryValue[$country]['count']++;
    }

    // Compute mean population per country
    $meanValues = [];
    foreach ($countryValue as $country => $values) {
        $meanValues[$country] = $values['total_value'] / $values['count'];
    }

    return $meanValues;
}


function normalizeData($data, $measure) {
    // Step 1: Aggregate values per country (calculate mean across years)
    $countryValues = [];

    foreach ($data as $row) {
        $country = $row['nom_pays'];
        $value = $row[$measure];

        if (!isset($countryValues[$country])) {
            $countryValues[$country] = ['total' => 0, 'count' => 0];
        }

        $countryValues[$country]['total'] += $value;
        $countryValues[$country]['count']++;
    }

    // Compute mean values per country
    $meanValues = [];
    foreach ($countryValues as $country => $values) {
        $meanValues[$country] = $values['total'] / $values['count'];
    }

    // Step 2: Check if normalization is needed
    $min = min($meanValues);
    $max = max($meanValues);

    // If values are already between 0 and 1, return the mean values without normalization
    if ($min >= 0 && $max <= 1) {
        return $meanValues; // Data is already normalized, no need to scale
    }

    // Step 3: Apply normalization only if needed
    if ($max == $min) return array_fill_keys(array_keys($meanValues), 1); // Avoid division by zero

    $normalizedData = [];
    foreach ($meanValues as $country => $value) {
        $normalizedData[$country] = ($value - $min) / ($max - $min);
    }

    return $normalizedData;
}

function calculateScores($questions, $data) {
    // Initialize country scores array containing all countries with a score of 0
    $countryScores = [];
    foreach($data['pays'] as $country){
        $countryScores[$country['nom_pays']] = 0;
    }

    // Calculating score for each country based on the domain of the questions
    foreach(array_keys($questions) as $question){
        // Extracting domain and measure from question
        list($domain, $measure) = explode('-', $question);

        // Normalizing data
        $normalizedData = normalizeData($data[$domain], $measure);

        // Calculating country scores (checks for country existence in the data)
        foreach($normalizedData as $country => $value){
            if (array_key_exists($country, $countryScores)) {
                $countryScores[$country] += $value;
            }
        }
    }
    // Obtaining result
    return $countryScores;
}

function calculateScoresDebug($questions, $data) {
    echo "Starting calculateScoresDebug...\n";

    // Initialize country scores array containing all countries with a score of 0
    $countryScores = [];
    foreach ($data['pays'] as $country) {
        $countryScores[$country['nom_pays']] = 0;
    }
    echo "Initialized country scores: \n";
    echo "<pre>" . print_r($countryScores, true) . "</pre>";

    // Processing each question
    foreach (array_keys($questions) as $question) {
        echo "\nProcessing question: $question\n";

        // Extract domain and measure
        $parts = explode('-', $question);
        if (count($parts) !== 2) {
            echo "Skipping invalid question format: $question\n";
            continue;
        }

        list($domain, $measure) = $parts;
        echo "Extracted domain: $domain, measure: $measure\n";

        // Check if domain exists in data
        if (!isset($data[$domain])) {
            echo "Warning: Domain '$domain' not found in data. Skipping.\n";
            continue;
        }

        // Normalize data
        $normalizedData = normalizeData($data[$domain], $measure);
        echo "Normalized data for domain '$domain':\n";
        echo "<pre>" . print_r($normalizedData, true) . "</pre>";

        // Update country scores
        foreach ($normalizedData as $country => $value) {
            if (array_key_exists($country, $countryScores)) {
                echo "Updating score for $country: +$value\n";
                $countryScores[$country] += $value;
            } else {
                echo "Warning: Country '$country' not found in countryScores. Skipping.\n";
            }
        }

        // Show updated scores
        echo "Updated country scores:\n";
        echo "<pre>" . print_r($countryScores, true) . "</pre>";
    }

    // Final result
    echo "\nFinal country scores:\n";
    echo "<pre>" . print_r($countryScores, true) . "</pre>";

    return $countryScores;
}
?>