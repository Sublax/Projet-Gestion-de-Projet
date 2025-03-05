<?php
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Interactive Dashboard with Chart.js and PHP</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <style>
    /* Base reset */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      color: #333;
      padding: 20px;
    }
    /* Top-level controls */
    .controls-top {
      text-align: center;
      margin-bottom: 20px;
    }
    .controls-top select {
      padding: 10px 15px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin: 0 10px;
      transition: background 0.3s;
    }
    .controls-top select:hover {
      background: #3498db;
      color: #fff;
    }
    /* Grid layout for chart containers */
    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
      max-width: 1400px;
      margin: auto;
    }
    .container {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    .container:hover {
      transform: scale(0.98);
    }
    /* Overlay controls styling (graph menus) */
    .overlay-controls {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 10px;
      padding: 12px;
      background: rgba(255, 255, 255, 0.95);
      border-bottom: 1px solid #ddd;
    }
    .overlay-controls label {
      font-size: 14px;
      font-weight: 500;
      margin-right: 5px;
      white-space: nowrap;
    }
    .overlay-controls select,
    .overlay-controls button {
      padding: 8px 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background: #fff;
      transition: background 0.3s, border-color 0.3s;
    }
    .overlay-controls select:focus,
    .overlay-controls button:focus {
      outline: none;
      border-color: #3498db;
    }
    .overlay-controls select:hover,
    .overlay-controls button:hover {
  box-shadow: rgba(45, 35, 66, 0.4) 0 4px 8px, 
              rgba(45, 35, 66, 0.3) 0 7px 13px -3px, 
              #3c4fe0 0 -3px 0 inset;
  transform: translateY(-2px);
}
    .overlay-controls button {
  align-items: center;
  appearance: none;
  background-image: radial-gradient(100% 100% at 100% 0, #5adaff 0, #5468ff 100%);
  border: 0;
  border-radius: 6px;
  box-shadow: rgba(45, 35, 66, 0.4) 0 2px 4px, rgba(45, 35, 66, 0.3) 0 7px 13px -3px, rgba(58, 65, 111, 0.5) 0 -3px 0 inset;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;
  display: inline-flex;
  font-family: "JetBrains Mono", monospace;
  height: 40px;
  justify-content: center;
  line-height: 1;
  padding: 0 16px;
  text-align: center;
  text-decoration: none;
  transition: box-shadow 0.15s, transform 0.15s;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  white-space: nowrap;
  will-change: box-shadow, transform;
  font-size: 14px;
  margin: 5px 0;
}

.overlay-controls button:active {
  box-shadow: #3c4fe0 0 3px 7px inset;
  transform: translateY(2px);
}

.overlay-controls button:focus {
  box-shadow: #3c4fe0 0 0 0 1.5px inset, 
              rgba(45, 35, 66, 0.4) 0 2px 4px, 
              rgba(45, 35, 66, 0.3) 0 7px 13px -3px, 
              #3c4fe0 0 -3px 0 inset;
}
    /* Fullscreen button: larger, distinct style */
    .fullscreen-btn {
      flex: 1;
      max-width: 140px;
      background: #3498db;
      color: #fff;
      border: none;
    }
    .fullscreen-btn:hover {
      background: #2980b9;
    }
    /* Canvas styling */
    canvas {
      display: block;
      width: 100% !important;
      height: 400px;
      border-bottom-left-radius: 12px;
      border-bottom-right-radius: 12px;
    }
  </style>
</head>
<body>
  <div class="controls-top">
    <label for="sectionSelect">Select Section:</label>
    <select id="sectionSelect">
      <option value="1">Trends/Comparing Values</option>
      <option value="2">Value Scales</option>
      <option value="3">Links Between Variables</option>
      <option value="4">Sector Analysis</option>
    </select>
  </div>
  
  <div class="grid-container">
    <!-- Section 1: Trends/Comparing Values (6 graphs) -->

    <!-- Barplot -->
    <div class="container" data-section="1">
      <div class="overlay-controls">
        <!-- Domain selector -->
        <label for="bar_domainSelect">Select Domain:</label>
        <select id="bar_domainSelect">
            <option value="">Select Domain</option>
        </select>
        <!-- Variable selector -->
        <label for="bar_variableSelect">Variable:</label>
        <select id="bar_variableSelect" multiple>
          <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
          <option value="costhealthydiet">cost of a healthy diet</option>
        </select>   
        <!-- Hidden select that will appear when clicking the button -->
        <select id="bar_newVariableSelect" style="display: none;">
            <option value="">Select a variable</option>
            <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
            <option value="costhealthydiet">cost of a healthy diet</option>
        </select>
        <!-- Add variable button -->
        <button id="bar_addVarButton">Add a new variable</button>
        <!-- Remove variable button -->
        <button id="bar_removeVarButton">Remove a variable</button>
        <!-- Hidden select that will appear when clicking the remove button -->
        <select id="bar_removeVariableSelect" style="display: none;">
            <option value="">Select a variable to remove</option>
            <!-- Options will be populated dynamically -->
        </select>
        <!-- Fullscreen button -->
        <button class="fullscreen-btn">Full Screen</button>  
      </div>
      <canvas id="barChart"></canvas>
    </div>

    <!-- Lineplot -->
    <div class="container" data-section="1">
      <div class="overlay-controls">
        <!-- Domain selector -->
        <label for="line_domainSelect">Select Domain:</label>
        <select id="line_domainSelect">
            <option value="">Select Domain</option>
        </select>
        <!-- Variable selector -->
        <label for="lineplotYVars">Variable:</label>
        <select id="lineplotYVars" multiple>
          <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
          <option value="costhealthydiet">cost of a healthy diet</option>
        </select>   
        <!-- Hidden select that will appear when clicking the button -->
        <select id="line_bar_newVariableSelect" style="display: none;">
            <option value="">Select a variable</option>
            <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
            <option value="costhealthydiet">cost of a healthy diet</option>
        </select>
        <!-- Add variable button -->
        <button id="line_addVarButton">Add a new variable</button>
        <!-- Remove variable button -->
        <button id="line_removeVarButton">Remove a variable</button>
        <!-- Hidden select that will appear when clicking the remove button -->
        <select id="line_removeVariableSelect" style="display: none;">
            <option value="">Select a variable to remove</option>
            <!-- Options will be populated dynamically -->
        </select>
        <!-- Fullscreen button -->
        <button class="fullscreen-btn">Full Screen</button>  
      </div>
      <canvas id="lineplotChart"></canvas>
    </div>

    <!-- Pie Chart -->
    <div class="container" data-section="1">
      <div class="overlay-controls">
        <!-- Domain selector -->
        <label for="pie_domainSelect">Select Domain:</label>
        <select id="pie_domainSelect">
            <option value="">Select Domain</option>
        </select>
        <!-- Variable selector -->
        <label for="piechartYVars">Variable:</label>
        <select id="piechartYVars" multiple>
          <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
          <option value="costhealthydiet">cost of a healthy diet</option>
        </select>   
        <!-- Hidden select that will appear when clicking the button -->
        <select id="pie_newVariableSelect" style="display: none;">
            <option value="">Select a variable</option>
            <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
            <option value="costhealthydiet">cost of a healthy diet</option>
        </select>
        <!-- Add variable button -->
        <button id="pie_addVarButton">Add a new variable</button>
        <!-- Remove variable button -->
        <button id="pie_removeVarButton">Remove a variable</button>
        <!-- Hidden select that will appear when clicking the remove button -->
        <select id="pie_removeVariableSelect" style="display: none;">
            <option value="">Select a variable to remove</option>
            <!-- Options will be populated dynamically -->
        </select>
        <!-- Fullscreen button -->
        <button class="fullscreen-btn">Full Screen</button>  
      </div>
      <canvas id="pieChart"></canvas>
    </div>

    <!-- Polar Chart -->
    <div class="container" data-section="1">
      <div class="overlay-controls">
        <!-- Domain selector -->
        <label for="polar_domainSelect">Select Domain:</label>
        <select id="polar_domainSelect">
            <option value="">Select Domain</option>
        </select>
        <!-- Variable selector -->
        <label for="polarchartYVars">Variable:</label>
        <select id="polarchartYVars" multiple>
          <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
          <option value="costhealthydiet">cost of a healthy diet</option>
        </select>   
        <!-- Hidden select that will appear when clicking the button -->
        <select id="polar_newVariableSelect" style="display: none;">
            <option value="">Select a variable</option>
            <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
            <option value="costhealthydiet">cost of a healthy diet</option>
        </select>
        <!-- Add variable button -->
        <button id="polar_addVarButton">Add a new variable</button>
        <!-- Remove variable button -->
        <button id="polar_removeVarButton">Remove a variable</button>
        <!-- Hidden select that will appear when clicking the remove button -->
        <select id="polar_removeVariableSelect" style="display: none;">
            <option value="">Select a variable to remove</option>
            <!-- Options will be populated dynamically -->
        </select>
        <!-- Fullscreen button -->
        <button class="fullscreen-btn">Full Screen</button>  
      </div>
      <canvas id="polarChart"></canvas>
    </div>

    <!-- Donut Chart -->
    <div class="container" data-section="1">
      <div class="overlay-controls">
        <!-- Domain selector -->
        <label for="donut_domainSelect">Select Domain:</label>
        <select id="donut_domainSelect">
            <option value="">Select Domain</option>
        </select>
        <!-- Variable selector -->
        <label for="donutchartYVars">Variable:</label>
        <select id="donutchartYVars" multiple>
          <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
          <option value="costhealthydiet">cost of a healthy diet</option>
        </select>   
        <!-- Hidden select that will appear when clicking the button -->
        <select id="donut_newVariableSelect" style="display: none;">
            <option value="">Select a variable</option>
            <option value="cleanfuelandcookingequipment">clean fuel and cooking equipment</option>
            <option value="costhealthydiet">cost of a healthy diet</option>
        </select>
        <!-- Add variable button -->
        <button id="donut_addVarButton">Add a new variable</button>
        <!-- Remove variable button -->
        <button id="donut_removeVarButton">Remove a variable</button>
        <!-- Hidden select that will appear when clicking the remove button -->
        <select id="donut_removeVariableSelect" style="display: none;">
            <option value="">Select a variable to remove</option>
            <!-- Options will be populated dynamically -->
        </select>
        <!-- Fullscreen button -->
        <button class="fullscreen-btn">Full Screen</button>  
      </div>
      <canvas id="donutChart"></canvas>
    </div>
    
    <!-- Section 2: Value Scales (Boxplot) -->
    <!-- Histogram Chart Section -->
<div class="container" data-section="2">
  <div class="overlay-controls">
    <label for="boxplot_domainSelect">Select Domain:</label>
    <select id="boxplot_domainSelect">
      <option value="">Select Domain</option>
      <!-- Options populated dynamically -->
    </select>
    <label for="boxplotYVars">Variable:</label>
    <select id="boxplotYVars" multiple>
      <option value="cleanfuelandcookingequipment">Clean Fuel & Cooking Equipment</option>
      <option value="costhealthydiet">Cost of a Healthy Diet</option>
    </select>
    <select id="boxplot_newVariableSelect" style="display: none;">
      <option value="">Select a variable</option>
      <option value="cleanfuelandcookingequipment">Clean Fuel & Cooking Equipment</option>
      <option value="costhealthydiet">Cost of a Healthy Diet</option>
    </select>
    <button id="boxplot_addVarButton">Add Variable</button>
    <button id="boxplot_removeVarButton">Remove Variable</button>
    <select id="boxplot_removeVariableSelect" style="display: none;">
      <option value="">Select variable to remove</option>
    </select>
    <button class="fullscreen-btn">Full Screen</button>
  </div>
  <canvas id="boxplotChart"></canvas>
</div>

    
    <!-- Section 3: Links Between Variables (2 graphs) -->
    <!-- Scatter Chart Section -->
<div class="container" data-section="3">
  <div class="overlay-controls">
    <label for="scatter_xDomainSelect">Select X Domain:</label>
    <select id="scatter_xDomainSelect">
      <option value="">Select Domain</option>
    </select>
    <label for="scatter_xVariableSelect">X Variable:</label>
    <select id="scatter_xVariableSelect" multiple></select>
    
    <label for="scatter_yDomainSelect">Select Y Domain:</label>
    <select id="scatter_yDomainSelect">
      <option value="">Select Domain</option>
    </select>
    <label for="scatter_yVariableSelect">Y Variable:</label>
    <select id="scatter_yVariableSelect" multiple></select>
    
    <button class="fullscreen-btn">Full Screen</button>
  </div>
  <canvas id="scatterChart"></canvas>
</div>

    
    <!-- Section 4: Sector Analysis (Radar Plot) -->
    <!-- Radar chart -->
    <div class="container" data-section="4">
  <div class="overlay-controls">
    <!-- Domain selector for Radar Chart -->
    <label for="radar_domainSelect">Select Domain:</label>
    <select id="radar_domainSelect">
      <option value="">Select Domain</option>
      <!-- Options will be populated dynamically -->
    </select>
    <!-- Variable selector for Radar Chart -->
    <label for="radar_variableSelect">Variable:</label>
    <select id="radar_variableSelect" multiple>
      <!-- Options will be populated dynamically -->
    </select>
    <!-- Hidden select for adding a new variable -->
    <select id="radar_newVariableSelect" style="display: none;">
      <option value="">Select a variable</option>
      <!-- Options will be populated dynamically -->
    </select>
    <!-- Add and Remove variable buttons -->
    <button id="radar_addVarButton">Add a new variable</button>
    <button id="radar_removeVarButton">Remove a variable</button>
    <!-- Hidden select for removing a variable -->
    <select id="radar_removeVariableSelect" style="display: none;">
      <option value="">Select a variable to remove</option>
      <!-- Options will be populated dynamically -->
    </select>
    <!-- Fullscreen button -->
    <button class="fullscreen-btn">Full Screen</button>
  </div>
  <canvas id="radarChart"></canvas>
</div>

  </div>

  <script>

    // ===================== Window functionalities =====================

    // Full screen functionality for each container's button
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
        if (!document.fullscreenElement) {
            // Remove any inline sizing on canvas elements.
            document.querySelectorAll('canvas').forEach(function(canvas) {
                canvas.style.width = '';
                canvas.style.height = '';
            });

            // Resize all Chart.js instances.
            var chartInstances = [
                barplotChartInstance, lineplotChartInstance, slopeChartInstance,
                pieChartInstance, polarChartInstance, donutChartInstance,
                boxplotChartInstance, linksScatterChartInstance, heatmapChartInstance,
                radarChartInstance
            ];
            chartInstances.forEach(function(chart) {
                if (chart) {
                    chart.resize();
                }
            });
        }
    });


    // Remove the no-hover-scale class when the mouse leaves the container
    document.querySelectorAll('.container').forEach(function(container) {
        container.addEventListener('mouseleave', function() {
            container.classList.remove('no-hover-scale');
        });
    });

    // Section selection functionality
    document.getElementById('sectionSelect').addEventListener('change', function() {
      var selectedSection = this.value;
      document.querySelectorAll('.grid-container .container').forEach(function(container) {
        container.style.display = (container.getAttribute('data-section') === selectedSection) ? 'block' : 'none';
      });
    });
    // Show Section 1 by default
    document.getElementById('sectionSelect').value = "1";
    document.getElementById('sectionSelect').dispatchEvent(new Event('change'));

    // ===================== Utility =====================
    function randomRGBA(alpha) {
      const r = Math.floor(Math.random() * 256);
      const g = Math.floor(Math.random() * 256);
      const b = Math.floor(Math.random() * 256);
      return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    // ===================== Reusable Chart Initializer =====================
    function initializeDynamicChart(config) {
    // config properties:
    // chartType, canvasId, domainSelectId, variableSelectId, newVariableSelectId,
    // addVarButtonId, removeVarButtonId, removeSelectId, defaultDomain, chartData,
    // (for pie charts only) labelField (optional)

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

    // Update variable selectors for a given domain
    function updateVariableSelectors(domain) {
      variableSelect.innerHTML = "";
      newVariableSelect.innerHTML = '<option value="">Select a variable</option>';
      if (chartData[domain] && chartData[domain].length > 0) {
        var keys = Object.keys(chartData[domain][0]);
        keys.forEach(function(key) {
          // Exclude fields 'annee' and 'Year'
          if (key === 'annee' || key === 'Year') return;
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

    // Helper: Get labels and initial data based on chart type
    function getChartData(domain) {
      if (config.chartType === 'pie') {
        // For a pie chart, use the default variable for the data values.
        // The labels come from a dedicated field. If config.labelField is defined,
        // use that; otherwise try to pick the first field that is not 'annee', 'Year', or the chosen variable.
        var row = chartData[domain][0];
        var defaultVar = variableSelect.options[0] ? variableSelect.options[0].value : "";
        var labelField = config.labelField;
        if (!labelField) {
          var candidate = Object.keys(row).find(function(key) {
            return key !== 'annee' && key !== 'Year' && key !== defaultVar;
          });
          labelField = candidate || defaultVar;
        }
        var labels = chartData[domain].map(function(item) {
          return item[labelField];
        });
        var dataValues = chartData[domain].map(function(item) {
          return item[defaultVar];
        });
        return { labels: labels, data: dataValues, defaultVar: defaultVar };
      } else {
        // For bar/line charts, we assume the labels are years.
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
    }

    // Initialize chart instance
    var ctx = document.getElementById(config.canvasId).getContext('2d');
    var initValues = getChartData(selectedDomain);
    var chartInstance = new Chart(ctx, {
      type: config.chartType,
      data: {
        labels: initValues.labels,
        datasets: [{
          label: initValues.defaultVar,
          data: initValues.data,
          backgroundColor: (config.chartType === 'pie') ?
            initValues.data.map(() => randomRGBA(0.6)) :
            'rgba(75, 192, 192, 0.2)',
          borderColor: (config.chartType === 'pie') ?
            initValues.data.map(() => randomRGBA(1)) :
            'rgba(75, 192, 192, 1)',
          borderWidth: 1,
          fill: (config.chartType === 'line') ? false : false
        }]
      },
      options: {
        scales: (config.chartType === 'pie') ? {} : { y: { beginAtZero: true } }
      }
    });

    // Domain change event
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
        backgroundColor: (config.chartType === 'pie') ?
          newValues.data.map(() => randomRGBA(0.6)) :
          'rgba(75, 192, 192, 0.2)',
        borderColor: (config.chartType === 'pie') ?
          newValues.data.map(() => randomRGBA(1)) :
          'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        fill: (config.chartType === 'line') ? false : false
      }];
      chartInstance.update();
    });

    // Variable selector change event
    variableSelect.addEventListener("change", function() {
      var selectedVar = this.value;
      var newData = chartData[selectedDomain].map(function(item) {
        return item[selectedVar];
      });
      if(config.chartType === 'pie'){
        chartInstance.data.datasets[0].backgroundColor = newData.map(() => randomRGBA(0.6));
        chartInstance.data.datasets[0].borderColor = newData.map(() => randomRGBA(1));
      }
      chartInstance.data.datasets[0].data = newData;
      chartInstance.data.datasets[0].label = selectedVar;
      chartInstance.update();
    });

    // Add new variable event
    addVarButton.addEventListener('click', function() {
      newVariableSelect.style.display = (newVariableSelect.style.display === 'none' || newVariableSelect.style.display === '') 
                                          ? 'block' : 'none';
    });

    newVariableSelect.addEventListener('change', function() {
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
          backgroundColor: (config.chartType === 'pie') ?
            newData.map(() => randomRGBA(0.6)) :
            randomRGBA(0.3),
          borderColor: (config.chartType === 'pie') ?
            newData.map(() => randomRGBA(1)) :
            randomRGBA(1),
          borderWidth: 1,
          fill: (config.chartType === 'line') ? false : false
        };
        chartInstance.data.datasets.push(newDataset);
        chartInstance.update();
      } else {
        alert("This variable is already added.");
      }
      newVariableSelect.style.display = 'none';
      newVariableSelect.selectedIndex = 0;
    });

    // Remove variable event
    removeVarButton.addEventListener('click', function() {
      removeSelect.innerHTML = '<option value="">Select a variable to remove</option>';
      chartInstance.data.datasets.forEach(function(dataset) {
        var option = document.createElement('option');
        option.value = dataset.label;
        option.text = dataset.label;
        removeSelect.appendChild(option);
      });
      removeSelect.style.display = 'block';
    });

    removeSelect.addEventListener('change', function() {
      var selectedVar = this.value;
      if (!selectedVar) return;
      chartInstance.data.datasets = chartInstance.data.datasets.filter(function(dataset) {
        return dataset.label !== selectedVar;
      });
      chartInstance.update();
      removeSelect.style.display = 'none';
      removeSelect.selectedIndex = 0;
    });

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
  
  // Helper function: aggregates the data for a given domain by summing up each numeric field,
  // excluding fields listed in config.excludeFields.
  function aggregateDataForDomain(domain) {
    var dataArray = chartData[domain]; // Array of data rows for this domain
    var aggregation = {};  // Will store { fieldName: sum }
    dataArray.forEach(function(item) {
      for (var key in item) {
        // Skip keys that we want to exclude (like the year)
        if (config.excludeFields && config.excludeFields.indexOf(key) !== -1) continue;
        // Convert value to a number; if it's not numeric, skip it.
        var value = parseFloat(item[key]);
        if (isNaN(value)) continue;
        if (!aggregation[key]) {
          aggregation[key] = value;
        } else {
          aggregation[key] += value;
        }
      }
    });
    var labels = Object.keys(aggregation);
    var dataValues = labels.map(function(k) { return aggregation[k]; });
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
        backgroundColor: agg.labels.map(() => randomRGBA(0.6)),
        borderColor: agg.labels.map(() => randomRGBA(1)),
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

/**
 * Initializes a boxplot distribution chart with dynamic controls.
 *
 * @param {Object} config - Configuration object with properties:
 *   - canvasId: ID of the <canvas> element where the chart is drawn.
 *   - domainSelectId: ID of the <select> element for domain selection.
 *   - variableSelectId: ID of the <select> element for variable selection.
 *   - newVariableSelectId: ID of the hidden <select> element for adding a new variable.
 *   - addVarButtonId: ID of the button to show the new variable selector.
 *   - removeVarButtonId: ID of the button to trigger variable removal.
 *   - removeSelectId: ID of the hidden <select> element used for removing a variable.
 *   - defaultDomain: The default domain to display.
 *   - chartData: The complete data object (keys are domains, values are arrays of data rows).
 */
function initializeBoxplotChart(config) {
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
  
  // Update the variable selector based on the selected domain.
  function updateVariableSelector(domain) {
    variableSelect.innerHTML = "";
    newVariableSelect.innerHTML = '<option value="">Select a variable</option>';
    if (chartData[domain] && chartData[domain].length > 0) {
      var keys = Object.keys(chartData[domain][0]);
      keys.forEach(function(key) {
        // Exclude fields that represent the year
        if (key === "annee" || key === "Year") return;
        var option = document.createElement("option");
        option.value = key;
        option.text = key;
        variableSelect.appendChild(option);
        
        // Also populate the hidden new variable selector
        var option2 = document.createElement("option");
        option2.value = key;
        option2.text = key;
        newVariableSelect.appendChild(option2);
      });
    }
    variableSelect.selectedIndex = 0;
    newVariableSelect.selectedIndex = 0;
  }
  updateVariableSelector(selectedDomain);
  
  // Helper: Compute percentile for an array of numbers.
  function getPercentile(arr, p) {
    var index = (p / 100) * (arr.length - 1);
    if (Math.floor(index) === index) {
      return arr[index];
    } else {
      var lower = arr[Math.floor(index)];
      var upper = arr[Math.ceil(index)];
      return lower + (upper - lower) * (index - Math.floor(index));
    }
  }
  
  // Helper: Compute boxplot statistics for an array of numbers.
  function computeBoxplotStats(values) {
    values.sort(function(a, b) { return a - b; });
    var min = values[0];
    var max = values[values.length - 1];
    var median = getPercentile(values, 50);
    var q1 = getPercentile(values, 25);
    var q3 = getPercentile(values, 75);
    return { min: min, q1: q1, median: median, q3: q3, max: max };
  }
  
  // Helper: For a given domain and variable, group data by year and compute boxplot stats.
  function getBoxplotData(domain, variable) {
    var rows = chartData[domain];
    var yearSet = new Set();
    rows.forEach(function(item) {
      var year = item.annee || item.Year;
      if (year) yearSet.add(year);
    });
    var years = Array.from(yearSet).sort();
    var stats = years.map(function(year) {
      var values = rows.filter(function(item) {
        return (item.annee || item.Year) == year && item[variable] != null;
      }).map(function(item) {
        return parseFloat(item[variable]);
      }).filter(function(num) { return !isNaN(num); });
      if (values.length > 0) {
        return computeBoxplotStats(values);
      } else {
        return { min: 0, q1: 0, median: 0, q3: 0, max: 0 };
      }
    });
    return { labels: years, data: stats, defaultVar: variable };
  }
  
  // Get initial chart data using the default variable from variableSelect.
  var defaultVar = variableSelect.options[0] ? variableSelect.options[0].value : "";
  var initValues = getBoxplotData(selectedDomain, defaultVar);
  
  // Create the boxplot chart (requires a boxplot plugin)
  var ctx = document.getElementById(config.canvasId).getContext('2d');
  var chartInstance = new Chart(ctx, {
    type: 'boxplot',
    data: {
      labels: initValues.labels,
      datasets: [{
        label: defaultVar,
        data: initValues.data,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
  
  // Domain change event: update the variable selector and reinitialize chart data.
  domainSelect.addEventListener("change", function() {
    selectedDomain = this.value;
    updateVariableSelector(selectedDomain);
    var newDefaultVar = variableSelect.options[0] ? variableSelect.options[0].value : "";
    var newValues = getBoxplotData(selectedDomain, newDefaultVar);
    chartInstance.data.labels = newValues.labels;
    chartInstance.data.datasets = [{
      label: newDefaultVar,
      data: newValues.data,
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1
    }];
    chartInstance.update();
  });
  
  // Variable selector change event: update the current dataset.
  variableSelect.addEventListener("change", function() {
    var selectedVar = this.value;
    var newValues = getBoxplotData(selectedDomain, selectedVar);
    chartInstance.data.labels = newValues.labels;
    chartInstance.data.datasets[0].data = newValues.data;
    chartInstance.data.datasets[0].label = selectedVar;
    chartInstance.update();
  });
  
  // Add new variable event: show/hide new variable selector and add dataset if selected.
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
      var newData = getBoxplotData(selectedDomain, selectedVariable);
      var newDataset = {
        label: selectedVariable,
        data: newData.data,
        backgroundColor: randomRGBA(0.3),
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
  
  // Remove variable event: allow removal of datasets.
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
    // Filter out undefined/null values and convert to strings
    return years.filter(Boolean).map(String);
  }

  // Helper: Get intersection of two arrays.
  function getCommonYears(arr1, arr2) {
    return arr1.filter(year => arr2.includes(year)).sort();
  }

  // Helper: Build scatter data points from two domains.
  // For each common year, find the corresponding row (assuming one per year) in each domain.
  function getScatterData(xDomain, yDomain, xVar, yVar) {
    var xYears = getYears(xDomain);
    var yYears = getYears(yDomain);
    var commonYears = getCommonYears(xYears, yYears);
    // For each common year, find the row in each domain and extract the numeric value.
    var dataPoints = commonYears.map(function(year) {
      var xRow = chartData[xDomain].find(item => (item.annee || item.Year) == year);
      var yRow = chartData[yDomain].find(item => (item.annee || item.Year) == year);
      // Parse float values; if missing, skip the point.
      var xVal = xRow ? parseFloat(xRow[xVar]) : null;
      var yVal = yRow ? parseFloat(yRow[yVar]) : null;
      if (xVal == null || isNaN(xVal) || yVal == null || isNaN(yVal)) {
        return null;
      }
      return { x: xVal, y: yVal };
    });
    // Filter out any null values.
    return dataPoints.filter(point => point !== null);
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
      // For scatter charts, labels are not strictly used; data points come from datasets.
      datasets: [{
        label: defaultXVar + " vs " + defaultYVar,
        data: initialData,
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
          title: {
            display: true,
            text: defaultXVar
          }
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: defaultYVar
          }
        }
      }
    }
  });

  // When the x domain changes.
  xDomainSelect.addEventListener("change", function() {
    xSelectedDomain = this.value;
    updateVariableSelector(xSelectedDomain, xVariableSelect);
    updateScatterChart();
  });

  // When the y domain changes.
  yDomainSelect.addEventListener("change", function() {
    ySelectedDomain = this.value;
    updateVariableSelector(ySelectedDomain, yVariableSelect);
    updateScatterChart();
  });

  // When the x variable or y variable changes.
  xVariableSelect.addEventListener("change", updateScatterChart);
  yVariableSelect.addEventListener("change", updateScatterChart);

  function updateScatterChart() {
    var selectedXVar = xVariableSelect.value;
    var selectedYVar = yVariableSelect.value;
    var newData = getScatterData(xSelectedDomain, ySelectedDomain, selectedXVar, selectedYVar);
    chartInstance.data.datasets[0].data = newData;
    chartInstance.data.datasets[0].label = selectedXVar + " vs " + selectedYVar;
    // Update axis titles.
    chartInstance.options.scales.x.title.text = selectedXVar;
    chartInstance.options.scales.y.title.text = selectedYVar;
    chartInstance.update();
  }

  // Optionally, you could add additional functionality for adding or removing extra datasets.
  // For simplicity, this example uses one dataset that is updated based on the selected domains and variables.

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



    // ===================== Initialize Charts =====================

    // Convert PHP data to a JavaScript variable
    var chartData = <?php echo json_encode($data); ?>;
    console.log(chartData);

    // Bar Chart
    var barChart = initializeDynamicChart({
      chartType: 'bar',
      canvasId: 'barChart',
      domainSelectId: 'bar_domainSelect',
      variableSelectId: 'bar_variableSelect',
      newVariableSelectId: 'bar_newVariableSelect',
      addVarButtonId: 'bar_addVarButton',
      removeVarButtonId: 'bar_removeVarButton',
      removeSelectId: 'bar_removeVariableSelect',
      defaultDomain: 'agroalimentaire',
      chartData: chartData
    });

    // Line Chart
    var lineChart = initializeDynamicChart({
      chartType: 'line',
      canvasId: 'lineplotChart',
      domainSelectId: 'line_domainSelect',
      variableSelectId: 'lineplotYVars',
      newVariableSelectId: 'line_bar_newVariableSelect',
      addVarButtonId: 'line_addVarButton',
      removeVarButtonId: 'line_removeVarButton',
      removeSelectId: 'line_removeVariableSelect',
      defaultDomain: 'agroalimentaire',
      chartData: chartData
    });

    // Pie Chart
    var pieChart = initializePieDistributionChart({
      canvasId: 'pieChart',         // The canvas element for the pie chart
      graphType: 'pie',             // The type of chart (pie, polarArea, doughnut)
      domainSelectId: 'pie_domainSelect',   // The domain selector element
      defaultDomain: 'travail',             // The default domain you want to show (e.g. "travail")
      chartData: chartData,                 // Your complete data object
      excludeFields: ['annee', 'Year']      // Fields to exclude from aggregation
    });

    // Polar Chart
    var polarChart = initializePieDistributionChart({
      canvasId: 'polarChart',         // The canvas element for the pie chart
      graphType: 'polarArea',             // The type of chart (pie, polarArea, doughnut)
      domainSelectId: 'pie_domainSelect',   // The domain selector element
      defaultDomain: 'meteo',             // The default domain you want to show (e.g. "travail")
      chartData: chartData,                 // Your complete data object
      excludeFields: ['annee', 'Year']      // Fields to exclude from aggregation
    });

    // Donut Chart
    var donutChart = initializePieDistributionChart({
      canvasId: 'donutChart',         // The canvas element for the pie chart
      graphType: 'doughnut',             // The type of chart (pie, polarArea, doughnut)
      domainSelectId: 'pie_domainSelect',   // The domain selector element
      defaultDomain: 'religion',             // The default domain you want to show (e.g. "travail")
      chartData: chartData,                 // Your complete data object
      excludeFields: ['annee', 'Year']      // Fields to exclude from aggregation
    });

    var radarChart = initializeRadarChart({
      canvasId: 'radarChart',
      domainSelectId: 'radar_domainSelect',
      variableSelectId: 'radar_variableSelect',
      newVariableSelectId: 'radar_newVariableSelect',
      addVarButtonId: 'radar_addVarButton',
      removeVarButtonId: 'radar_removeVarButton',
      removeSelectId: 'radar_removeVariableSelect',
      defaultDomain: 'meteo',  
      chartData: chartData     
    });

    var scatterChart = initializeDualDomainScatterChart({
      canvasId: 'scatterChart',
      xDomainSelectId: 'scatter_xDomainSelect',
      xVariableSelectId: 'scatter_xVariableSelect',
      yDomainSelectId: 'scatter_yDomainSelect',
      yVariableSelectId: 'scatter_yVariableSelect',
      defaultXDomain: 'agroalimentaire',
      defaultYDomain: 'travail',
      chartData: chartData  // Your data object from PHP
    });

    var histogramChart = initializeHistogramChart({
      canvasId: 'boxplotChart',
      domainSelectId: 'boxplot_domainSelect',
      variableSelectId: 'boxplotYVars',
      newVariableSelectId: 'boxplot_newVariableSelect',
      addVarButtonId: 'boxplot_addVarButton',
      removeVarButtonId: 'boxplot_removeVarButton',
      removeSelectId: 'boxplot_removeVariableSelect',
      defaultDomain: 'travail', // for example
      chartData: chartData,     // your data object from PHP
      numBins: 10         // Optional: set the number of bins (default is 10)
    });



    </script>