<?php
function getBD(){
        $bdd = new PDO('mysql:host=mysql-payspedia.alwaysdata.net;dbname=payspedia_bdprojet;charset=utf8', 'payspedia_admin', 'payspedia1234!');
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