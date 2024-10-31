<?php
function getBD(){
        $bdd = new PDO('mysql:host=localhost;dbname=bdprojet;charset=utf8', 'root', 'root');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
}
