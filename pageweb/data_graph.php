<?php
include 'bd.php';
$bdd = getBD();
$data = [];

// Liste des pays disponibles
$sql_countries = "SELECT nom_pays FROM pays";
$result_countries = $bdd->query($sql_countries);
$data['countries'] = $result_countries->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le pays sélectionné depuis le paramètre GET (par défaut, premier pays listé)
$pays = isset($_GET['pays']) ? $_GET['pays'] : ($data['countries'][0]['nom_pays'] ?? 'Afghanistan');

// Graphique en barre
$sql2 = "SELECT pays.nom_pays,annee, score_bonheur FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays WHERE nom_pays = :pays";
$stmt = $bdd->prepare($sql2);
$stmt->execute(['pays' => $pays]);
$data['barChart'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Graphique en lignes
$sql1 = "SELECT annee, naissance,mort FROM sante INNER JOIN pays ON sante.id_pays = pays.id_pays WHERE nom_pays = :pays AND annee < 2024 AND annee > 1999 ORDER BY annee";
$result1 = $bdd->prepare($sql1);
$result1->execute(['pays' => $pays]);
$data['lineChart'] = $result1->fetchAll(PDO::FETCH_ASSOC);

// Graphique en camembert
$sql3 = "SELECT pays.nom_pays,pas_important, important FROM religion INNER JOIN pays ON religion.id_pays = pays.id_pays WHERE nom_pays = :pays LIMIT 1";
$stmt = $bdd->prepare($sql3);
$stmt->execute(['pays' => $pays]);
$data['pieChart'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql4 = "SELECT pays.nom_pays,annee,ete_tavg, printemps_tavg, automne_tavg, hiver_tavg FROM meteo INNER JOIN pays ON meteo.id_pays = pays.id_pays WHERE nom_pays = :pays ";
$stmt = $bdd->prepare($sql4);
$stmt->execute(['pays' => $pays]);
$data['radarChart'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql5 = "SELECT pays.nom_pays,annee,ete_tavg, printemps_tavg, automne_tavg, hiver_tavg FROM meteo INNER JOIN pays ON meteo.id_pays = pays.id_pays WHERE nom_pays = :pays ";
$stmt = $bdd->prepare($sql5);
$stmt->execute(['pays' => $pays]);
$data['polarChart'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
