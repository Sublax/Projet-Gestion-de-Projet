<?php
session_start();
// Si aucune sessions active on en créer une : 
if (!isset($_SESSION["pays_predis"])) {
    $_SESSION["pays_predis"] = array();
}

$data = json_decode(file_get_contents("php://input"), true);
if(isset($data)){
    // on évite la liste de liste
    foreach($data["country"] as $pays){
        $_SESSION["pays_predis"][] = $pays;
    }

    echo json_encode(['message' => 'Pays ajouté avec succès']);
}else{
    echo json_encode(['message' => "Erreur lors de l'ajout"]);
}