<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord interactif</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


  <style>
    /* 
      =================================
      BASE STYLES
      =================================
    */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: white;
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    h1, h2, h3, h4, h5, h6 {
      color: #fff;
    }

    

    /* 
      =================================
      HEADER
      =================================
    */
    header {
    background-color: #fff;
    padding: 10px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.menu-bar {
    display: flex;
    width: 100%;
    justify-content: space-around; /* or space-between */
    align-items: center;
    background-color: #f4f4f4;
    padding: 10px 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
}

.menu-item {
    text-align: center;
    cursor: pointer;
}

.menu-item img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    background-color: transparent;
}

.menu-item p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #333;
}

.menu-item.logo {
    position: relative;
    width: 50px;
    height: 50px;
    bottom: -20px;
    border-radius: 80%;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    padding: 10px;
    background-color: #f4f4f4;
}

.menu-item.logo img {
    width: 150%; /* The image is intentionally larger than its container */
    height: 150%;
    object-fit: cover;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}


    /* 
      =================================
      TOP STATS CARDS
      =================================
    */
    .stats-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    .stats-cards .card{
      background: white;
      border-radius: 16px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      text-align: center;
      transition: transform 0.3s ease, background 0.3s ease;
      position: relative;
      overflow: hidden;
      height: 320px;
      padding-top: 10px;
    }

    .stats-cards .info{
      background: white;
      border-radius: 16px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      text-align: center;
      transition: transform 0.3s ease, background 0.3s ease;
      overflow: hidden;
      height: 200px;
      padding-top: 10px;
    }

    .stats-cards .card:hover,
    .stats-cards .info:hover{
      transform: translateY(-2px);
      background:rgb(231, 231, 231);
    }
    .stats-cards .card h2,
    .stats-cards .info h2 {
      font-size: 1rem;
      color: grey;
      margin-bottom: 5px;
    }
    .stats-cards .card p,
    .stats-cards .info p {
      font-size: 1.5rem;
      font-weight: bold;
      color: grey;
      margin-bottom: 3px;
    }
    .stats-cards .card canvas {
      width: 100%;
      height: 80px;
    }

    #miniChart1,
    #miniChart2 {
        height: 130px !important;
        margin-top: 45px;
    }


    /* 
      =================================
      DASHBOARD LAYOUT
      =================================
    */
    .dashboard {
      display: grid;
      grid-template-columns: 220px 1fr;
      gap: 20px;
      padding: 20px;
      flex: 1;
    }

    /* 
      =================================
      SIDEBAR NAVIGATION
      =================================
    */
    nav {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        padding: 20px;
        /* Make it sticky so it remains visible on scroll */
        position: sticky;
        top: 20px; /* Adjust based on your header height */
        height: calc(100vh - 40px); /* Subtract the top spacing so it fits well */
        overflow-y: auto; /* In case there are many menu items */
    }
    
    nav ul {
      list-style: none;
      padding: 0;
    }
    nav ul li {
      margin-bottom: 15px;
      cursor: pointer;
      padding: 10px;
      border-radius: 8px;
      transition: background 0.3s;
      font-size: 0.95rem;
      color: grey;
    }
    nav ul li:hover {
      background: #33324a;
      color: #fff;
    }
    nav ul li.active {
      font-weight: 500;
      color: #fff;
      background: #414062;
    }

    /* 
      =================================
      CONTENT AREA
      =================================
    */
    .content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
    }

    .container {
      background: white;
      border-radius: 16px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      overflow: hidden;
      transition: transform 0.3s ease, background 0.3s ease;
      position: relative;
    }
    .container:hover {
      transform: scale(0.98);
      background: rgb(231, 231, 231);
    }

    /* 
      =================================
      EDIT BUTTON
      =================================
    */
    .edit-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
      background:rgb(0, 0, 0);
      color: #fff;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.8rem;
      transition: background 0.3s;
    }
    .edit-btn:hover {
      background: #565479;
    }

    /* 
      =================================
      OVERLAY CONTROLS
      =================================
    */
    .overlay-controls {
      display: none; /* Hidden by default */
      flex-wrap: wrap;
      align-items: center;
      gap: 10px;
      padding: 12px;
      background: rgba(255, 255, 255, 0.07);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(8px);
    }
    .overlay-controls label {
      font-size: 0.85rem;
      font-weight: 500;
      margin-right: 5px;
      color: #ccc;
      white-space: nowrap;
    }
    .overlay-controls select,
    .overlay-controls button {
      padding: 8px 12px;
      font-size: 0.85rem;
      border: 1px solid #444;
      border-radius: 4px;
      background:rgb(10, 3, 97);
      color: #fff;
      transition: background 0.3s, border-color 0.3s;
    }
    .overlay-controls select:focus,
    .overlay-controls button:focus {
      outline: none;
      border-color: #6c5ce7;
    }
    .overlay-controls select:hover,
    .overlay-controls button:hover {
      background: #2a2939;
    }
    .overlay-controls button {
      cursor: pointer;
    }
    

    .tag {
        display: inline-block;
        background-color: rgb(199, 0, 0); /* semi-transparent background */
        color: black;                              /* text color */
        padding: 4px 10px;                        /* some padding */
        border: 1px solid rgb(65, 153, 153);   /* border matching the background color */
        border-radius: 12px;                      /* rounded corners */
        font-size: 0.9rem;                        /* adjust font size as needed */
        margin-left: 5px;                         /* optional: some space from preceding text */
        z-index: 2;
        position: relative;
    }


    /* 
      =================================
      FULLSCREEN BUTTON
      =================================
    */
    .fullscreen-btn {
      flex: 1;
      max-width: 140px;
      background: #6c5ce7;
      border: none;
      color: #fff;
    }
    .fullscreen-btn:hover {
      background: #5841c1;
    }

    /* 
      =================================
      CANVAS STYLES
      =================================
    */
    canvas {
      display: block;
      width: 100% !important;
      height: 100px;
      border-bottom-left-radius: 16px;
      border-bottom-right-radius: 16px;
    }

    /* 
      =================================
      GRAPH DESCRIPTION
      =================================
    */
    .graph-description {
      padding: 10px;
      background: rgb(8, 1, 134);
      font-size: 0.9rem;
      color: white;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- PHP: Checking for "country" and fetching data (same as your original code) -->
  <?php

    include '../navbar.php';

    if (!isset($_GET['country'])) {
        die("No country specified.");
    }
    $country = $_GET['country'];

    // Build the URL to data_graph.php with the country parameter
    $dataGraphUrl = "http://localhost/payspedia_v2/pageweb/datavizio/data_graph.php?country=" . urlencode($country);

    // Fetch the JSON data from data_graph.php
    $jsonData = file_get_contents($dataGraphUrl);
    if ($jsonData === false) {
        die("Error fetching data from data_graph.php");
    }

    // Optionally, log or process the fetched JSON data
    error_log("Data received from data_graph.php: " . $jsonData);

    // Output the JSON data (or further process it as needed)
    header('Content-Type: text/html');
    // Getting data
    $data = json_decode($jsonData, true);

  ?>

  <header>
    <h1>Tableau de Bord Interactif</h1>
  </header>
  
  <div class="dashboard">
    <nav>
      <ul id="sectionNav">
        <li data-section="1" class="active">Quelques Caracteristiques générales</li>
        <li data-section="2">Voir les Trends et Distributions</li>
        <li data-section="3">Fréquence des valeurs</li>
        <li data-section="4">Liaison Linéaire entre Deux Variables</li>
        <li data-section="5">Analyse par Secteur</li>
      </ul>
    </nav>

    <div class="content">
        <!-- Section 1: General Characteristics -->
        <div class="container" data-section="1">
  <!-- Top Stats Cards -->
  <section class="stats-cards">
    <div class="card">
      <h2>Natalité/Mortalité</h2>
      <p>- Evolution -</p>
      <canvas id="miniChart1"></canvas>
    </div>
    <div class="card">
      <h2>Distribution Temperatures par Saisons</h2>
      
      <canvas id="miniChart2"></canvas>
    </div>
    <div class="card">
      <h2>Distribution population sans emploi par sexe</h2>
      <canvas id="miniChart3"></canvas>
    </div>
    <div class="card">
      <h2>Secteurs économiques les plus développées</h2>
      <div id="myCustomLegend"></div>
      <canvas id="miniChart4"></canvas>
    </div>
    <div class="info">
      <h2>Esperance de vie moyenne</h2>
      <p id='esperance_vie'>75.4 Years</p>
      <p class="life-icon"><i class="fas fa-heartbeat fa-3x"></i></p>
    </div>
    <div class="info">
      <h2>Bonheur</h2>
      <p id='bonheur'>83%</p>
      <p class="icon-card"><i class="fas fa-smile fa-3x"></i></p>
    </div>
    <div class="info">
      <h2>Taux Access Transport</h2>
      <p id='taux_transport'>92%</p>
      <p class="icon-card"><i class="fas fa-bus fa-3x"></i></p>
    </div>
    <div class="info">
      <h2>Taux Criminalité</h2>
      <p id='taux_criminalite'>92%</p>
      <p class="icon-card"><i class="fas fa-user-shield fa-3x"></i></p>
    </div>
  </section>
</div>

        
      <!-- Section 2: Trends/Comparing Values -->
      <!-- Barplot -->
      <div class="container" data-section="2">
        <button class="edit-btn">Edit</button>
        <div class="overlay-controls">
        <div id="selectMenu" style="display: block;">
        <div id="bar_variableTagsContainer" style="margin-top: 8px; cursor: pointer"></div>  
          <select id="bar_newVariableSelect" style="display: none;">
              <option value="">Select a variable</option>
              <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
              <option value="costhealthydiet">cost of a healthy diet</option>
          </select>
          <button id="bar_addVarButton">Ajouter une variable</button>
          <button class="fullscreen-btn">Full Screen</button> 
          </div> 
        </div>
        <div class="graph-description">
          Diagramme en Barres
        </div>
        <canvas id="barChart"></canvas>
      </div>

      <!-- Lineplot -->
      <div class="container" data-section="2">
        <button class="edit-btn">Edit</button>
        <div class="overlay-controls">
          <div id="selectMenu" style="display: block;">
            <div id="line_variableTagsContainer" style="margin-top: 8px; cursor: pointer"></div>
            <select id="line_newVariableSelect" style="display: none;">
                <option value="">Select a variable</option>
                <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
                <option value="costhealthydiet">cost of a healthy diet</option>
            </select>
            <button id="line_addVarButton">Ajouter une variable</button>
            <button class="fullscreen-btn">Full Screen</button>
          </div>
        </div>
        <div class="graph-description">
          Diagramme en lignes
        </div>
        <canvas id="lineplotChart"></canvas>
      </div>

      <!-- Pie Chart -->
      <div class="container" data-section="2">
        <button class="edit-btn">Edit</button>
        <div class="overlay-controls">
          <label for="pie_domainSelect">Select Domain:</label>
          <select id="pie_domainSelect">
              <option value="">Select Domain</option>
          </select>
          <button class="fullscreen-btn">Full Screen</button>  
        </div>
        <div class="graph-description">
          Diagramme circulaire
        </div>
        <canvas id="pieChart"></canvas>
      </div>

      <!-- Polar Chart -->
      <div class="container" data-section="2">
        <button class="edit-btn">Edit</button>
        <div class="overlay-controls">
          <label for="polar_domainSelect">Select Domain:</label>
          <select id="polar_domainSelect">
              <option value="">Select Domain</option>
          </select>
          <button class="fullscreen-btn">Full Screen</button>  
        </div>
        <div class="graph-description">
          Diagramme Polaire
        </div>
        <canvas id="polarChart"></canvas>
      </div>

      <!-- Donut Chart -->
      <div class="container" data-section="2">
        <button class="edit-btn">Edit</button>
        <div class="overlay-controls">
          <label for="donut_domainSelect">Select Domain:</label>
          <select id="donut_domainSelect">
              <option value="">Select Domain</option>
          </select>
          <button class="fullscreen-btn">Full Screen</button>  
        </div>
        <div class="graph-description">
          Diagramme en Donut
        </div>
        <canvas id="donutChart"></canvas>
      </div>

      <!-- Section 2: Value Scales (Histogram / Boxplot) -->
      <div class="container" data-section="3">
        <button class="edit-btn">Edit</button>
        <div class="overlay-controls">
          <div id="selectMenu" style="display: block;">
            <!-- Variable tags container (for listing current datasets) -->
            <div id="hist_variableTagsContainer" style="margin-top: 8px; cursor: pointer"></div>
            <button id="hist_addVarButton">Ajouter une variable</button>
            <button class="fullscreen-btn">Full Screen</button>
          </div>
        </div>
        <div class="graph-description">
          Histogramme
        </div>
        <canvas id="histChart"></canvas>
      </div>

      <!-- Section 3: Links Between Variables (Scatter Chart) -->
      <div class="container" data-section="4">
          <button class="edit-btn">Edit</button>
          <div class="overlay-controls">
              <div id="scatter_variableTagsContainer" style="margin-top: 8px; cursor: pointer;"></div>
              <button class="fullscreen-btn">Full Screen</button>
          </div>
          <div class="graph-description">
              Nuage de points
          </div>
          <canvas id="scatterChart"></canvas>
      </div>


      <!-- Section 4: Sector Analysis (Radar Chart) -->
      <div class="container" data-section="5">
          <button class="edit-btn">Edit</button>
          <div class="overlay-controls">
              <div id="selectMenu" style="display: block;">
                  <div id="radar_variableTagsContainer" style="margin-top: 8px; cursor: pointer"></div>
                  <button id="radar_addVarButton">Ajouter une variable</button>
                  <button class="fullscreen-btn">Full Screen</button>
              </div>
          </div> <!-- ✅ Correct position: ends controls here -->

          <!-- Now, these elements will always be visible -->
          <div class="graph-description">
              Diagramme Radar
          </div>
          <canvas id="radarChart"></canvas>
      </div>

    </div>
  </div>

  <script>
    // ===================== Fullscreen Functionality =====================
    document.querySelectorAll('.fullscreen-btn').forEach(function(button) {
      button.addEventListener('click', function(e) {
        e.stopPropagation();
        var container = e.target.closest('.container');
        if (container.requestFullscreen) {
          container.requestFullscreen();
        } else if (container.webkitRequestFullscreen) {
          container.webkitRequestFullscreen();
        } else if (container.msRequestFullscreen) {
          container.msRequestFullscreen();
        }
      });
    });

    document.addEventListener('fullscreenchange', function() {
  if (document.fullscreenElement) {
    // When in fullscreen, hide edit buttons and overlay controls within the fullscreen container.
    document.querySelectorAll('.edit-btn, .overlay-controls').forEach(function(el) {
      el.style.display = 'none';
    });
  } else {
    // When exiting fullscreen, restore the edit buttons and overlay controls.
    document.querySelectorAll('.edit-btn').forEach(function(el) {
      el.style.display = 'block';
    });
    document.querySelectorAll('.overlay-controls').forEach(function(el) {
      el.style.display = 'flex';
    });
    // Reset canvas sizes and resize charts as before.
    document.querySelectorAll('canvas').forEach(function(canvas) {
      canvas.style.width = '';
      canvas.style.height = '';
    });
    var chartInstances = [barChart, lineChart, pieChart, polarChart, donutChart, radarChart, scatterChart, histogramChart];
    chartInstances.forEach(function(chart) {
      if (chart) { chart.resize(); }
    });
  }
});


    // ===================== Sidebar Navigation =====================
    const navItems = document.querySelectorAll('#sectionNav li');
    const containers = document.querySelectorAll('.content .container');
    navItems.forEach(item => {
      item.addEventListener('click', () => {
        navItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        const selectedSection = item.getAttribute('data-section');
        containers.forEach(container => {
          container.style.display = (container.getAttribute('data-section') === selectedSection) ? 'block' : 'none';
        });
      });
    });
    // Initialize: show only section 1 containers.
    containers.forEach(container => {
      if (container.getAttribute('data-section') !== '1') {
        container.style.display = 'none';
      }
    });

    // ===================== Edit Button to Toggle Graph Menu =====================
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var container = btn.closest('.container');
        var controls = container.querySelector('.overlay-controls');
        if (controls.style.display === 'none' || controls.style.display === '') {
          controls.style.display = 'flex';
          btn.innerText = 'X';
        } else {
          controls.style.display = 'none';
          btn.innerText = 'Edit';
        }
      });
    });

    // ===================== Utility Function =====================
    function randomRGBA(alpha = 0.2) {
  // Generate a random hue (0-360)
  const hue = Math.floor(Math.random() * 360);
  
  // Use HSL color space to generate vibrant colors:
  // - Increase saturation between 80-100% for vivid colors.
  // - Lower lightness between 40-60% so colors pop against dark themes.
  const saturation = Math.floor(Math.random() * 20) + 80; // Range: 80-100%
  const lightness = Math.floor(Math.random() * 20) + 40;  // Range: 40-60%
  
  // Use the provided alpha value directly for transparency
  const boundedAlpha = alpha;
  
  // Convert HSL to RGB
  const h = hue / 360;
  const s = saturation / 100;
  const l = lightness / 100;
  
  let r, g, b;
  
  if (s === 0) {
    r = g = b = l;
  } else {
    const hue2rgb = (p, q, t) => {
      if (t < 0) t += 1;
      if (t > 1) t -= 1;
      if (t < 1/6) return p + (q - p) * 6 * t;
      if (t < 1/2) return q;
      if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
      return p;
    };
    
    const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
    const p = 2 * l - q;
    
    r = hue2rgb(p, q, h + 1/3);
    g = hue2rgb(p, q, h);
    b = hue2rgb(p, q, h - 1/3);
  }
  
  // Convert to RGB (0-255)
  const rgb = [
    Math.round(r * 255),
    Math.round(g * 255),
    Math.round(b * 255)
  ];
  
  return `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${boundedAlpha})`;
}


    // ===================== Placeholder for the Scatter Chart Function =====================
