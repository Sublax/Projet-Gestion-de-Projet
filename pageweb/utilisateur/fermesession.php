<?php 
// Ferme la session pays_predis de l'utilisateur
session_start();
if (isset($_SESSION["pays_predis"])) {
    $_SESSION["pays_predis"] = array();
    header('Location: flag.php');
}else{
    echo "0";
} ?>