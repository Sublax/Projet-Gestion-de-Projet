// get_third_graph_data.php
<?php
require_once 'bd.php';
require_once 'nom_pays.php';

$table = $_GET['table'];
$year = $_GET['year'];

// Obtener los datos correspondientes de la tabla seleccionada
$data = getTableData($table);  // Función que devuelve los datos de la tabla

// Filtrar los datos por año
$filteredData = array_filter($data, function ($row) use ($year) {
    return $row['annee'] == $year;
});

// Preparar las etiquetas y valores para el gráfico
$labels = array_map(function ($row) {
    return $row['nom_pays'];  // Asumimos que 'nom_pays' es el nombre del país
}, $filteredData);

$values = array_map(function ($row) use ($table) {
    return isset($row[$table]) ? floatval($row[$table]) : 0;
}, $filteredData);

// Devolver los datos como JSON
echo json_encode(['labels' => $labels, 'values' => $values]);
?>