function initializeDynamicChartV3(config) {
  const chartData = config.chartData;
  let selectedDomain = config.defaultDomain;

  // Get DOM elements.
  const canvas = document.getElementById(config.canvasId);
  const addVarButton = document.getElementById(config.addVarButtonId);
  const variableTagsContainer = document.getElementById(config.variableTagsContainerId);

  // Helper to add a common explanation header to a modal.
  function addCommonExplanation(modalContent, titleText) {
    const modalTitle = document.createElement("h2");
    modalTitle.textContent = titleText;
    modalTitle.style.marginTop = "0";
    modalContent.appendChild(modalTitle);
    
    const explanation = document.createElement("p");
    explanation.textContent = "";
    explanation.style.fontSize = "0.9em";
    explanation.style.color = "#666";
    explanation.style.marginBottom = "15px";
    modalContent.appendChild(explanation);
  }

  // Helper: Get chart data for a given domain.
  // It picks the first available variable (ignoring "annee" or "Year") as default.
  function getChartData(domain) {
    let defaultVar = "";
    let labels = [];
    if (chartData[domain] && chartData[domain].length > 0) {
      defaultVar = Object.keys(chartData[domain][0])
        .find(key => key !== 'annee' && key !== 'Year') || "";
      // Get the year labels from the domain.
      labels = chartData[domain].map(item => domain === 'economie' ? item.Year : item.annee);
    }
    if (config.chartType === 'pie') {
      const labelField = config.labelField || defaultVar;
      const pieLabels = chartData[domain].map(item => item[labelField]);
      const dataValues = chartData[domain].map(item => item[defaultVar]);
      // Also store the original year labels for union calculation.
      return { labels: pieLabels, data: dataValues, defaultVar, originalLabels: labels };
    } else {
      const dataValues = chartData[domain].map(item => item[defaultVar]);
      return { labels: labels, data: dataValues, defaultVar, originalLabels: labels };
    }
  }
  const initValues = getChartData(selectedDomain);

  // Create the Chart.js instance.
  const ctx = canvas.getContext('2d');
  const chartInstance = new Chart(ctx, {
    type: config.chartType,
    data: {
      labels: initValues.labels, // Initially from the selected domain.
      datasets: [{
        label: initValues.defaultVar,
        data: initValues.data,
        originalLabels: initValues.originalLabels, // Save original labels.
        backgroundColor: config.chartType === 'pie'
          ? initValues.data.map(() => randomRGBA(0.6))
          : 'rgba(75, 192, 192, 0.2)',
        borderColor: config.chartType === 'pie'
          ? initValues.data.map(() => randomRGBA(1))
          : 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      scales: config.chartType === 'pie' ? {} : { y: { beginAtZero: true } }
    }
  });

  // --- New Function: Update Global (Union) X-Axis Labels ---
  function updateGlobalLabels() {
    let unionLabels = [];
    // Combine all original labels from each dataset.
    chartInstance.data.datasets.forEach(ds => {
      if (ds.originalLabels) {
        unionLabels = unionLabels.concat(ds.originalLabels);
      }
    });
    // Remove duplicates and sort (assuming numeric years).
    unionLabels = [...new Set(unionLabels)].sort((a, b) => a - b);
    
    // For each dataset, remap its data to the union labels.
    chartInstance.data.datasets.forEach(ds => {
      if (!ds.originalLabels) return;
      let newData = [];
      unionLabels.forEach(year => {
        const idx = ds.originalLabels.indexOf(year);
        if (idx !== -1) {
          newData.push(ds.data[idx]);
        } else {
          newData.push(null); // Or use 0 if preferred.
        }
      });
      ds.data = newData;
    });
    
    // Update the chart's global labels.
    chartInstance.data.labels = unionLabels;
    chartInstance.update();
  }

  // --- Update Variable Tags Function ---
  function updateVariableTags() {
    if (!variableTagsContainer) return;

    // Clear existing content.
    variableTagsContainer.innerHTML = "";
    
    // Style the container as a grid.
    variableTagsContainer.style.display = "grid";
    variableTagsContainer.style.gridTemplateColumns = "repeat(auto-fit, minmax(150px, 1fr))";
    variableTagsContainer.style.gridGap = "10px";
    variableTagsContainer.style.border = "2px solid #ccc";
    variableTagsContainer.style.padding = "10px";
    variableTagsContainer.style.borderRadius = "5px";
    variableTagsContainer.style.backgroundColor = "#f9f9f9";
    variableTagsContainer.style.marginBottom = "10px";
    
    // --- Add a description alongside the buttons and tags ---
    const mainExplanation = document.createElement("p");
    mainExplanation.textContent = "Cette interface vous permet d'ajouter, de modifier ou de supprimer des variables du graphique. Cliquez sur un tag pour modifier la variable correspondante, ou sur le bouton 'Ajouter une variable' pour en inclure une nouvelle. Les modifications seront appliquées en temps réel.";
    mainExplanation.style.fontSize = "0.9em";
    mainExplanation.style.color = "#666";
    mainExplanation.style.marginBottom = "10px";
    variableTagsContainer.appendChild(mainExplanation);

    // Create and add a heading.
    const heading = document.createElement("h3");
    heading.textContent = "Variables Actuelles";
    heading.style.marginTop = "0";
    heading.style.textAlign = "center";
    heading.style.color = "blue"; // Adjust as needed.
    heading.style.gridColumn = "1 / -1"; // Span all columns.
    variableTagsContainer.appendChild(heading);

    // Create a tag for each dataset.
    chartInstance.data.datasets.forEach((dataset, index) => {
      const tag = document.createElement("div");
      tag.className = "tag";
      // Make the tag container relative and center its text.
      tag.style.position = "relative";
      tag.style.textAlign = "center";
      // Add right padding to make room for the x button.
      tag.style.paddingRight = "25px";
      tag.style.margin = "5px";
      tag.style.padding = "5px 10px";
      tag.style.backgroundColor = "#eee";
      tag.style.borderRadius = "3px";
      tag.style.cursor = "pointer";

      // Create the text span for the variable name.
      const labelSpan = document.createElement("span");
      labelSpan.textContent = dataset.label;
      tag.appendChild(labelSpan);

      // Create the remove icon ("×") and position it.
      const removeIcon = document.createElement("span");
      removeIcon.textContent = "×";
      removeIcon.style.position = "absolute";
      removeIcon.style.right = "5px";
      removeIcon.style.top = "50%";
      removeIcon.style.transform = "translateY(-50%)";
      removeIcon.style.cursor = "pointer";
      removeIcon.style.color = "#900";
      removeIcon.style.fontWeight = "bold";

      // Prevent the click on the remove icon from triggering the tag click.
      removeIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        chartInstance.data.datasets.splice(index, 1);
        chartInstance.update();
        updateGlobalLabels();
        updateVariableTags();
      });
      tag.appendChild(removeIcon);

      // Tag click (excluding the remove icon) opens a modal to edit the variable.
      tag.addEventListener("click", () => {
        // Create modal overlay.
        const modalOverlay = document.createElement("div");
        modalOverlay.style.position = "fixed";
        modalOverlay.style.top = "0";
        modalOverlay.style.left = "0";
        modalOverlay.style.width = "100%";
        modalOverlay.style.height = "100%";
        modalOverlay.style.backgroundColor = "rgba(0,0,0,0.5)";
        modalOverlay.style.display = "flex";
        modalOverlay.style.alignItems = "center";
        modalOverlay.style.justifyContent = "center";
        modalOverlay.style.zIndex = "1000";
        
        // Create modal content container.
        const modalContent = document.createElement("div");
        modalContent.style.backgroundColor = "#fff";
        modalContent.style.padding = "20px";
        modalContent.style.borderRadius = "8px";
        modalContent.style.minWidth = "300px";
        modalContent.style.fontFamily = "Arial, sans-serif";
        modalContent.style.color = "#333";
        
        // --- Add common explanation for all functionalities in modal ---
        addCommonExplanation(modalContent, "Edit Variable");
        
        // Create a label and select for domains.
        const domainLabel = document.createElement("label");
        domainLabel.textContent = "Select Domain:";
        domainLabel.style.display = "block";
        domainLabel.style.marginBottom = "5px";
        modalContent.appendChild(domainLabel);
        
        const domainSelectModal = document.createElement("select");
        domainSelectModal.style.width = "100%";
        Object.keys(chartData).forEach(domain => {
          const option = document.createElement("option");
          option.value = domain;
          option.textContent = domain;
          domainSelectModal.appendChild(option);
        });
        // Set default selected domain.
        domainSelectModal.value = selectedDomain;
        modalContent.appendChild(domainSelectModal);
        
        // Create a label and select for variables.
        const variableLabel = document.createElement("label");
        variableLabel.textContent = "Select Variable:";
        variableLabel.style.display = "block";
        variableLabel.style.marginTop = "10px";
        variableLabel.style.marginBottom = "5px";
        modalContent.appendChild(variableLabel);
        
        const variableSelectModal = document.createElement("select");
        variableSelectModal.style.width = "100%";
        
        // Helper to populate the variable select.
        function populateVariableSelect(domain) {
          variableSelectModal.innerHTML = "";
          if (chartData[domain] && chartData[domain].length > 0) {
            Object.keys(chartData[domain][0])
              .filter(key => key !== "annee" && key !== "Year")
              .forEach(key => {
                const option = document.createElement("option");
                option.value = key;
                option.textContent = key;
                variableSelectModal.appendChild(option);
              });
          }
        }
        populateVariableSelect(domainSelectModal.value);
        variableSelectModal.value = dataset.label; // Set current variable as default.
        modalContent.appendChild(variableSelectModal);
        
        domainSelectModal.addEventListener("change", () => {
          populateVariableSelect(domainSelectModal.value);
        });
        
        // Create Confirm and Cancel buttons.
        const confirmButton = document.createElement("button");
        confirmButton.textContent = "Confirm";
        confirmButton.style.marginTop = "15px";
        confirmButton.style.padding = "5px 10px";
        confirmButton.style.cursor = "pointer";
        modalContent.appendChild(confirmButton);
        
        const cancelButton = document.createElement("button");
        cancelButton.textContent = "Cancel";
        cancelButton.style.marginTop = "15px";
        cancelButton.style.marginLeft = "10px";
        cancelButton.style.padding = "5px 10px";
        cancelButton.style.cursor = "pointer";
        modalContent.appendChild(cancelButton);
        
        modalOverlay.appendChild(modalContent);
        document.body.appendChild(modalOverlay);
        
        // On confirm, update the dataset.
        confirmButton.addEventListener("click", () => {
          const newDomain = domainSelectModal.value;
          const newVariable = variableSelectModal.value;
          if (newDomain && newVariable) {
            const duplicateExists = chartInstance.data.datasets.some((ds, dsIndex) => dsIndex !== index && ds.label === newVariable);
            if (duplicateExists) {
              alert("This variable is already added in another tag.");
              return;
            }
            // Update dataset with new data.
            const newData = chartData[newDomain].map(item => item[newVariable]);
            dataset.label = newVariable;
            dataset.data = newData;
            // Save new original labels.
            dataset.originalLabels = newDomain === 'economie'
              ? chartData[newDomain].map(item => item.Year)
              : chartData[newDomain].map(item => item.annee);
            if (config.chartType === 'pie') {
              dataset.backgroundColor = newData.map(() => randomRGBA(0.6));
              dataset.borderColor = newData.map(() => randomRGBA(1));
            } else {
              dataset.backgroundColor = randomRGBA(0.3);
              dataset.borderColor = randomRGBA(1);
            }
            chartInstance.update();
            updateGlobalLabels();
            updateVariableTags();
          }
          document.body.removeChild(modalOverlay);
        });
        
        cancelButton.addEventListener("click", () => {
          document.body.removeChild(modalOverlay);
        });
      });
      
      variableTagsContainer.appendChild(tag);
    });
  }

  // --- Add Variable Logic ---
  if (addVarButton) {
    addVarButton.addEventListener('click', function() {
      const modalOverlay = document.createElement("div");
      modalOverlay.style.cssText =
        "position: fixed; top: 0; left: 0; width: 100%; height: 100%; " +
        "background-color: rgba(0,0,0,0.5); display: flex; align-items: center; " +
        "justify-content: center; z-index: 1000;";
      
      const modalContent = document.createElement("div");
      modalContent.style.cssText =
        "background-color: #fff; padding: 20px; border-radius: 8px; " +
        "min-width: 300px; font-family: Arial, sans-serif; color: #333;";
      
      // --- Add common explanation for all functionalities in modal ---
      addCommonExplanation(modalContent, "Add Variable");
      
      const domainLabel = document.createElement("label");
      domainLabel.textContent = "Select Domain:";
      domainLabel.style.display = "block";
      domainLabel.style.marginBottom = "5px";
      modalContent.appendChild(domainLabel);
      
      const domainSelectModal = document.createElement("select");
      domainSelectModal.style.width = "100%";
      Object.keys(chartData).forEach(domain => {
        const option = document.createElement("option");
        option.value = domain;
        option.textContent = domain;
        domainSelectModal.appendChild(option);
      });
      domainSelectModal.value = selectedDomain;
      modalContent.appendChild(domainSelectModal);
      
      const variableLabel = document.createElement("label");
      variableLabel.textContent = "Select Variable:";
      variableLabel.style.display = "block";
      variableLabel.style.margin = "10px 0 5px";
      modalContent.appendChild(variableLabel);
      
      const variableSelectModal = document.createElement("select");
      variableSelectModal.style.width = "100%";
      
      function populateVariableSelect(domain) {
        variableSelectModal.innerHTML = "";
        if (chartData[domain] && chartData[domain].length > 0) {
          Object.keys(chartData[domain][0])
            .filter(key => key !== "annee" && key !== "Year")
            .forEach(key => {
              const option = document.createElement("option");
              option.value = key;
              option.textContent = key;
              variableSelectModal.appendChild(option);
            });
        }
      }
      populateVariableSelect(domainSelectModal.value);
      domainSelectModal.addEventListener("change", () => {
        populateVariableSelect(domainSelectModal.value);
      });
      
      modalContent.appendChild(variableSelectModal);
      
      const confirmButton = document.createElement("button");
      confirmButton.textContent = "Confirm";
      confirmButton.style.cssText = "margin-top: 15px; padding: 5px 10px; cursor: pointer;";
      modalContent.appendChild(confirmButton);
      
      const cancelButton = document.createElement("button");
      cancelButton.textContent = "Cancel";
      cancelButton.style.cssText = "margin-top: 15px; margin-left: 10px; padding: 5px 10px; cursor: pointer;";
      modalContent.appendChild(cancelButton);
      
      modalOverlay.appendChild(modalContent);
      document.body.appendChild(modalOverlay);
      
      confirmButton.addEventListener("click", () => {
        const chosenDomain = domainSelectModal.value;
        const chosenVariable = variableSelectModal.value;
        if (chosenDomain && chosenVariable) {
          const alreadyAdded = chartInstance.data.datasets.some(ds => ds.label === chosenVariable);
          if (alreadyAdded) {
            alert("This variable is already added.");
          } else {
            const newData = chartData[chosenDomain].map(item => item[chosenVariable]);
            const newDataset = {
              label: chosenVariable,
              data: newData,
              originalLabels: chosenDomain === 'economie'
                ? chartData[chosenDomain].map(item => item.Year)
                : chartData[chosenDomain].map(item => item.annee),
              backgroundColor: config.chartType === 'pie'
                ? newData.map(() => randomRGBA(0.6))
                : randomRGBA(0.3),
              borderColor: config.chartType === 'pie'
                ? newData.map(() => randomRGBA(1))
                : randomRGBA(1),
              borderWidth: 1,
              fill: false
            };
            chartInstance.data.datasets.push(newDataset);
            chartInstance.update();
            updateGlobalLabels();
            updateVariableTags();
          }
        }
        document.body.removeChild(modalOverlay);
      });
      
      cancelButton.addEventListener("click", () => {
        document.body.removeChild(modalOverlay);
      });
    });
  }

  updateVariableTags();
  // Ensure the global x-axis reflects the union of all dataset years.
  updateGlobalLabels();
  return chartInstance;
}

