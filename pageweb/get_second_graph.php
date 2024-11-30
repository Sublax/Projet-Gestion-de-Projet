<?php
require "./bd.php";
$bdd = getBD();
header('Content-Type: application/json');

// Récupérer les pays ou d'autres filtres envoyés par JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$countries = $data['countries'];

if (empty($countries)) {
    echo json_encode([
        'labels' => [],
        'values' => []
    ]);
    exit;
}

// Exemple : Récupérer un autre indicateur
$placeholders = implode(',', array_fill(0, count($countries), '?'));
$sql = "SELECT annee, taux_classe_primaire  
        FROM education 
        WHERE id_pays IN ($placeholders)";
$stmt = $bdd->prepare($sql);
$stmt->execute($countries);

$labels = [];
$values = [];
while ($row = $stmt->fetch()) {
    $labels[] = $row['annee'];
    $values[] = $row['taux_classe_primaire'];
}

echo json_encode([
    'labels' => $labels,
    'values' => $values
]);
?>
