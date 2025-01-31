<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Algorithm part
    $question1 = $_POST['question1'] ?? 'Default Value';
    $question2 = $_POST['question2'] ?? 'Default Value';

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