/**
   * --- PIE CHART ---
 * Initializes a pie chart that shows the aggregated distribution for the selected domain.
 * The aggregation sums up all numeric values for each field, excluding specified fields.
 *
 * @param {Object} config - Configuration object with properties:
 *    - canvasId: the ID of the <canvas> element.
 *    - graphType: the type of chart (pie, polar, donut)
 *    - domainSelectId: the ID of the <select> element for domain selection.
 *    - defaultDomain: the default domain (e.g. "travail").
 *    - chartData: the full data object (an object whose keys are domains and whose values are arrays of data rows).
 *    - excludeFields: an array of field names to exclude from aggregation (e.g. ["annee", "Year"]).
 */
function initializePieDistributionChart(config) {
  // Retrieve the data and initial domain
  var chartData = config.chartData;
  var selectedDomain = config.defaultDomain;
  
  // Get the domain selector element and populate it with domains from the data
  var domainSelect = document.getElementById(config.domainSelectId);
  domainSelect.innerHTML = "";
  Object.keys(chartData).forEach(function(domain) {
    var option = document.createElement("option");
    option.value = domain;
    option.text = domain;
    domainSelect.appendChild(option);
  });
  domainSelect.value = selectedDomain;
  
  // Helper function: aggregates the data for a given domain by calculating the mean of each numeric field,
  // excluding fields listed in config.excludeFields.
  function aggregateDataForDomain(domain) {
    var dataArray = chartData[domain]; // Array of data rows for this domain
    var aggregation = {};  // Will store { fieldName: { sum: number, count: number } }
    dataArray.forEach(function(item) {
      for (var key in item) {
        // Skip keys that we want to exclude (like the year)
        if (config.excludeFields && config.excludeFields.indexOf(key) !== -1) continue;
        // Convert value to a number; if it's not numeric, skip it.
        var value = parseFloat(item[key]);
        if (isNaN(value)) continue;
        if (!aggregation[key]) {
          aggregation[key] = { sum: value, count: 1 };
        } else {
          aggregation[key].sum += value;
          aggregation[key].count += 1;
        }
      }
    });
    // Calculate mean for each field
    var labels = Object.keys(aggregation);
    var dataValues = labels.map(function(k) { 
      return aggregation[k].sum / aggregation[k].count;
    });
    return { labels: labels, data: dataValues };
  }
  
  // Get initial aggregated data for the default domain
  var agg = aggregateDataForDomain(selectedDomain);
  
  // Get the canvas context and create the pie chart
  var ctx = document.getElementById(config.canvasId).getContext('2d');
  var pieChart = new Chart(ctx, {
    type: config.graphType,
    data: {
      labels: agg.labels,
      datasets: [{
        data: agg.data,
        backgroundColor: agg.labels.map(() => randomRGBA(0.7)),
        borderColor: 'rgb(125, 240, 80)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });
  
  // When the domain selection changes, re-aggregate the data and update the chart
  domainSelect.addEventListener("change", function() {
    selectedDomain = this.value;
    var newAgg = aggregateDataForDomain(selectedDomain);
    pieChart.data.labels = newAgg.labels;
    pieChart.data.datasets[0].data = newAgg.data;
    pieChart.data.datasets[0].backgroundColor = newAgg.labels.map(() => randomRGBA(0.6));
    pieChart.data.datasets[0].borderColor = newAgg.labels.map(() => randomRGBA(1));
    pieChart.update();
  });
  
  return pieChart;
}


/**
 * Initializes a radar chart for a selected domain.
 *
 * @param {Object} config - Configuration object with properties:
 *   - canvasId: ID of the <canvas> element for the chart.
 *   - domainSelectId: ID of the <select> element for domain selection.
 *   - variableSelectId: ID of the <select> element for variable selection.
 *   - newVariableSelectId: ID of the <select> element for adding a new variable.
 *   - addVarButtonId: ID of the button to add a new variable.
 *   - removeVarButtonId: ID of the button to remove a variable.
 *   - removeSelectId: ID of the <select> element used for removing a variable.
 *   - defaultDomain: The initial domain to display.
 *   - chartData: The complete data object (keys are domains, values are arrays of data rows).
 */
function initializeRadarChart(config) {
  var chartData = config.chartData;
  var selectedDomain = config.defaultDomain;

  // Get DOM elements
  var domainSelect = document.getElementById(config.domainSelectId);
  var variableSelect = document.getElementById(config.variableSelectId);
  var newVariableSelect = document.getElementById(config.newVariableSelectId);
  var addVarButton = document.getElementById(config.addVarButtonId);
  var removeVarButton = document.getElementById(config.removeVarButtonId);
  var removeSelect = document.getElementById(config.removeSelectId);

  // Populate the domain selector
  domainSelect.innerHTML = "";
  Object.keys(chartData).forEach(function(domain) {
    var option = document.createElement("option");
    option.value = domain;
    option.text = domain;
    domainSelect.appendChild(option);
  });
  domainSelect.value = selectedDomain;

  // Update variable selectors for the given domain
  function updateVariableSelectors(domain) {
    variableSelect.innerHTML = "";
    newVariableSelect.innerHTML = '<option value="">Select a variable</option>';
    if (chartData[domain] && chartData[domain].length > 0) {
      var keys = Object.keys(chartData[domain][0]);
      keys.forEach(function(key) {
        // Exclude fields that represent the year
        if (key === "annee" || key === "Year") return;
        var option1 = document.createElement("option");
        option1.value = key;
        option1.text = key;
        variableSelect.appendChild(option1);

        var option2 = document.createElement("option");
        option2.value = key;
        option2.text = key;
        newVariableSelect.appendChild(option2);
      });
    }
    variableSelect.selectedIndex = 0;
    newVariableSelect.selectedIndex = 0;
  }
  updateVariableSelectors(selectedDomain);

  // Helper: Extract radar chart data for a domain.
  // We assume that the labels are the years (from field 'annee' or 'Year'),
  // and the default dataset is taken from the first variable in the variableSelect.
  function getChartData(domain) {
    var labels;
    if (domain === 'economie') {
      labels = chartData[domain].map(function(item) { return item.Year; });
    } else {
      labels = chartData[domain].map(function(item) { return item.annee; });
    }
    var defaultVar = variableSelect.options[0] ? variableSelect.options[0].value : "";
    var dataValues = chartData[domain].map(function(item) {
      return item[defaultVar];
    });
    return { labels: labels, data: dataValues, defaultVar: defaultVar };
  }

  // Get initial values for the chart.
  var initValues = getChartData(selectedDomain);

  // Create the radar chart
  var ctx = document.getElementById(config.canvasId).getContext('2d');
  var chartInstance = new Chart(ctx, {
    type: 'radar',
    data: {
      labels: initValues.labels,
      datasets: [{
        label: initValues.defaultVar,
        data: initValues.data,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        fill: true
      }]
    },
    options: {
      scale: {
        ticks: { beginAtZero: true }
      }
    }
  });

  // Domain change: update selectors and reinitialize chart data
  domainSelect.addEventListener("change", function() {
    selectedDomain = this.value;
    updateVariableSelectors(selectedDomain);
    newVariableSelect.style.display = "none";
    newVariableSelect.selectedIndex = 0;
    var newValues = getChartData(selectedDomain);
    chartInstance.data.labels = newValues.labels;
    chartInstance.data.datasets = [{
      label: newValues.defaultVar,
      data: newValues.data,
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1,
      fill: true
    }];
    chartInstance.update();
  });

  // Variable selector change: update the current dataset
  variableSelect.addEventListener("change", function() {
    var selectedVar = this.value;
    var newData = chartData[selectedDomain].map(function(item) {
      return item[selectedVar];
    });
    chartInstance.data.datasets[0].data = newData;
    chartInstance.data.datasets[0].label = selectedVar;
    chartInstance.update();
  });

  // Add new variable event: show/hide the new variable select and add dataset if selected
  addVarButton.addEventListener("click", function() {
    newVariableSelect.style.display = (newVariableSelect.style.display === "none" || newVariableSelect.style.display === "") 
                                      ? "block" : "none";
  });
  newVariableSelect.addEventListener("change", function() {
    var selectedVariable = this.value;
    if (!selectedVariable) return;
    var alreadyAdded = chartInstance.data.datasets.some(function(ds) {
      return ds.label === selectedVariable;
    });
    if (!alreadyAdded) {
      var newData = chartData[selectedDomain].map(function(item) {
        return item[selectedVariable];
      });
      var newDataset = {
        label: selectedVariable,
        data: newData,
        backgroundColor: randomRGBA(0.3),
        borderColor: randomRGBA(1),
        borderWidth: 1,
        fill: true
      };
      chartInstance.data.datasets.push(newDataset);
      chartInstance.update();
    } else {
      alert("This variable is already added.");
    }
    newVariableSelect.style.display = "none";
    newVariableSelect.selectedIndex = 0;
  });

  // Remove variable event: allow removal of datasets
  removeVarButton.addEventListener("click", function() {
    removeSelect.innerHTML = '<option value="">Select a variable to remove</option>';
    chartInstance.data.datasets.forEach(function(dataset) {
      var option = document.createElement("option");
      option.value = dataset.label;
      option.text = dataset.label;
      removeSelect.appendChild(option);
    });
    removeSelect.style.display = "block";
  });
  removeSelect.addEventListener("change", function() {
    var selectedVar = this.value;
    if (!selectedVar) return;
    chartInstance.data.datasets = chartInstance.data.datasets.filter(function(dataset) {
      return dataset.label !== selectedVar;
    });
    chartInstance.update();
    removeSelect.style.display = "none";
    removeSelect.selectedIndex = 0;
  });

  return chartInstance;
}

function initializeRadarChartV2(config) {
  const chartData = config.chartData;
  let selectedDomain = config.defaultDomain;

  const canvas = document.getElementById(config.canvasId);
  const addVarButton = document.getElementById(config.addVarButtonId);
  const variableTagsContainer = document.getElementById(config.variableTagsContainerId);

  const ctx = canvas.getContext("2d");

  function getRadarData(domain, variable) {
    const labels = chartData[domain].map(d => domain === 'economie' ? d.Year : d.annee);
    const data = chartData[domain].map(d => parseFloat(d[variable]) || 0);
    return { labels, data };
  }

  const defaultVar = Object.keys(chartData[selectedDomain][0]).find(k => k !== 'annee' && k !== 'Year');
  const initialData = getRadarData(selectedDomain, defaultVar);

  const chartInstance = new Chart(ctx, {
    type: 'radar',
    data: {
      labels: initialData.labels,
      datasets: [{
        label: defaultVar,
        data: initialData.data,
        domain: selectedDomain,
        backgroundColor: randomRGBA(0.2),
        borderColor: randomRGBA(1),
        borderWidth: 2,
        fill: true
      }]
    },
    options: { responsive: true, scales: { r: { beginAtZero: true } } }
  });

  function updateVariableTags() {
    variableTagsContainer.innerHTML = "";

    const description = document.createElement("p");
    description.textContent = "Cette interface vous permet d'ajouter, de modifier ou de supprimer des variables. Cliquez sur un tag pour modifier la variable correspondante, ou sur le bouton 'Ajouter une variable' pour en inclure une nouvelle. Les modifications seront appliquées en temps réel.";
    description.style.fontSize = "0.9em";
    description.style.color = "#555";
    description.style.marginBottom = "10px";
    description.style.gridColumn = "1 / -1";
    variableTagsContainer.appendChild(description);

    const header = document.createElement("h4");
    header.textContent = "Variables Actuelles";
    header.style.textAlign = "center";
    header.style.color = "#333";
    header.style.margin = "5px 0";
    header.style.gridColumn = "1 / -1";
    variableTagsContainer.appendChild(header);

    chartInstance.data.datasets.forEach((dataset, idx) => {
      const tag = document.createElement("div");
      tag.className = "tag";
      tag.textContent = dataset.label;
      tag.style.position = "relative";
      tag.style.padding = "5px 25px 5px 10px";
      tag.style.margin = "5px";
      tag.style.backgroundColor = "#ddd";
      tag.style.borderRadius = "3px";
      tag.style.cursor = "pointer";

      const removeIcon = document.createElement("span");
      removeIcon.textContent = "×";
      removeIcon.style.position = "absolute";
      removeIcon.style.right = "8px";
      removeIcon.style.top = "50%";
      removeIcon.style.transform = "translateY(-50%)";
      removeIcon.style.color = "#900";
      removeIcon.style.cursor = "pointer";
      removeIcon.onclick = (e) => {
        e.stopPropagation();
        chartInstance.data.datasets.splice(idx, 1);
        chartInstance.update();
        updateVariableTags();
      };
      tag.appendChild(removeIcon);

      tag.onclick = () => openVariableModal(dataset, true);
      variableTagsContainer.appendChild(tag);
    });
  }

  function openVariableModal(dataset = null, isEdit = false) {
    const modalOverlay = document.createElement("div");
    modalOverlay.style.cssText = "position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:1000;";
    const modalContent = document.createElement("div");
    modalContent.style.cssText = "background:#fff;padding:20px;border-radius:8px;min-width:300px;font-family:Arial;";

    const domainSelect = document.createElement("select");
    Object.keys(chartData).forEach(domain => {
      const opt = document.createElement("option");
      opt.value = domain;
      opt.textContent = domain;
      domainSelect.appendChild(opt);
    });
    domainSelect.value = dataset ? dataset.domain : selectedDomain;
    modalContent.appendChild(domainSelect);

    const variableSelect = document.createElement("select");
    modalContent.appendChild(variableSelect);

    function populateVariables(domain) {
      variableSelect.innerHTML = "";
      const alreadySelected = chartInstance.data.datasets
        .filter(ds => !isEdit || ds.label !== dataset.label)
        .map(ds => `${ds.domain}:${ds.label}`);

      Object.keys(chartData[domain][0])
        .filter(k => k !== 'annee' && k !== 'Year')
        .forEach(variable => {
          const identifier = `${domain}:${variable}`;
          if (!alreadySelected.includes(identifier)) {
            const opt = document.createElement("option");
            opt.value = variable;
            opt.textContent = variable;
            variableSelect.appendChild(opt);
          }
        });

      if (!variableSelect.options.length) {
        const opt = document.createElement("option");
        opt.textContent = "No available variables";
        opt.disabled = true;
        variableSelect.appendChild(opt);
      }
    }

    populateVariables(domainSelect.value);
    variableSelect.value = dataset ? dataset.label : variableSelect.options[0]?.value;

    domainSelect.onchange = () => populateVariables(domainSelect.value);

    const confirm = document.createElement("button");
    confirm.textContent = "Confirm";
    confirm.onclick = () => {
      if (!variableSelect.value || variableSelect.options[0].disabled) {
        alert("No valid variable selected.");
        return;
      }

      const newDomain = domainSelect.value;
      const newVar = variableSelect.value;
      const newData = getRadarData(newDomain, newVar);

      if (isEdit && dataset) {
        dataset.label = newVar;
        dataset.domain = newDomain;
        dataset.data = newData.data;
        dataset.backgroundColor = randomRGBA(0.2);
        dataset.borderColor = randomRGBA(1);
      } else {
        chartInstance.data.datasets.push({
          label: newVar,
          data: newData.data,
          domain: newDomain,
          backgroundColor: randomRGBA(0.2),
          borderColor: randomRGBA(1),
          borderWidth: 2,
          fill: true
        });
      }

      chartInstance.data.labels = newData.labels;
      chartInstance.update();
      updateVariableTags();
      document.body.removeChild(modalOverlay);
    };
    modalContent.appendChild(confirm);

    const cancel = document.createElement("button");
    cancel.textContent = "Cancel";
    cancel.style.marginLeft = "10px";
    cancel.onclick = () => document.body.removeChild(modalOverlay);
    modalContent.appendChild(cancel);

    modalOverlay.appendChild(modalContent);
    document.body.appendChild(modalOverlay);
  }

  addVarButton.onclick = () => openVariableModal();

  canvas.ondblclick = () => canvas.requestFullscreen?.();

  updateVariableTags();
  return chartInstance;
}


/**
 * Initializes a dynamic scatter chart.
 *
 * @param {Object} config - Configuration object with these properties:
 *   - canvasId: the ID of the <canvas> element.
 *   - domainSelectId: the ID of the domain <select> element.
 *   - xVariableSelectId: the ID of the <select> element for selecting the x variable.
 *   - yVariableSelectId: the ID of the <select> element for selecting the y variable.
 *   - newDatasetSelectId: the ID of a hidden <select> element used to add a new dataset.
 *       (This select should allow the user to choose a pair of variables – for simplicity, we assume it uses the same options as x and y selectors.)
 *   - addDatasetButtonId: the ID of the button to show the new dataset selector.
 *   - removeDatasetButtonId: the ID of the button to trigger dataset removal.
 *   - removeDatasetSelectId: the ID of the hidden <select> element used for removing a dataset.
 *   - defaultDomain: the domain to use by default.
 *   - chartData: the data object (an object with keys as domains and values as arrays of data rows).
 */
function initializeScatterChart(config) {
  // Extract configuration and data.
  var chartData = config.chartData;
  var selectedDomain = config.defaultDomain;

  // Get DOM elements.
  var domainSelect = document.getElementById(config.domainSelectId);
  var xVariableSelect = document.getElementById(config.xVariableSelectId);
  var yVariableSelect = document.getElementById(config.yVariableSelectId);
  var newDatasetSelect = document.getElementById(config.newDatasetSelectId);
  var addDatasetButton = document.getElementById(config.addDatasetButtonId);
  var removeDatasetButton = document.getElementById(config.removeDatasetButtonId);
  var removeDatasetSelect = document.getElementById(config.removeDatasetSelectId);

  // Populate the domain selector.
  domainSelect.innerHTML = "";
  Object.keys(chartData).forEach(function(domain) {
    var option = document.createElement("option");
    option.value = domain;
    option.text = domain;
    domainSelect.appendChild(option);
  });
  domainSelect.value = selectedDomain;

  // Update the x and y variable selectors based on the selected domain.
  function updateVariableSelectors(domain) {
    // Clear existing options.
    xVariableSelect.innerHTML = "";
    yVariableSelect.innerHTML = "";
    newDatasetSelect.innerHTML = '<option value="">Select a variable pair</option>';

    // Use the keys from the first row of the domain's data.
    if (chartData[domain] && chartData[domain].length > 0) {
      var keys = Object.keys(chartData[domain][0]);
      // Optionally, exclude some keys if needed (for example, "annee" or "Year").
      keys.forEach(function(key) {
        if (key === 'annee' || key === 'Year') return;
        var optionX = document.createElement("option");
        optionX.value = key;
        optionX.text = key;
        xVariableSelect.appendChild(optionX);

        var optionY = document.createElement("option");
        optionY.value = key;
        optionY.text = key;
        yVariableSelect.appendChild(optionY);

        // Also add option to the new dataset selector (we'll combine x and y selections later).
        var optionPair = document.createElement("option");
        optionPair.value = key; // Here we just reuse key for demonstration.
        optionPair.text = key;
        newDatasetSelect.appendChild(optionPair);
      });
    }
    xVariableSelect.selectedIndex = 0;
    yVariableSelect.selectedIndex = 0;
    newDatasetSelect.selectedIndex = 0;
  }
  updateVariableSelectors(selectedDomain);

  // Helper: Given a domain and chosen x and y variable names, create scatter data points.
  function getScatterData(domain, xVar, yVar) {
    return chartData[domain].map(function(item) {
      return { x: parseFloat(item[xVar]), y: parseFloat(item[yVar]) };
    }).filter(function(point) {
      // Filter out any points where x or y is not a number.
      return !isNaN(point.x) && !isNaN(point.y);
    });
  }

  // Get initial scatter data using default x and y from the selectors.
  var defaultXVar = xVariableSelect.options[0] ? xVariableSelect.options[0].value : "";
  var defaultYVar = yVariableSelect.options[0] ? yVariableSelect.options[0].value : "";
  var initScatterData = getScatterData(selectedDomain, defaultXVar, defaultYVar);

  // Get the canvas and create the scatter chart.
  var ctx = document.getElementById(config.canvasId).getContext('2d');
  var chartInstance = new Chart(ctx, {
    type: 'scatter',
    data: {
      labels: [], // Scatter charts do not require labels.
      datasets: [{
        label: defaultXVar + " vs " + defaultYVar,
        data: initScatterData,
        backgroundColor: 'rgba(255, 99, 132, 0.8)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          type: 'linear',
          position: 'bottom',
          title: { display: true, text: defaultXVar }
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: defaultYVar }
        }
      }
    }
  });

  // When the domain selection changes, update variable selectors and chart data.
  domainSelect.addEventListener("change", function() {
    selectedDomain = this.value;
    updateVariableSelectors(selectedDomain);
    // Get the new default variables.
    defaultXVar = xVariableSelect.options[0] ? xVariableSelect.options[0].value : "";
    defaultYVar = yVariableSelect.options[0] ? yVariableSelect.options[0].value : "";
    var newData = getScatterData(selectedDomain, defaultXVar, defaultYVar);
    // Update the dataset.
    chartInstance.data.datasets = [{
      label: defaultXVar + " vs " + defaultYVar,
      data: newData,
      backgroundColor: 'rgba(255, 99, 132, 0.8)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }];
    // Update axis titles.
    chartInstance.options.scales.x.title.text = defaultXVar;
    chartInstance.options.scales.y.title.text = defaultYVar;
    chartInstance.update();
  });

  // When either the x or y variable selection changes, update the dataset.
  // We attach event listeners to both selectors.
  function updateScatterDataset() {
    var selectedXVar = xVariableSelect.value;
    var selectedYVar = yVariableSelect.value;
    var newData = getScatterData(selectedDomain, selectedXVar, selectedYVar);
    chartInstance.data.datasets[0].data = newData;
    chartInstance.data.datasets[0].label = selectedXVar + " vs " + selectedYVar;
    chartInstance.options.scales.x.title.text = selectedXVar;
    chartInstance.options.scales.y.title.text = selectedYVar;
    chartInstance.update();
  }
  xVariableSelect.addEventListener("change", updateScatterDataset);
  yVariableSelect.addEventListener("change", updateScatterDataset);

  // "Add new dataset" functionality – allow adding an additional scatter dataset.
  addDatasetButton.addEventListener("click", function() {
    // Toggle the display of the new dataset select.
    newDatasetSelect.style.display = (newDatasetSelect.style.display === "none" || newDatasetSelect.style.display === "")
                                      ? "block" : "none";
  });
  newDatasetSelect.addEventListener("change", function() {
    var selectedVariable = this.value;
    if (!selectedVariable) return;
    // For an additional dataset, we assume that the user wants to use the same variable for both x and y,
    // or you can extend this logic to have two separate selectors for a new dataset.
    var newDatasetData = getScatterData(selectedDomain, selectedVariable, selectedVariable);
    // Check if a dataset with that label is already added.
    var alreadyAdded = chartInstance.data.datasets.some(function(ds) {
      return ds.label === selectedVariable;
    });
    if (!alreadyAdded) {
      var newDataset = {
        label: selectedVariable,
        data: newDatasetData,
        backgroundColor: randomRGBA(0.8),
        borderColor: randomRGBA(1),
        borderWidth: 1
      };
      chartInstance.data.datasets.push(newDataset);
      chartInstance.update();
    } else {
      alert("This dataset is already added.");
    }
    newDatasetSelect.style.display = "none";
    newDatasetSelect.selectedIndex = 0;
  });

  // "Remove dataset" functionality.
  removeDatasetButton.addEventListener("click", function() {
    removeDatasetSelect.innerHTML = '<option value="">Select a dataset to remove</option>';
    chartInstance.data.datasets.forEach(function(dataset) {
      var option = document.createElement("option");
      option.value = dataset.label;
      option.text = dataset.label;
      removeDatasetSelect.appendChild(option);
    });
    removeDatasetSelect.style.display = "block";
  });
  removeDatasetSelect.addEventListener("change", function() {
    var selectedLabel = this.value;
    if (!selectedLabel) return;
    chartInstance.data.datasets = chartInstance.data.datasets.filter(function(dataset) {
      return dataset.label !== selectedLabel;
    });
    chartInstance.update();
    removeDatasetSelect.style.display = "none";
    removeDatasetSelect.selectedIndex = 0;
  });

  return chartInstance;
}

function initializeDualDomainScatterChartV2(config) {
    const { chartData, defaultXDomain, defaultYDomain, canvasId, variableTagsContainerId } = config;

    let xDomain = defaultXDomain;
    let yDomain = defaultYDomain;

    const variableTagsContainer = document.getElementById(variableTagsContainerId);
    const ctx = document.getElementById(canvasId).getContext('2d');

    // Helper functions
    const getYears = domain => (chartData[domain] || []).map(d => d.annee || d.Year).filter(Boolean);
    const getCommonYears = (a, b) => a.filter(y => b.includes(y));

    const getScatterData = (xDomain, yDomain, xVar, yVar) => {
        const commonYears = getCommonYears(getYears(xDomain), getYears(yDomain));
        return commonYears.map(year => {
            const xVal = parseFloat(chartData[xDomain].find(d => (d.annee || d.Year) == year)[xVar]);
            const yVal = parseFloat(chartData[yDomain].find(d => (d.annee || d.Year) == year)[yVar]);
            return (isNaN(xVal) || isNaN(yVal)) ? null : { x: xVal, y: yVal };
        }).filter(Boolean);
    };

    const calculateLinearRegression = data => {
        const n = data.length;
        const sumX = data.reduce((a, b) => a + b.x, 0);
        const sumY = data.reduce((a, b) => a + b.y, 0);
        const sumXY = data.reduce((a, b) => a + b.x * b.y, 0);
        const sumXX = data.reduce((a, b) => a + b.x * b.x, 0);
        const slope = (n * sumXY - sumX * sumY) / (n * sumXX - sumX * sumX);
        const intercept = (sumY - slope * sumX) / n;
        return { slope, intercept };
    };

    const getTrendLinePoints = (data, { slope, intercept }) => {
        const xs = data.map(p => p.x);
        const [minX, maxX] = [Math.min(...xs), Math.max(...xs)];
        return [{ x: minX, y: slope * minX + intercept }, { x: maxX, y: slope * maxX + intercept }];
    };

    // Initial variables
    let xVariable = Object.keys(chartData[xDomain][0]).find(k => !['annee', 'Year'].includes(k));
    let yVariable = Object.keys(chartData[yDomain][0]).find(k => !['annee', 'Year'].includes(k));

    const initialData = getScatterData(xDomain, yDomain, xVariable, yVariable);

    const chartInstance = new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: `${xVariable} vs ${yVariable}`,
                data: initialData,
                backgroundColor: 'rgba(255,99,132,0.7)',
                borderColor: 'rgba(255,99,132,1)',
                pointRadius: 5,
            }]
        },
        options: {
            scales: {
                x: { title: { display: true, text: xVariable } },
                y: { title: { display: true, text: yVariable }, beginAtZero: true }
            }
        }
    });

    const updateScatterChart = () => {
        const newData = getScatterData(xDomain, yDomain, xVariable, yVariable);
        chartInstance.data.datasets[0].data = newData;
        chartInstance.data.datasets[0].label = `${xVariable} vs ${yVariable}`;
        chartInstance.options.scales.x.title.text = xVariable;
        chartInstance.options.scales.y.title.text = yVariable;

        if (newData.length > 1) {
            const regression = calculateLinearRegression(newData);
            const trendLinePoints = getTrendLinePoints(newData, regression);
            if (chartInstance.data.datasets[1]) {
                chartInstance.data.datasets[1].data = trendLinePoints;
            } else {
                chartInstance.data.datasets.push({
                    type: 'line',
                    label: 'Trendline',
                    data: trendLinePoints,
                    borderColor: 'rgba(75,192,192,1)',
                    borderWidth: 2,
                    pointRadius: 0,
                    borderDash: [5, 5],
                    fill: false
                });
            }
        } else if (chartInstance.data.datasets.length > 1) {
            chartInstance.data.datasets.pop();
        }
        chartInstance.update();
        renderVariableTags();
    };

    const createModal = (isXVariable) => {
        const modalOverlay = document.createElement('div');
        modalOverlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;justify-content:center;align-items:center;z-index:1000;';
        
        const modal = document.createElement('div');
        modal.style.cssText = 'background:#fff;padding:20px;border-radius:8px;width:300px;';
        
        const domainSelect = document.createElement('select');
        Object.keys(chartData).forEach(domain => {
            const opt = document.createElement('option');
            opt.value = domain;
            opt.textContent = domain;
            domainSelect.appendChild(opt);
        });
        domainSelect.value = isXVariable ? xDomain : yDomain;
        
        const variableSelect = document.createElement('select');
        const populateVariables = () => {
            variableSelect.innerHTML = '';
            Object.keys(chartData[domainSelect.value][0])
                .filter(k => !['annee', 'Year'].includes(k))
                .forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v;
                    opt.textContent = v;
                    variableSelect.appendChild(opt);
                });
        };
        populateVariables();
        variableSelect.value = isXVariable ? xVariable : yVariable;

        domainSelect.onchange = populateVariables;

        const confirmBtn = document.createElement('button');
        confirmBtn.textContent = 'Confirm';
        confirmBtn.onclick = () => {
            if (isXVariable) {
                xDomain = domainSelect.value;
                xVariable = variableSelect.value;
            } else {
                yDomain = domainSelect.value;
                yVariable = variableSelect.value;
            }
            updateScatterChart();
            document.body.removeChild(modalOverlay);
        };

        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.onclick = () => document.body.removeChild(modalOverlay);
        cancelBtn.style.marginLeft = '10px';

        modal.append(domainSelect, variableSelect, confirmBtn, cancelBtn);
        modalOverlay.appendChild(modal);
        document.body.appendChild(modalOverlay);
    };

    const renderVariableTags = () => {
        variableTagsContainer.innerHTML = '';

        // Create and add the explanation text
        const explanation = document.createElement('p');
        explanation.textContent = "Cliquer sur les etiquettes pour modifier les variables. Les modifications seront appliquées en temps réel.";
        explanation.style.fontStyle = 'italic';
        explanation.style.marginBottom = '10px';
        explanation.style.color = '#2c3e50'; // Set explanation text color (e.g., dark blue/gray)
        variableTagsContainer.appendChild(explanation);

        // Create and add the variable tags
        ['X: ' + xVariable, 'Y: ' + yVariable].forEach((text, idx) => {
            const tag = document.createElement('span');
            tag.textContent = text;
            tag.style.cssText = 'background:#e74c3c;padding:5px 10px;border-radius:12px;cursor:pointer;margin-right:10px;';
            tag.onclick = () => createModal(idx === 0);
            variableTagsContainer.appendChild(tag);
      });
    };

    renderVariableTags();

    return chartInstance;
}




