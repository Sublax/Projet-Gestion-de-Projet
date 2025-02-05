<?php
function getBD(){
        $bdd = new PDO('mysql:host=localhost;dbname=bdprojet;charset=utf8', 'root', 'root');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
}

function getData($bdd, $table){
        if ($table == 'economie'){
                $stmt = $bdd->prepare("SELECT * 
                                FROM $table, pays
                                WHERE $table.id_country = pays.id_pays");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }elseif ($table == 'pays'){
                $stmt = $bdd->prepare("SELECT * 
                                FROM $table");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
                $stmt = $bdd->prepare("SELECT * 
                                FROM $table, pays
                                WHERE $table.id_pays = pays.id_pays");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
?>