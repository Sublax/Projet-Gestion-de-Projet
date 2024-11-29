<?php
session_start();
include '../bd.php';

function verify(){
    //Fonction qui vérifie que les variables existent bien
    if(isset($_POST['username'],$_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['country'],$_POST["password"],$_POST['confirm_password'])){
        return TRUE;
    }else{
        return FALSE;
    }
}

function save($u,$fn,$ln,$e,$l,$p){
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
    $password = $_POST['password'] ;
    $confirmPassword = $_POST['confirm_password'] ;
    if((empty($username) || empty($firstName)
    || empty($lastName) || empty($email) || empty($location) || empty($password) 
    || empty($confirmPassword)) || $password != $confirmPassword){
        //Si champs invalident => On redirige
        echo "<meta http-equiv='refresh' content='0;url= ./register.php?username=".$username."&first_name=".$firstName."&last_name=".$lastName."&email=".$email."&location=".$location."'/>";
    }else{
        // Sinon on save les données
        save($username,$firstName,$lastName,$email,$location,$password);
    }
}else{
    die("Vous n'avez pas accès à cette page.");
    exit();
}

?>