/**
 * Initializes a scatter chart that uses two different domains for x and y variables.
 * It computes the intersection of available years in both domains and builds scatter points.
 *
 * @param {Object} config - Configuration object with properties:
 *   - canvasId: ID of the <canvas> element.
 *   - xDomainSelectId: ID of the x-domain <select> element.
 *   - xVariableSelectId: ID of the x-variable <select> element.
 *   - yDomainSelectId: ID of the y-domain <select> element.
 *   - yVariableSelectId: ID of the y-variable <select> element.
 *   - defaultXDomain: Default domain for x values.
 *   - defaultYDomain: Default domain for y values.
 *   - chartData: The complete data object (keys are domains, values are arrays of data rows).
 */
function initializeDualDomainScatterChart(config) {
  var chartData = config.chartData;
  var xSelectedDomain = config.defaultXDomain;
  var ySelectedDomain = config.defaultYDomain;

  // Get DOM elements
  var xDomainSelect = document.getElementById(config.xDomainSelectId);
  var xVariableSelect = document.getElementById(config.xVariableSelectId);
  var yDomainSelect = document.getElementById(config.yDomainSelectId);
  var yVariableSelect = document.getElementById(config.yVariableSelectId);

  // Populate domain selectors
  function populateDomainSelector(selector, defaultDomain) {
    selector.innerHTML = "";
    Object.keys(chartData).forEach(function(domain) {
      var option = document.createElement("option");
      option.value = domain;
      option.text = domain;
      selector.appendChild(option);
    });
    selector.value = defaultDomain;
  }
  populateDomainSelector(xDomainSelect, xSelectedDomain);
  populateDomainSelector(yDomainSelect, ySelectedDomain);

  // Populate variable selectors based on a domain.
  function updateVariableSelector(domain, variableSelect) {
    variableSelect.innerHTML = "";
    if (chartData[domain] && chartData[domain].length > 0) {
      var keys = Object.keys(chartData[domain][0]);
      keys.forEach(function(key) {
        // Exclude the year fields
        if (key === "annee" || key === "Year") return;
        var option = document.createElement("option");
        option.value = key;
        option.text = key;
        variableSelect.appendChild(option);
      });
    }
    if (variableSelect.options.length > 0) {
      variableSelect.selectedIndex = 0;
    }
  }
  updateVariableSelector(xSelectedDomain, xVariableSelect);
  updateVariableSelector(ySelectedDomain, yVariableSelect);

  // Helper: Extract available years from a domain's data.
  function getYears(domain) {
    if (!chartData[domain]) return [];
    var years = chartData[domain].map(function(item) {
      return item.annee || item.Year;
    });
    return years.filter(Boolean).map(String);
  }

  // Helper: Get intersection of two arrays.
  function getCommonYears(arr1, arr2) {
    return arr1.filter(year => arr2.includes(year)).sort();
  }

  // Helper: Build scatter data points from two domains.
  function getScatterData(xDomain, yDomain, xVar, yVar) {
    var xYears = getYears(xDomain);
    var yYears = getYears(yDomain);
    var commonYears = getCommonYears(xYears, yYears);
    var dataPoints = commonYears.map(function(year) {
      var xRow = chartData[xDomain].find(item => (item.annee || item.Year) == year);
      var yRow = chartData[yDomain].find(item => (item.annee || item.Year) == year);
      var xVal = xRow ? parseFloat(xRow[xVar]) : null;
      var yVal = yRow ? parseFloat(yRow[yVar]) : null;
      if (xVal == null || isNaN(xVal) || yVal == null || isNaN(yVal)) {
        return null;
      }
      return { x: xVal, y: yVal };
    });
    return dataPoints.filter(point => point !== null);
  }

  // Helper: Calculate linear regression parameters (slope and intercept)
  function calculateLinearRegression(dataPoints) {
    var n = dataPoints.length;
    if (n === 0) return null;
    var sumX = 0, sumY = 0, sumXY = 0, sumXX = 0;
    dataPoints.forEach(p => {
      sumX += p.x;
      sumY += p.y;
      sumXY += p.x * p.y;
      sumXX += p.x * p.x;
    });
    var slope = (n * sumXY - sumX * sumY) / (n * sumXX - sumX * sumX);
    var intercept = (sumY - slope * sumX) / n;
    return { slope, intercept };
  }

  // Helper: Generate two points for the trend line using the regression parameters.
  function getTrendLinePoints(dataPoints, regression) {
    var xs = dataPoints.map(p => p.x);
    var minX = Math.min(...xs);
    var maxX = Math.max(...xs);
    return [
      { x: minX, y: regression.slope * minX + regression.intercept },
      { x: maxX, y: regression.slope * maxX + regression.intercept }
    ];
  }

  // Get initial variables and data points.
  var defaultXVar = xVariableSelect.options[0] ? xVariableSelect.options[0].value : "";
  var defaultYVar = yVariableSelect.options[0] ? yVariableSelect.options[0].value : "";
  var initialData = getScatterData(xSelectedDomain, ySelectedDomain, defaultXVar, defaultYVar);

  // Create the scatter chart.
  var ctx = document.getElementById(config.canvasId).getContext('2d');
  var chartInstance = new Chart(ctx, {
    type: 'scatter',
    data: {
      // Dataset 0: The scatter data points.
      datasets: [{
        label: defaultXVar + " vs " + defaultYVar,
        data: initialData,
        backgroundColor: 'rgba(255, 99, 132, 0.8)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1,
        pointRadius: 5
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          type: 'linear',
          position: 'bottom',
          title: { display: true, text: defaultXVar }
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: defaultYVar }
        }
      }
    }
  });

  // Update the scatter chart data and trendline.
  function updateScatterChart() {
    var selectedXVar = xVariableSelect.value;
    var selectedYVar = yVariableSelect.value;
    var newData = getScatterData(xSelectedDomain, ySelectedDomain, selectedXVar, selectedYVar);
    chartInstance.data.datasets[0].data = newData;
    chartInstance.data.datasets[0].label = selectedXVar + " vs " + selectedYVar;
    chartInstance.options.scales.x.title.text = selectedXVar;
    chartInstance.options.scales.y.title.text = selectedYVar;
    
    // Calculate regression and trend line if there is data.
    if (newData.length > 0) {
      var regression = calculateLinearRegression(newData);
      if (regression) {
        var trendLinePoints = getTrendLinePoints(newData, regression);
        // Check if trendline dataset already exists (dataset index 1)
        if (chartInstance.data.datasets.length < 2) {
          chartInstance.data.datasets.push({
            type: 'line',
            label: 'Trendline',
            data: trendLinePoints,
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: false,
            pointRadius: 0,
            borderDash: [5, 5]
          });
        } else {
          chartInstance.data.datasets[1].data = trendLinePoints;
        }
      }
    } else {
      // If no data, remove trendline if it exists.
      if (chartInstance.data.datasets.length >= 2) {
        chartInstance.data.datasets.splice(1, 1);
      }
    }
    chartInstance.update();
  }

  // Event listeners for domain and variable changes.
  xDomainSelect.addEventListener("change", function() {
    xSelectedDomain = this.value;
    updateVariableSelector(xSelectedDomain, xVariableSelect);
    updateScatterChart();
  });

  yDomainSelect.addEventListener("change", function() {
    ySelectedDomain = this.value;
    updateVariableSelector(ySelectedDomain, yVariableSelect);
    updateScatterChart();
  });

  xVariableSelect.addEventListener("change", updateScatterChart);
  yVariableSelect.addEventListener("change", updateScatterChart);

  return chartInstance;
}


