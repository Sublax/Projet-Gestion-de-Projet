<?php
// Démarrer la session
session_start();

// Détruire toutes les données de la session
session_unset();
session_destroy();

// Rediriger l'utilisateur vers la page de connexion ou d'accueil
header("Location: ../index.php");
exit();
?>