<?php
require "./bd.php";
$bdd = getBD();
header('Content-Type: application/json');

// Récupérer les pays sélectionnés depuis JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$countries = $data['countries'];

if (empty($countries)) {
    echo json_encode([
        'labels' => [],
        'values' => []
    ]);
    exit;
}

// Préparer une requête SQL filtrée
$placeholders = implode(',', array_fill(0, count($countries), '?'));
$sql = "SELECT annee, cleanfuelandcookingequipment 
        FROM agroalimentaire 
        WHERE id_pays IN ($placeholders)";
$stmt = $bdd->prepare($sql);
$stmt->execute($countries);

// Construire les données pour le graphique
$labels = [];
$values = [];
while ($row = $stmt->fetch()) {
    $labels[] = $row['annee'];
    $values[] = $row['cleanfuelandcookingequipment'];
}

// Retourner les données JSON
echo json_encode([
    'labels' => $labels,
    'values' => $values
]);
?>