/**
 * Initializes a histogram chart that aggregates numeric values into bins.
 * It allows selection of a domain and variable and supports adding and removing datasets.
 *
 * @param {Object} config - Configuration object with properties:
 *   - canvasId: ID of the <canvas> element.
 *   - domainSelectId: ID of the <select> element for domain selection.
 *   - variableSelectId: ID of the <select> element for variable selection.
 *   - newVariableSelectId: ID of the hidden <select> element for adding a new variable.
 *   - addVarButtonId: ID of the button to show the new variable selector.
 *   - removeVarButtonId: ID of the button to trigger dataset removal.
 *   - removeSelectId: ID of the hidden <select> element used for removing a dataset.
 *   - defaultDomain: The default domain to display.
 *   - chartData: The complete data object (keys are domains, values are arrays of data rows).
 *   - numBins (optional): Number of bins for the histogram (default: 10).
 */
function initializeHistogramChart(config) {
  var chartData = config.chartData;
  var selectedDomain = config.defaultDomain;
  var numBins = config.numBins || 10;

  // Get DOM elements
  var domainSelect = document.getElementById(config.domainSelectId);
  var variableSelect = document.getElementById(config.variableSelectId);
  var newVariableSelect = document.getElementById(config.newVariableSelectId);
  var addVarButton = document.getElementById(config.addVarButtonId);
  var removeVarButton = document.getElementById(config.removeVarButtonId);
  var removeSelect = document.getElementById(config.removeSelectId);

  // Populate the domain selector
  domainSelect.innerHTML = "";
  Object.keys(chartData).forEach(function(domain) {
    var option = document.createElement("option");
    option.value = domain;
    option.text = domain;
    domainSelect.appendChild(option);
  });
  domainSelect.value = selectedDomain;

  // Update the variable selector based on the selected domain.
  function updateVariableSelectors(domain) {
    variableSelect.innerHTML = "";
    newVariableSelect.innerHTML = '<option value="">Select a variable</option>';
    if (chartData[domain] && chartData[domain].length > 0) {
      var keys = Object.keys(chartData[domain][0]);
      keys.forEach(function(key) {
        // Exclude fields that represent the year
        if (key === "annee" || key === "Year") return;
        var option1 = document.createElement("option");
        option1.value = key;
        option1.text = key;
        variableSelect.appendChild(option1);

        var option2 = document.createElement("option");
        option2.value = key;
        option2.text = key;
        newVariableSelect.appendChild(option2);
      });
    }
    variableSelect.selectedIndex = 0;
    newVariableSelect.selectedIndex = 0;
  }
  updateVariableSelectors(selectedDomain);

  // Helper: Compute histogram bins for an array of numbers.
  function computeHistogram(dataArray, numBins) {
    // Determine min and max values.
    var min = Math.min(...dataArray);
    var max = Math.max(...dataArray);
    var binWidth = (max - min) / numBins;
    var bins = new Array(numBins).fill(0);
    var labels = [];
    for (var i = 0; i < numBins; i++) {
      var lower = min + i * binWidth;
      var upper = lower + binWidth;
      labels.push(lower.toFixed(2) + " - " + upper.toFixed(2));
    }
    // Count values for each bin.
    dataArray.forEach(function(value) {
      var binIndex = Math.floor((value - min) / binWidth);
      if (binIndex === numBins) binIndex--; // handle edge case for max
      bins[binIndex]++;
    });
    return { bins: bins, labels: labels };
  }

  // Helper: For a given domain and variable, compute histogram data.
  function getHistogramData(domain, variable, numBins) {
    var values = chartData[domain].map(function(item) {
      return parseFloat(item[variable]);
    }).filter(function(num) {
      return !isNaN(num);
    });
    return computeHistogram(values, numBins);
  }

  // Get initial chart data using the default variable.
  var defaultVar = variableSelect.options[0] ? variableSelect.options[0].value : "";
  var initHistData = getHistogramData(selectedDomain, defaultVar, numBins);

  // Create the histogram chart (using a bar chart type).
  var ctx = document.getElementById(config.canvasId).getContext('2d');
  var chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: initHistData.labels,
      datasets: [{
        label: defaultVar,
        data: initHistData.bins,
        backgroundColor: randomRGBA(0.6),
        borderColor: randomRGBA(1),
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: { display: true, text: defaultVar + " Bins" }
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: "Count" }
        }
      }
    }
  });

  // When the domain selection changes.
  domainSelect.addEventListener("change", function() {
    selectedDomain = this.value;
    updateVariableSelectors(selectedDomain);
    var newDefaultVar = variableSelect.options[0] ? variableSelect.options[0].value : "";
    var newHistData = getHistogramData(selectedDomain, newDefaultVar, numBins);
    chartInstance.data.labels = newHistData.labels;
    chartInstance.data.datasets = [{
      label: newDefaultVar,
      data: newHistData.bins,
      backgroundColor: randomRGBA(0.6),
      borderColor: randomRGBA(1),
      borderWidth: 1
    }];
    chartInstance.options.scales.x.title.text = newDefaultVar + " Bins";
    chartInstance.update();
  });

  // When variable selection changes.
  variableSelect.addEventListener("change", function() {
    var selectedVar = this.value;
    var newHistData = getHistogramData(selectedDomain, selectedVar, numBins);
    chartInstance.data.labels = newHistData.labels;
    chartInstance.data.datasets[0].data = newHistData.bins;
    chartInstance.data.datasets[0].label = selectedVar;
    chartInstance.options.scales.x.title.text = selectedVar + " Bins";
    chartInstance.update();
  });

  // "Add new variable" functionality: allow adding an extra histogram dataset.
  addVarButton.addEventListener("click", function() {
    newVariableSelect.style.display = (newVariableSelect.style.display === "none" || newVariableSelect.style.display === "") 
                                        ? "block" : "none";
  });
  newVariableSelect.addEventListener("change", function() {
    var selectedVariable = this.value;
    if (!selectedVariable) return;
    var alreadyAdded = chartInstance.data.datasets.some(function(ds) {
      return ds.label === selectedVariable;
    });
    if (!alreadyAdded) {
      var newHistData = getHistogramData(selectedDomain, selectedVariable, numBins);
      var newDataset = {
        label: selectedVariable,
        data: newHistData.bins,
        backgroundColor: randomRGBA(0.6),
        borderColor: randomRGBA(1),
        borderWidth: 1
      };
      chartInstance.data.datasets.push(newDataset);
      chartInstance.update();
    } else {
      alert("This variable is already added.");
    }
    newVariableSelect.style.display = "none";
    newVariableSelect.selectedIndex = 0;
  });

  // "Remove dataset" functionality.
  removeVarButton.addEventListener("click", function() {
    removeSelect.innerHTML = '<option value="">Select a variable to remove</option>';
    chartInstance.data.datasets.forEach(function(dataset) {
      var option = document.createElement("option");
      option.value = dataset.label;
      option.text = dataset.label;
      removeSelect.appendChild(option);
    });
    removeSelect.style.display = "block";
  });
  removeSelect.addEventListener("change", function() {
    var selectedVar = this.value;
    if (!selectedVar) return;
    chartInstance.data.datasets = chartInstance.data.datasets.filter(function(dataset) {
      return dataset.label !== selectedVar;
    });
    chartInstance.update();
    removeSelect.style.display = "none";
    removeSelect.selectedIndex = 0;
  });

  return chartInstance;
  }

