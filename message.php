<?php
// Incluir el archivo de conexión
include 'bd.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php?message=Vous devez être connecté pour envoyer un message");
    exit();
}

// Récupérer les données du formulaire
$id_client = $_SESSION['client'];
$message = isset($_POST['msg']) ? trim($_POST['msg']) : '';
$objet = isset($_POST['objet']) ? trim($_POST['objet']) : '';
$date = date('Y-m-d H:i:s'); // Obtenemos la fecha y hora actual

// Vérifier que le message n'est pas vide
if (empty($message) || empty($objet)) {
    echo "Veuillez remplir tous les champs.";
    exit();
}

// Connexion à la base de données
$conn = getBD();

// Préparer la requête SQL pour insérer les données dans la table messages_contact
$sql = "INSERT INTO messages_contact (id_client, message, date) VALUES (:id_client, :message, :date)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_client', $id_client);
$stmt->bindParam(':message', $message);
$stmt->bindParam(':date', $date);

// Exécuter la requête
if ($stmt->execute()) {
    echo "Message envoyé avec succès !";
    header("Location: index.php?message=Message envoyé avec succès");
    exit();
} else {
    echo "Erreur lors de l'envoi du message.";
}

// Fermer la connexion
$conn = null;
?>
