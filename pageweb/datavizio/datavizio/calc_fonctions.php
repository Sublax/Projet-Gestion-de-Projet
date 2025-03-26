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
        // Extracting domain and measure (adn measure type for meteo) from question
        if(count(explode('-', $question)) > 2){
            $parts = explode('-', $question);
            if($parts[0] === 'meteo'){
                $domain = $parts[0];
                $measure = $parts[1];
                $measure_type = $parts[2];
            } else {
                $measure = implode('-', array_slice($parts, -2)); // Last two parts as measure
                $domain = implode('-', array_slice($parts, 0, -2)); // Everything else as domain
                $measure_type = 'no_need';
            }
        }else{
            list($domain, $measure) = explode('-', $question);
            $measure_type = 'no_need';
        }
        // Treating "economie"
        if ($domain === 'economie' && strpos($measure, "_") !== false) {
            $measure = str_replace("_", " ", $measure);
        }

        // Normalizing data (meteo special case)
        if($domain === 'meteo'){
            // Mean value of t_avg for each country
            $meanTemps = calculateMeanValue($data[$domain], $measure);
            // Comparing to the value of the question
            array_walk($meanTemps, function(&$value) use ($questions, $measure, $measure_type) {
                if ($measure_type === 'minimal') {
                    $value = ($value < $questions["meteo-".$measure."-".$measure_type]) ? 0 : 1;
                } elseif ($measure_type === 'maximal') {
                    $value = ($value > $questions["meteo-".$measure."-".$measure_type]) ? 0 : 1;
                } elseif ($measure_type === 'middle_10_20') {
                    $value = ($value < $questions["meteo-".$measure."-".$measure_type] && $value > 10) ? 1 : 0;
                } elseif ($measure_type === 'middle_20_30') {
                    $value = ($value < $questions["meteo-".$measure."-".$measure_type] && $value > 20) ? 1 : 0;
                } else {
                    $value = 0;
                }
            });
            
            // Adding to the country score according to the comparison
            foreach($normalizedData as $country => $value){
                if (array_key_exists($country, $countryScores)) {
                    $countryScores[$country] += $value;
                }
            }
        }else{
            $normalizedData = normalizeData($data[$domain], $measure); 

             // Calculating country scores (checks for country existence in the data)
            foreach($normalizedData as $country => $value){
                if (array_key_exists($country, $countryScores)) {
                    $countryScores[$country] += $value;
                }
            }
        }
    }

    // Obtaining result
    return $countryScores;
}

