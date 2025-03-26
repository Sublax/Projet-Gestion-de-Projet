<?php
require_once "../bd.php";
require_once "calc_fonctions.php";

// country score = sum i=1->n (question values_i x country data_i)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Getting questions values from the form
    foreach ($_POST as $key => $value) {
        // Handle checkbox arrays correctly
        $questions[$key] = $value;
    }

    /* Spliting questions into:
        - pre_questions: questions before the split_key
        - choix: the split_key
        - stats_questions: questions after the split_key
    */
    $split_key = 'choix';
    $found = False;
    foreach ($questions as $key => $value) {
        if($key === $split_key){
            $found = True;
            $choix = $value;
            continue;
        }
        if(!$found){
            $pre_questions[$key] = $value;
        } else {
            $stats_questions[$key] = $value;
        }
    }
    

    // DB connection
    $bdd = getBD(); 

    // Fetching data from the database
    $tables = [
        'agroalimentaire',
        'bonheur',
        'corruption',
        'crime',
        'economie',
        'education',
        'meteo',
        'religion',
        'sante',
        'social',
        'tourisme',
        'transport',
        'travail',
        'pays'
    ];

    $data = [];
    foreach($tables as $table){
        $data[$table] = getData($bdd, $table);
    }
     
    // Calculating country scores
    $countryScores = calculateScoresDebug($stats_questions, $data);

    // Convert country scores to JSON
    $json_data = json_encode($countryScores, JSON_PRETTY_PRINT);

    // Write JSON data to a file
    file_put_contents("country_scores.json", $json_data);

    // Pass form data to the Python script (if needed)
    $pythonPath = "C:/Users/bogda/AppData/Local/Programs/Python/Python313/python.exe";
    // Path to be replaced with the actual path to python.exe of the server
    $pythonCommand = escapeshellcmd("$pythonPath generate_map.py");
    $output = shell_exec($pythonCommand);

    // Redirect to the generated map HTML file
    header("Location: map.html");
    exit;
    
}
?>