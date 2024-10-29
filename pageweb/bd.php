<?php
function getBD(){
    $bdd = new PDO('mysql:host=localhost;dbname=bdprojet;charset=utf8',
    'root', 'root');
return $bdd;
}
?>