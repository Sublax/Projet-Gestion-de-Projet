<?php
include '../bd.php';
session_start();

// Vérifier si les champs du formulaire sont remplis
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Récupérer les valeurs du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Connexion à la base de données
    $conn = getBD();
    
    // Préparer la requête SQL pour vérifier les informations de l'utilisateur (soit username soit mail)
    $sql = "SELECT * FROM clients WHERE nom_utilisateur = :u OR mail = :m";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['u' => $username, 'm' => $username]);

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Vérifier si le mot de passe est correct
        if (password_verify($password, $user['mdp'])) {
            // Mot de passe correct, démarrer la session
            $_SESSION['client'] = $user['id_client'];
            $_SESSION['username'] = $user['nom_utilisateur'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['location'] = $user["localisation"];
            
            echo "1";
            exit;
            //header("Location: ../index.php"); // Rediriger vers la page d'accueil
            //exit();
        } else {
            // Mot de passe incorrect
            echo "Mot de passe incorrect.";
            exit;
        }
    } else {
        // Utilisateur non trouvé
        echo "Informations incorrectes.";
        exit;
    }
} else {
    echo "Veuillez remplir tous les champs.";
    exit;
}
?>