function calculateScoresDebug($questions, $data) {
    // Initialize country scores array containing all countries with a score of 0
    $countryScores = [];
    foreach ($data['pays'] as $country) {
        $countryScores[$country['nom_pays']] = 0;
    }

    // Debug: Show available questions
    error_log("Available questions: " . print_r(array_keys($questions), true));
    error_log("Available valeurs: " . print_r(($questions), true));

    // Calculating score for each country based on the domain of the questions
    foreach ($questions as $question => $questionValue) {
        // Extracting domain and measure (and measure type for meteo) from question
        $parts = explode('-', $question);
        if (count($parts) > 2) {
            if ($parts[0] === 'meteo') {
                $domain = $parts[0];
                $measure = $parts[1];
                $measure_type = $parts[2];
            } else {
                $measure = implode('-', array_slice($parts, -2)); // Last two parts as measure
                $domain = implode('-', array_slice($parts, 0, -2)); // Everything else as domain
                $measure_type = 'no_need';
            }
        } else {
            list($domain, $measure) = explode('-', $question);
            $measure_type = 'no_need';
        }

        // Debug: Show which domain and measure are being processed
        error_log("Processing domain: $domain, Measure: $measure, Measure Type: $measure_type");

        // Treating "economie"
        if ($domain === 'economie' && strpos($measure, "_") !== false) {
            $measure = str_replace("_", " ", $measure);
        }

        // Normalizing data (meteo special case)
        if ($domain === 'meteo') {
            if (!isset($data[$domain])) {
                error_log("⚠ Warning: Domain '$domain' is missing in \$data.");
                continue;
            }

            // Mean value of t_avg for each country
            $meanTemps = calculateMeanValue($data[$domain], $measure);

            // Debug: Show computed mean temperatures
            error_log("Mean temperatures computed for $measure: " . print_r($meanTemps, true));

            // Ensure the key exists in $questions
            $questionKey = "meteo-".$measure."-".$measure_type;
            if (!isset($questions[$questionKey])) {
                error_log("⚠ Warning: Missing '$questionKey' in \$questions.");
                continue;
            }

            // Compare values and normalize data
            array_walk($meanTemps, function (&$value) use ($questions, $measure, $measure_type, $questionKey) {
                if ($measure_type === 'minimal') {
                    $value = ($value < $questions[$questionKey]) ? 2.5 : 0;
                } elseif ($measure_type === 'maximal') {
                    $value = ($value > $questions[$questionKey]) ? 2.5 : 0;
                } elseif ($measure_type === 'middle_0_10') {
                    $value = ($value < $questions[$questionKey] && $value > 0) ? 2.5 : 0;
                } elseif ($measure_type === 'middle_10_20') {
                    $value = ($value < $questions[$questionKey] && $value > 10) ? 2.5 : 0;
                } elseif ($measure_type === 'middle_20_30') {
                    $value = ($value < $questions[$questionKey] && $value > 20) ? 2.5 : 0;
                } elseif ($measure_type === 'middle_5_15') {
                    $value = ($value < $questions[$questionKey] && $value > 5) ? 2.5 : 0;
                } elseif ($measure_type === 'middle_15_25') {
                    $value = ($value < $questions[$questionKey] && $value > 15) ? 2.5 : 0;
                } else {
                    $value = 0;
                }
            });

            // Debug: Show normalized data for meteo
            error_log("Normalized meteo data for $measure: " . print_r($meanTemps, true));

            // Adding to the country score according to the comparison
            foreach ($meanTemps as $country => $value) {
                if (array_key_exists($country, $countryScores)) {
                    error_log("Adding score for country $country: +$value");
                    $countryScores[$country] += $value;
                }
            }
        } else if (($domain === 'agroalimentaire' && $measure === 'costhealthydiet')
                    || ($domain === 'travail' && $measure === 'sans_emploi_femme')
                    || ($domain === 'travail' && $measure === 'sans_emploi_homme')
                    || ($domain === 'corruption' && $measure === 'corruption_politique')
                    || ($domain === 'sante' && $measure === 'mort')
                    || ($domain === 'economie' && $measure === 'Household consumption expenditure')
                    || ($domain === 'economie' && $measure === 'Total Value Added')
                    || ($domain === 'crime' && $measure === 'taux')
                ){
            
            // Flipping normalized values (1 - norm. value)
            if (!isset($data[$domain])) {
                error_log("⚠ Warning: Domain '$domain' is missing in \$data.");
                continue;
            }

            // Normalize data
            $normalizedData = normalizeData($data[$domain], $measure);

            // Debug: Show normalized data for non-meteo cases
            error_log("Normalized data for $measure: " . print_r($normalizedData, true));

            // Adding scores
            foreach ($normalizedData as $country => $value) {
                if (array_key_exists($country, $countryScores)) {
                    $scoreToAdd = 5 * $questionValue * (1 - $value);
                    error_log("Adding score for country $country: +$scoreToAdd");
                    $countryScores[$country] += $scoreToAdd;
                }
            }

        } else {
            if (!isset($data[$domain])) {
                error_log("⚠ Warning: Domain '$domain' is missing in \$data.");
                continue;
            }

            // Normalize data
            $normalizedData = normalizeData($data[$domain], $measure);

            // Debug: Show normalized data for non-meteo cases
            error_log("Normalized data for $measure: " . print_r($normalizedData, true));

            // Adding scores
            foreach ($normalizedData as $country => $value) {
                if (array_key_exists($country, $countryScores)) {
                    $scoreToAdd = 5 * $questionValue * $value;
                    error_log("Adding score for country $country: +$scoreToAdd");
                    $countryScores[$country] += $scoreToAdd;
                }
            }
        }

        // Handling 'Turkey' name case
        if (array_key_exists('Türkiye', $countryScores)) {
            $countryScores['Turkey'] = $countryScores['Türkiye'];
            unset($countryScores['Türkiye']);
        } else {
            echo "The key 'Türkiye' does not exist in the array.";
        }
    }

    // Debug: Show final scores
    error_log("Final country scores: " . print_r($countryScores, true));
    error_log("============================================================");

    // Returning results
    return $countryScores;
}

?>