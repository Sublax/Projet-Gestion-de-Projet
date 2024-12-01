<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Algorithm part
    $question1 = $_POST['question1'] ?? 'Default Value';
    $question2 = $_POST['question2'] ?? 'Default Value';

    // Pass form data to the Python script (if needed)
    $pythonCommand = escapeshellcmd("python generate_map.py");
    $output = shell_exec($pythonCommand);

    // Redirect to the generated map HTML file
    header("Location: map.html");
    exit;
}
?>
