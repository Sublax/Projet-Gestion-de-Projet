<?php
// Incluir le fichier de connexion
include '../bd.php';

// Récupérer la connexion à la base de données
$conn = getBD();

// Récupérer les données du formulaire
$nom_utilisateur = $_POST['username'];
$nom = $_POST['first_name'];
$prenom = $_POST['last_name'];
$mail = $_POST['email'];
$localisation = $_POST['location'];
$mdp = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

// Préparer la requête SQL pour insérer les données dans la table clients
$sql = "INSERT INTO clients (nom_utilisateur, nom, prenom, mail, localisation, mdp) VALUES (?, ?, ?, ?, ?, ?)";

// Utiliser une requête préparée pour éviter les injections SQL
$stmt = $conn->prepare($sql);
$stmt->execute([$nom_utilisateur, $nom, $prenom, $mail, $localisation, $mdp]);

// Vérifier si l'insertion a réussi
if ($stmt) {
    echo "Inscription réussie !";
    header("Location: login.php"); // Rediriger vers la page de connexion après l'inscription
    exit();
} else {
    echo "Erreur lors de l'inscription.";
}

// Fermer la connexion
$conn = null;
?>
