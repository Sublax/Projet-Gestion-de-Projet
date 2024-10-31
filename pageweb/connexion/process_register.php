<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../bd.php';

// Initialiser un tableau pour stocker les erreurs
$errors = [];
$form_data = []; // Tableau pour stocker les données du formulaire

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $username = $_POST['username'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $location = $_POST['location'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Stocker les valeurs dans form_data pour les conserver en cas d'erreur
    $form_data = [
        'username' => $username,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'location' => $location,
    ];

    // Vérification des champs vides
    if (empty($username) || empty($firstName) || empty($lastName) || empty($email) || empty($location) || empty($password) || empty($confirmPassword)) {
        $errors[] = "Veuillez remplir tous les champs.";
    }

    // Vérification si les mots de passe correspondent
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier si le nom_utilisateur ou le mail existe déjà
    if (empty($errors)) {
        $conn = getBD();
        $sql_check = "SELECT COUNT(*) FROM clients WHERE nom_utilisateur = ? OR mail = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([$username, $email]);
        $existingUser = $stmt_check->fetchColumn();

        if ($existingUser > 0) {
            $errors[] = "Nom d'utilisateur ou email déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Hachage du mot de passe et insertion
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO clients (nom_utilisateur, nom, prenom, mail, localisation, mdp) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $firstName, $lastName, $email, $location, $hashedPassword]);

            // Vérifier si l'insertion a réussi
            if ($stmt) {
                $_SESSION['success'] = "Inscription réussie !";
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Erreur lors de l'inscription.";
            }
        }
        $conn = null;
    }
}

// Rediriger en cas d'erreurs
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $form_data;
    header("Location: register.php");
    exit();
}
?>
