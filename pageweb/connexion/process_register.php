<?php
include '../bd.php';
session_start();

// Récupérer la connexion à la base de données
try {
    $conn = getBD();
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer les données du formulaire
$nom_utilisateur = $_POST['username'];
$nom = $_POST['first_name'];
$prenom = $_POST['last_name'];
$mail = $_POST['email'];
$localisation = $_POST['location'];
$mdp = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Vérifier si le nom_utilisateur ou le mail existe déjà
$sql_check = "SELECT COUNT(*) FROM clients WHERE nom_utilisateur = ? OR mail = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([$nom_utilisateur, $mail]);
$existingUser = $stmt_check->fetchColumn();

if ($existingUser > 0) {
    $_SESSION['error'] = "Nom d'utilisateur ou email déjà utilisé. Veuillez en choisir un autre.";
    header("Location: register.php");
    exit();
}

// Préparer la requête SQL pour insérer les données dans la table clients
try {
    $sql = "INSERT INTO clients (nom_utilisateur, nom, prenom, mail, localisation, mdp) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nom_utilisateur, $nom, $prenom, $mail, $localisation, $mdp]);

    // Si l'insertion réussit, rediriger vers la page de connexion
    $_SESSION['success'] = "Inscription réussie !";
    header("Location: login.php");
    exit();
} catch (PDOException $e) {
    echo "Erreur lors de l'inscription : " . $e->getMessage();
}

// Fermer la connexion
$conn = null;
?>
