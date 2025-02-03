<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Get raw POST data
$data = json_decode(file_get_contents('php://input'), true);
$country = $data['country'] ?? null;

if (!$country) {
    http_response_code(400);
    echo json_encode(["error" => "Country not specified"]);
    exit;
}

// Define Python path and script
// !!! To modify with the paths oh the server !!!
$pythonPath = "/usr/bin/python3";
$scriptPath = escapeshellarg("~/Documents/GitGestionProjet/pageweb/datavizio/generate_graphs.py");

// Command execution with output capturing
$command = "\"$pythonPath\" $scriptPath " . escapeshellarg($country) . " 2>&1";
$output = [];
$return_var = null;

exec($command, $output, $return_var);

// Debugging Output
$response = [
    "command" => $command,
    "output" => $output,
    "exit_status" => $return_var
];

echo json_encode($response);
?>
