<?php
require_once "../bd.php";
require_once "calc_fonctions.php";

// country score = sum i=1->n (question values_i x country data_i)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Getting questions values from the form
    $questions = [];
    $totalQuestions = 16; // Adjust this number based on your form
    for ($i = 1; $i <= $totalQuestions; $i++) {
        $questions["question$i"] = $_POST["question$i"] ?? 'Default Value';
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

    // Convert country scores to JSON

    // Write JSON data to a file

    // ==================== Testing Phase ====================

    // Test data
    $test_questions = [
        'agroalimentaire-cleanfuelandcookingequipment' => 0.5,
        'agroalimentaire-costhealthydiet' => 0.7,
        'bonheur-score_bonheur' => 0.3,
        'bonheur-generosite' => 0.9,
        'corruption-rule_of_law' => 0.2
    ];

    // Calculating country scores
    $countryScores = calculateScores($test_questions, $data);

    // Convert to JSON
    $json_data = json_encode($countryScores, JSON_PRETTY_PRINT);

    // Write JSON data to a file
    file_put_contents("country_scores.json", $json_data);

    //========================================================

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
