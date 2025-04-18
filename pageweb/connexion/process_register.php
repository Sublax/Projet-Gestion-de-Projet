<?php
session_start();
include '../bd.php';

function verify(){
    //Fonction qui vérifie que les variables existent bien
    if(isset($_POST['username'],$_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['country'],$_POST["visitedCountry"],$_POST["password"],$_POST['confirm_password'])){
        return TRUE;
    }else{
        return FALSE;
    }
}

function save($u,$fn,$ln,$e,$l,$p,$v){
    // Fonction qui sauvegarde les données
    //Pas besoin d'utiliser verify() car notre fonction sera appelé après avoir déjà effectué un test
    $bdd = getBD();
    $stmt = $bdd->prepare('SELECT mail FROM clients WHERE mail=:mail');
    $stmt->execute([
        ':mail' => $e,
    ]);
    $stmt2 = $bdd->prepare('SELECT nom_utilisateur FROM clients WHERE nom_utilisateur=:username');
    $stmt2->execute([
        ':username' => $u,
    ]);
    if($stmt->rowCount() > 0 || $stmt2->rowCount() > 0){
        $_SESSION['errorMessage'] = "Un compte possède déjà ce nom d'utilisateur ou l'adresse mail.";
        header("Location: ./register.php");
    }else{
        /*éviter les injections SQL :
        à la place de concatener on utilise execute()*/
        $sql = 'INSERT INTO clients (nom_utilisateur, nom, prenom, mail, localisation, mdp) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $bdd->prepare($sql);
        // On hashe le mdp avant de l'envoyer dans la bdd :
        $hash_mdp = password_hash($p, PASSWORD_DEFAULT);
        $stmt->execute([$u, $fn, $ln, $e, $l, $hash_mdp]);
        $_SESSION['successMessage'] = "Le compte a été créé !";

        //Maintenant on va récupérer son ID pour le mettre dans la base info_clients et y mettre la valeur : 
        $stmt = $bdd->prepare('SELECT id_client FROM clients WHERE mail=:mail');
        $stmt->execute([
            ':mail' => $e,
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            $id_client = $result["id_client"];
            $stmt2 = $bdd->prepare('INSERT INTO info_clients (id_client,nb_posts,nb_pays_visite,biographie) VALUES (?, ?, ?, ?)');
            $stmt2->execute([$id_client,0,$v,""]);
        }else{
            die("Erreur d'enregistrement.");
        }

        header("Location: ./login.php");
        exit();
    }
}


// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST" && verify()){
    $username = $_POST['username'] ;
    $firstName = $_POST['first_name'] ;
    $lastName = $_POST['last_name'] ;
    $email = $_POST['email'] ;
    $location = $_POST['country'] ;
    $visitedCountry = $_POST["visitedCountry"];
    $password = $_POST['password'] ;
    $confirmPassword = $_POST['confirm_password'];
    if($visitedCountry <0){
        die("Le nombre de pays visité doit être un entier.");
    }
    if((empty($username) || empty($firstName)
    || empty($lastName) || empty($email) || empty($location) || empty($password) 
    || empty($confirmPassword)) || empty($visitedCountry) || $password != $confirmPassword){
        //Si champs invalident => On redirige
        echo "<meta http-equiv='refresh' content='0;url= ./register.php?username=".$username."&first_name=".$firstName."&last_name=".$lastName."&email=".$email."&location=".$location."&visitedCountry=".$visitedCountry."'/>";
    }else{
        // Sinon on save les données
        save($username,$firstName,$lastName,$email,$location,$password,$visitedCountry);
    }
}else{
    die("Vous n'avez pas accès à cette page.");
    exit();
}

?>