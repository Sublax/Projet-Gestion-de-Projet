<?php
require "bd.php";
$bdd = getBD();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Graphique</title>
  <link rel="stylesheet" href="styles/styles.css">

</head>
<body>
<?php include 'navbar.php' ;?>

<form id="filterForm">
  <label for="countryFilter">Filtrer par pays :</label>
  <select id="countryFilter" multiple>
    <?php
    // Obtenez les noms des pays et leurs IDs
    $sql = "SELECT id_pays, nom_pays FROM pays";
    $result = $bdd->query($sql);
    while ($row = $result->fetch()) {
        echo "<option value='{$row['id_pays']}'>{$row['nom_pays']}</option>";
    }
    ?>
  </select>
  <button type="button" id="applyFilter">Appliquer</button>
</form>
<div class="graphique">
  <canvas id="myChart"></canvas> 
</div>

<div class="graphique">
  <canvas id="secondChart"></canvas> 
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>

<script>

  //Data block
  // Toutes les données :
  const data = {
    labels: [], 
    datasets: [{
      label: 'Clean Fuel and Cooking Equipment',
      data: [], 
      borderWidth: 1,
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)'
    }]
  };


  //Config block
  const config ={
  type: 'bar',
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  }
  //Render block
  
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
  // Gestionnaire d'événement pour appliquer le filtre
  document.getElementById('applyFilter').addEventListener('click', function() {
    const selectedCountries = Array.from(document.getElementById('countryFilter').selectedOptions)
      .map(option => option.value); // Récupérer les ID des pays sélectionnés

    // Envoyer les pays sélectionnés au serveur via fetch
    fetch('./get_filtered_data.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ countries: selectedCountries })
    })
    .then(response => response.json())
    .then(data => {
      // Mettre à jour les données du graphique
      myChart.data.labels = data.labels; // Années
      myChart.data.datasets[0].data = data.values; // Moyennes
      myChart.update(); // Actualiser le graphique
    })
    .catch(error => console.error('Erreur lors de la récupération des données:', error));
  });


  //=======================================================
  // Configuration pour le deuxième graphique
const secondData = {
  labels: [], // Les labels pour l'axe X
  datasets: [{
    label: 'Education primaire',
    data: [], // Les données correspondantes
    borderWidth: 1,
    backgroundColor: 'rgba(255, 99, 132, 0.2)',
    borderColor: 'rgba(255, 99, 132, 1)'
  }]
};

const secondConfig = {
  type: 'line', // Type de graphique (par exemple, 'line', 'bar', etc.)
  data: secondData,
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
};

const secondChart = new Chart(
  document.getElementById('secondChart'),
  secondConfig
);

// Gestionnaire d'événement pour le second graphique
document.getElementById('applyFilter').addEventListener('click', function() {
  const selectedCountries = Array.from(document.getElementById('countryFilter').selectedOptions)
    .map(option => option.value);

  fetch('./get_second_graph.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ countries: selectedCountries })
  })
  .then(response => response.json())
  .then(data => {
    secondChart.data.labels = data.labels; // Labels pour le second graphique
    secondChart.data.datasets[0].data = data.values; // Données pour le second graphique
    secondChart.update(); // Mettre à jour le graphique
  })
  .catch(error => console.error('Erreur pour le second graphique:', error));
});



</script>
