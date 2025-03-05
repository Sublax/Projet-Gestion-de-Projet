<?php
include '../bd.php';
$bdd = getBD();
$data = [];

// Get the country name from either GET (URL) or POST (JSON)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the raw POST data
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    $pays = isset($input['country']) ? $input['country'] : null;
    // Log the received country (for debugging)
    error_log("Received Country (POST): " . $pays);
} else {
    // Default to GET method
    $pays = isset($_GET['country']) ? $_GET['country'] : null;
    // Log the received country (for debugging)
    error_log("Received Country (GET): " . $pays);
}

// Liste des pays disponibles
$sql_countries = "SELECT nom_pays FROM pays";
$result_countries = $bdd->query($sql_countries);
$data['countries'] = $result_countries->fetchAll(PDO::FETCH_ASSOC);

// ✅ Ensure $pays is set correctly (use first country if null)
if (!$pays && !empty($data['countries'])) {
    $pays = $data['countries'][0]['nom_pays'];
}

// ✅ Log final country name used in queries
error_log("Final Country Used: " . $pays);

// Getting data for each table while deleting the specified columns 
$data_tables = [
    'agroalimentaire' => ['nom_pays', 'id_agro', 'id_pays'],
    'bonheur'         => ['nom_pays', 'id_bonheur', 'id_pays', 'rang_bonheur'],
    'corruption'      => ['nom_pays', 'id_cor', 'id_pays'],
    'crime'           => ['nom_pays', 'id_crime', 'id_pays'],
    'economie'        => ['nom_pays', 'ID_eco', 'id_country', 'Currency', 'id_pays'],
    'education'       => ['nom_pays', 'id_educ', 'id_pays'],
    'meteo'           => ['nom_pays', 'id_meteo', 'id_pays'],
    'religion'        => ['nom_pays', 'id_religion', 'id_pays'],
    'sante'           => ['nom_pays', 'id_sante', 'id_pays'],
    'social'          => ['nom_pays', 'id_social', 'pib', 'id_pays'],
    'tourisme'        => ['nom_pays', 'id_tour', 'id_pays'],
    'transport'       => ['nom_pays', 'id_transport', 'id_pays'],
    'travail'         => ['nom_pays', 'id_travail', 'population', 'id_pays']
];

foreach($data_tables as $table => $columns){
    // Query datas for graphs
    if ($table === "economie"){
        $sql_data = "SELECT * 
                        FROM pays
                        LEFT JOIN $table ON $table.id_country = pays.id_pays 
                        WHERE nom_pays = :pays";
    }else{
        $sql_data = "SELECT * 
                        FROM pays 
                        LEFT JOIN $table ON $table.id_pays = pays.id_pays 
                        WHERE nom_pays = :pays";
    }

    $stmt = $bdd->prepare($sql_data);
    $stmt->execute(['pays' => $pays]);
    $data[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through each row and remove the columns defined in the mask
    foreach ($data[$table] as &$row) {
        foreach ($columns as $column) {
            unset($row[$column]);
        }
    }
}



// Set header and echo JSON encoded data
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>