function initializeHistogramChartV2(config) {
  const chartData = config.chartData;
  const numBins = config.numBins || 10;
  let selectedDomain = config.defaultDomain;

  const canvas = document.getElementById(config.canvasId);
  const addVarButton = document.getElementById(config.addVarButtonId);
  const variableTagsContainer = document.getElementById(config.variableTagsContainerId);

  const ctx = canvas.getContext("2d");

  function computeHistogram(dataArray, binsCount) {
    const min = Math.min(...dataArray);
    const max = Math.max(...dataArray);
    const binWidth = (max - min) / binsCount;
    const bins = Array(binsCount).fill(0);
    const labels = [];

    for (let i = 0; i < binsCount; i++) {
      const lower = min + i * binWidth;
      const upper = lower + binWidth;
      labels.push(`${lower.toFixed(2)} - ${upper.toFixed(2)}`);
    }

    dataArray.forEach(value => {
      let bin = Math.floor((value - min) / binWidth);
      if (bin === binsCount) bin--;
      bins[bin]++;
    });

    return { bins, labels };
  }

  function getHistogramData(domain, variable) {
    const values = chartData[domain]
      .map(d => parseFloat(d[variable]))
      .filter(v => !isNaN(v));
    return computeHistogram(values, numBins);
  }

  const defaultVar = Object.keys(chartData[selectedDomain][0]).find(k => k !== 'annee' && k !== 'Year');
  const initialData = getHistogramData(selectedDomain, defaultVar);

  const chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: initialData.labels,
      datasets: [{
        label: defaultVar,
        data: initialData.bins,
        domain: selectedDomain,
        backgroundColor: randomRGBA(0.6),
        borderColor: randomRGBA(1),
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: { title: { display: true, text: `${defaultVar} Bins` } },
        y: { beginAtZero: true, title: { display: true, text: 'Count' } }
      }
    }
  });

  function updateVariableTags() {
    variableTagsContainer.innerHTML = "";

    // Variable zone description
    const description = document.createElement("p");
    description.textContent = "Cette interface vous permet d'ajouter, enlever ou editer les variables du graphique. Cliquer sur une etiquette pour la modifier ou cliquer sur 'Ajouter une varaible' pour ajouter une nouvelle variable au graph.";
    description.style.fontSize = "0.9em";
    description.style.color = "#555";
    description.style.marginBottom = "10px";
    description.style.gridColumn = "1 / -1";
    variableTagsContainer.appendChild(description);

    // Tag header
    const header = document.createElement("h4");
    header.textContent = "Variables actuelles";
    header.style.textAlign = "center";
    header.style.color = "#333";
    header.style.margin = "5px 0";
    header.style.gridColumn = "1 / -1";
    variableTagsContainer.appendChild(header);

    chartInstance.data.datasets.forEach((dataset, idx) => {
      const tag = document.createElement("div");
      tag.className = "tag";
      tag.textContent = dataset.label;
      tag.style.position = "relative";
      tag.style.padding = "5px 25px 5px 10px";
      tag.style.margin = "5px";
      tag.style.backgroundColor = "#ddd";
      tag.style.borderRadius = "3px";
      tag.style.cursor = "pointer";

      const removeIcon = document.createElement("span");
      removeIcon.textContent = "×";
      removeIcon.style.position = "absolute";
      removeIcon.style.right = "8px";
      removeIcon.style.top = "50%";
      removeIcon.style.transform = "translateY(-50%)";
      removeIcon.style.color = "#900";
      removeIcon.style.cursor = "pointer";

      removeIcon.onclick = (e) => {
        e.stopPropagation();
        chartInstance.data.datasets.splice(idx, 1);
        chartInstance.update();
        updateVariableTags();
      };
      tag.appendChild(removeIcon);

      tag.onclick = () => openVariableModal(dataset, true);
      variableTagsContainer.appendChild(tag);
    });
  }

  function openVariableModal(dataset = null, isEdit = false) {
    const modalOverlay = document.createElement("div");
    modalOverlay.style.cssText = "position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:1000;";
    const modalContent = document.createElement("div");
    modalContent.style.cssText = "background:#fff;padding:20px;border-radius:8px;min-width:300px;font-family:Arial;";

    const domainSelect = document.createElement("select");
    Object.keys(chartData).forEach(domain => {
      const opt = document.createElement("option");
      opt.value = domain;
      opt.textContent = domain;
      domainSelect.appendChild(opt);
    });
    domainSelect.value = dataset ? dataset.domain : selectedDomain;
    modalContent.appendChild(domainSelect);

    const variableSelect = document.createElement("select");
    modalContent.appendChild(variableSelect);

    function populateVariables(domain) {
      variableSelect.innerHTML = "";
      const alreadySelected = chartInstance.data.datasets
        .filter(ds => !isEdit || ds.label !== dataset.label)
        .map(ds => `${ds.domain}:${ds.label}`);

      Object.keys(chartData[domain][0])
        .filter(k => k !== 'annee' && k !== 'Year')
        .forEach(variable => {
          const identifier = `${domain}:${variable}`;
          if (!alreadySelected.includes(identifier)) {
            const opt = document.createElement("option");
            opt.value = variable;
            opt.textContent = variable;
            variableSelect.appendChild(opt);
          }
        });

      if (!variableSelect.options.length) {
        const opt = document.createElement("option");
        opt.textContent = "No available variables";
        opt.disabled = true;
        variableSelect.appendChild(opt);
      }
    }

    populateVariables(domainSelect.value);
    variableSelect.value = dataset ? dataset.label : variableSelect.options[0]?.value;

    domainSelect.onchange = () => populateVariables(domainSelect.value);

    const confirm = document.createElement("button");
    confirm.textContent = "Confirm";
    confirm.onclick = () => {
      if (!variableSelect.value || variableSelect.options[0].disabled) {
        alert("No valid variable selected.");
        return;
      }

      const newDomain = domainSelect.value;
      const newVar = variableSelect.value;
      const newHist = getHistogramData(newDomain, newVar);

      if (isEdit && dataset) {
        dataset.label = newVar;
        dataset.domain = newDomain;
        dataset.data = newHist.bins;
        dataset.backgroundColor = randomRGBA(0.6);
        dataset.borderColor = randomRGBA(1);
      } else {
        chartInstance.data.datasets.push({
          label: newVar,
          data: newHist.bins,
          domain: newDomain,
          backgroundColor: randomRGBA(0.6),
          borderColor: randomRGBA(1),
          borderWidth: 1
        });
      }

      chartInstance.data.labels = newHist.labels;
      chartInstance.update();
      updateVariableTags();
      document.body.removeChild(modalOverlay);
    };
    modalContent.appendChild(confirm);

    const cancel = document.createElement("button");
    cancel.textContent = "Cancel";
    cancel.style.marginLeft = "10px";
    cancel.onclick = () => document.body.removeChild(modalOverlay);
    modalContent.appendChild(cancel);

    modalOverlay.appendChild(modalContent);
    document.body.appendChild(modalOverlay);
  }

  addVarButton.onclick = () => openVariableModal();

  canvas.ondblclick = () => canvas.requestFullscreen?.();

  updateVariableTags();
  return chartInstance;
}





  // ===================== Main Script =====================

    var chartData = <?php echo json_encode($data); ?>;
    console.log(chartData);

    // Example usage of your existing chart initialization functions:
    var barChart = initializeDynamicChartV3({
      chartType: 'bar',
      canvasId: 'barChart',
      newVariableSelectId: 'bar_newVariableSelect',
      addVarButtonId: 'bar_addVarButton',
      variableTagsContainerId: 'bar_variableTagsContainer', // Add this line
      defaultDomain: 'bonheur',
      chartData: chartData
    });
 
    var lineChart = initializeDynamicChartV3({
      chartType: 'line',
      canvasId: 'lineplotChart',
      newVariableSelectId: 'line_newVariableSelect',
      addVarButtonId: 'line_addVarButton',
      variableTagsContainerId: 'line_variableTagsContainer',
      defaultDomain: 'agroalimentaire',
      chartData: chartData
    });

    var pieChart = initializePieDistributionChart({
      canvasId: 'pieChart',
      graphType: 'pie',
      domainSelectId: 'pie_domainSelect',
      defaultDomain: 'travail',
      chartData: chartData,
      excludeFields: ['annee', 'Year']
    });

    var polarChart = initializePieDistributionChart({
      canvasId: 'polarChart',
      graphType: 'polarArea',
      domainSelectId: 'polar_domainSelect',
      defaultDomain: 'meteo',
      chartData: chartData,
      excludeFields: ['annee', 'Year']
    });

    var donutChart = initializePieDistributionChart({
      canvasId: 'donutChart',
      graphType: 'doughnut',
      domainSelectId: 'donut_domainSelect',
      defaultDomain: 'religion',
      chartData: chartData,
      excludeFields: ['annee', 'Year']
    });

    var radarChart = initializeRadarChartV2({
      canvasId: 'radarChart',
      newVariableSelectId: 'radar_newVariableSelect',
      addVarButtonId: 'radar_addVarButton',
      variableTagsContainerId: 'radar_variableTagsContainer',
      defaultDomain: 'meteo',
      chartData: chartData
    });

    var scatterChart = initializeDualDomainScatterChartV2({
      canvasId: 'scatterChart',
      variableTagsContainerId: 'scatter_variableTagsContainer',
      defaultXDomain: 'agroalimentaire', // or your preferred domain
      defaultYDomain: 'travail',         // or your preferred domain
      chartData: chartData
    });

    var histogramChart = initializeHistogramChartV2({
      canvasId: 'histChart',
      newVariableSelectId: 'hist_newVariableSelect',
      addVarButtonId: 'hist_addVarButton',
      variableTagsContainerId: 'hist_variableTagsContainer',
      defaultDomain: 'travail', // adjust to your desired default domain
      chartData: chartData,     // your chartData object from PHP
      numBins: 10
    });


    // ===================== Mini Charts for Top Cards =====================

    // -- Helping functions --
    function formatNumber(num) {
        if (num >= 1e9) {
            return (num / 1e9).toFixed(1) + 'B';
        } else if (num >= 1e6) {
            return (num / 1e6).toFixed(1) + 'M';
        } else if (num >= 1e3) {
            return (num / 1e3).toFixed(1) + 'K';
        }
        return num.toString();
    }

    function mean(arr) {
        if (arr.length === 0) return 0; // or handle empty array as needed
        const sum = arr.reduce((acc, val) => acc + val, 0);
        return sum / arr.length;
    }

    // --- Data mini radar chart ---

    // Assume chartData['economie'] is an array of objects.
    const economie = chartData['economie'];

    // Get all keys from the first object (assuming consistency)
    const allKeys = Object.keys(economie[0]);

    // Compute the mean for each key that contains numeric values.
    const means = allKeys.map(key => {
    // Convert values to numbers and filter out non-numeric results
    const numericValues = economie
        .map(item => parseFloat(item[key]))
        .filter(value => !isNaN(value));

    // Calculate the mean if there are numeric values.
    const mean =
        numericValues.length > 0
        ? numericValues.reduce((sum, value) => sum + value, 0) /
            numericValues.length
        : 0;

    return { key, mean };
    });

    // Optional: Exclude keys that you don't consider (e.g., "Year")
    const filteredMeans = means.filter(item => ['Agriculture, hunting, forestry, fishing (ISIC A-B)', 'Construction (ISIC F)', 'Transport, storage and communication (ISIC I)', 'Wholesale, retail trade, restaurants and hotels (ISIC G-H)', 'Imports of goods and services', 'Manufacturing (ISIC D)', 'Exports of goods and services'].includes(item.key));

    // Sort the keys by mean in descending order.
    const sortedMeans = filteredMeans.sort((a, b) => b.mean - a.mean);

    // Extract the top 5 keys along with their mean values.
    const topFive = sortedMeans.slice(0, 5);

    radar_labels = topFive.map(item => item.key);
    radar_data = topFive.map(item => item.mean);



    // -- Data mini barplot chart --

    var autumn_temp = chartData['meteo']
        .map(item => parseFloat(item['automne_tavg']));

    var winter_temp = chartData['meteo']
        .map(item => parseFloat(item['hiver_tavg']));

    var summer_temp = chartData['meteo']
        .map(item => parseFloat(item['ete_tavg']));

    var spring_temp = chartData['meteo']
        .map(item => parseFloat(item['printemps_tavg']));

    var autumn_temp_moy = mean(autumn_temp);
    var winter_temp_moy = mean(winter_temp);
    var summer_temp_moy = mean(summer_temp);
    var spring_temp_moy = mean(spring_temp);
        

    // -- Data mini lineplot chart --

    var morts = chartData['sante']
        .map(item => parseFloat(item['mort']))
        .filter(value => value !== 0);

    var naissances = chartData['sante']
        .map(item => parseFloat(item['naissance']))
        .filter(value => value !== 0);

    var years = chartData['sante']
        .map(item => parseFloat(item['annee']))
        .filter(value => value >= 1950 && value <= 2024);

    // -- Data mini pie chart --

    var pie_labels = Object.keys(chartData['travail'][0]);
    pie_labels.shift();

    var sans_emploi_homme = chartData['travail'].map(item => parseFloat(item['sans_emploi_homme']));
    var sans_emploi_femme = chartData['travail'].map(item => parseFloat(item['sans_emploi_femme']));

    var pie_data = [mean(sans_emploi_homme), mean(sans_emploi_femme)];

    console.log(sans_emploi_homme, sans_emploi_femme);

    // -- Data mini pie chart --

    function wrapLabel(label, maxCharsPerLine) {
      const words = label.split(' ');
      let wrappedLabel = '';
      let line = '';

      words.forEach(word => {
        if ((line + word).length > maxCharsPerLine) {
          wrappedLabel += line.trim() + '\n';
          line = word + ' ';
        } else {
          line += word + ' ';
        }
      });
      wrappedLabel += line.trim();
      return wrappedLabel;
    }

    const wrappedRadarLabels = radar_labels.map(label => wrapLabel(label, 3));


    // -- Mini Graphs --

    var miniChart1 = new Chart(document.getElementById('miniChart1').getContext('2d'), {
      type: 'line',
      data: {
        labels: years,
        datasets: [
            {   
                label: 'Morts',
                data: morts,
                borderColor: 'rgb(255, 0, 0)',
                borderWidth: 3,
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0
            },
            {   
                label: 'Naissances',
                data: naissances,
                borderColor: 'rgb(0, 255, 0)',
                borderWidth: 3,
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0
            }
    ]
      },
      options: { 
        responsive: true, 
        plugins: { legend: { display: true } },
        scales: { y: { ticks: { color: 'grey' } }, x: { ticks: { color: 'grey' } } }
      }
    });

    var miniChart2 = new Chart(document.getElementById('miniChart2').getContext('2d'), {
      type: 'bar',
      data: {
        labels: ['Printemps', 'Ete', 'Automne', 'Hiver'],
        datasets: [{
          data: [spring_temp_moy, summer_temp_moy, autumn_temp_moy, winter_temp_moy],
          backgroundColor: [
            'rgba(0, 255, 21, 0.6)',
            'rgb(238, 255, 0)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)'
          ],
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          minBarLength: 5
        }]
      },
      options: { 
        responsive: true, 
        plugins: { legend: { display: false } },
        scales: { y: { ticks: { color: 'grey' } }, x: { ticks: { color: 'grey' } } }
      }
    });

    var miniChart3 = new Chart(document.getElementById('miniChart3').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: pie_labels,
        datasets: [{
            data: pie_data,
            backgroundColor: ['rgb(255, 4, 79)', 'rgba(75, 192, 192, 0.6)'],
            borderColor: ['rgb(123, 255, 0)', 'rgb(123, 255, 0)'],
            borderWidth: 1
        }]
    },
    options: { 
        responsive: true, 
        plugins: {
            legend: { display: true },
            datalabels: {
                formatter: function(value, context) {
                    const dataArr = context.chart.data.datasets[0].data;
                    const sum = dataArr.reduce((a, b) => a + b, 0);
                    const percentage = (value * 100 / sum).toFixed(2) + "%";
                    return percentage;
                },
                color: 'white', // Text color for the percentage labels
                font: { size: 13 } // Font size for the percentage labels
            }
        }
    },
    plugins: [ChartDataLabels] // Register the Data Labels plugin
});


    var miniChart4 = new Chart(document.getElementById('miniChart4').getContext('2d'), {
    type: 'radar',
    data: {
        labels: radar_labels, // e.g., ["Long Label 1", "Long Label 2", ...]
        datasets: [{
            label: 'Dataset',
            data: radar_data,   // your data array
            borderColor: 'rgb(60, 255, 0)',
            borderWidth: 2,
            fill: true,
            // Provide an array so each point gets its own color
            pointBackgroundColor: ['red', 'blue', 'green', 'orange', 'purple', 'yellow'],
            pointBorderColor: '#fff',
            pointRadius: 5,      // Increase point size
            pointHoverRadius: 7  // Increase size when hovered
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { 
                display: false  // disable the default legend
            }
        },
        scales: {
            r: {
                ticks: {
                    callback: function(value) {
                        return formatNumber(value);
                    }
                },
                pointLabels: {
                    display: false
                }
            }
        }
    }
});

// Function to generate custom legend HTML
function generateCustomLegend(chart) {
    var dataset = chart.data.datasets[0];
    var labels = chart.data.labels;
    var colors = dataset.pointBackgroundColor;
    // Set smaller font size and black text color
    var legendHTML = '<ul style="list-style: none; padding: 0; margin: 0; font-size: 8px; color: black;">';
    
    labels.forEach(function(label, index) {
        legendHTML += '<li style="display: flex; align-items: center; margin-bottom: 1px;">' +
                      '<span style="display: inline-block; width: 5px; height: 5px; background-color:' + colors[index] + '; margin-right: 2px;"></span>' +
                      label +
                      '</li>';
    });
    
    legendHTML += '</ul>';
    return legendHTML;
}



// After the chart is created, insert the custom legend into your HTML
document.getElementById('myCustomLegend').innerHTML = generateCustomLegend(miniChart4);

    // -- Info cards --

    var esperance_vie = chartData['sante']
        .map(item => parseFloat(item['esperance_vie']))
        .filter(value => value !== 0);

    var bonheur = chartData['bonheur']
        .map(item => parseFloat(item['score_bonheur']));

    var taux_transport = chartData['transport']
        .map(item => parseFloat(item['taux_acces_transport']));

    var taux_criminalite = parseFloat(chartData['crime'][0]['taux']);

    esperance_vie_moy = mean(esperance_vie);
    bonheur_moy = mean(bonheur);
    taux_transport_moy = mean(taux_transport)

    document.getElementById("esperance_vie").textContent = esperance_vie_moy.toFixed(2) + ' / 87';
    document.getElementById("bonheur").textContent = bonheur_moy.toFixed(2) + ' / 7.76';
    document.getElementById("taux_transport").textContent = taux_transport_moy.toFixed(2) + ' / 99.87';
    document.getElementById("taux_criminalite").textContent = taux_criminalite.toFixed(2) + ' / 44.7';

  </script>
</body>
</html>
