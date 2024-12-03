<?php
// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Get the country name
$country = $data['country'] ?? null;

if (!$country) {
    http_response_code(400);
    echo json_encode(["error" => "Country not specified"]);
    exit;
}

// Execute the Python script with the country name
$command = escapeshellcmd("python generate_graphs.py " . escapeshellarg($country));
$output = [];
$return_var = null;

exec($command, $output, $return_var);

if ($return_var !== 0) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to generate graphs"]);
    exit;
}

// Respond with success message
echo json_encode(["message" => "Graphs for {$country} generated successfully"]);
?>
