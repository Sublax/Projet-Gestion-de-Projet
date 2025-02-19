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
$sql2 = "SELECT pays.nom_pays,annee, score_bonheur , generosite FROM bonheur INNER JOIN pays ON bonheur.id_pays = pays.id_pays WHERE nom_pays = :pays";
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


$sql6 = "SELECT pays.nom_pays,annee, sans_emploi_femme,sans_emploi_homme FROM travail INNER JOIN pays ON travail.id_pays = pays.id_pays WHERE nom_pays = :pays AND annee < 2023 AND annee > 1999 ORDER BY annee";
$stmt = $bdd->prepare($sql6);
$stmt->execute(['pays' => $pays]);
$data['lineChart2'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql7 = "SELECT pays.nom_pays,transport.annee, taux_acces_transport FROM transport INNER JOIN pays ON transport.id_pays = pays.id_pays WHERE nom_pays = :pays AND annee IN (2016,2020) ORDER BY annee";
$stmt = $bdd->prepare($sql7);
$stmt->execute(['pays' => $pays]);
$data['doughnutChart'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql8 = "SELECT pays.nom_pays,corruption.annee,crime.taux as taux_crime,(corruption_politique * 100) as corruption_politique 
FROM corruption 
INNER JOIN pays ON corruption.id_pays = pays.id_pays 
INNER JOIN crime ON crime.id_pays = pays.id_pays 
AND crime.annee = corruption.annee 
WHERE nom_pays = :pays
ORDER BY annee";

$stmt = $bdd->prepare($sql8);
$stmt->execute(['pays' => $pays]);
$data['doughnutChart2'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
