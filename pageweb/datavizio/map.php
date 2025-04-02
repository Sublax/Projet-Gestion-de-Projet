
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Suitability Map</title>
    
<style>
/* General Page Styling */
body {
    background: linear-gradient(to bottom, #ffffff, #e6f7ff);
    font-family: 'Arial', sans-serif;
    text-align: center;
    color: #333;
    margin: 0;
    padding-top: 100px;
    position: relative; /* Needed for absolute positioning of child elements */
}

/* Page Title */
h1 {
    font-size: 28px;
    margin-top: 20px;
    font-weight: bold;
    color: #003366;
    margin-bottom: 10px;
}

/* Updated Map Container - Full Width */
#map-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100vw; /* Takes full screen width */
    height: 90vh; /* Increased height */
    padding: 10px;
    overflow: hidden; /* Prevents extra scrollbars */
}

/* Ensure the Plotly Graph Expands Properly */
.js-plotly-plot {
    width: 95vw !important;  /* Force full width */
    height: 85vh !important; /* Force full height */
    border-radius: 10px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    background: white;
    padding: 10px;
}

/* Top Countries List Positioned at Bottom Left */
#top-countries {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.9);
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
    max-height: 40vh;
    overflow-y: auto;
    text-align: left;
    z-index: 1000;
}
#top-countries h2 {
    margin: 0 0 5px 0;
    font-size: 18px;
}
#top-countries ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
#top-countries li {
    margin-bottom: 4px;
    font-size: 14px;
}

/* Button Container */
.button-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 10px;
}

/* Stylish Zoom Buttons */
.map-button {
    background: #0056b3;
    color: white;
    border: none;
    padding: 12px 18px;
    margin: 5px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.map-button:hover {
    background: #003366;
    transform: scale(1.05);
}

.footer {
    text-align: center;
    font-size: 14px;
    color: #666;
    margin-top: 20px;
}

/* Increase the modebar size */
.js-plotly-plot .modebar {
    background: rgba(255, 255, 255, 0.9) !important;  /* White background */
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
    transform: scale(1.2); /* Makes the entire bar bigger */
}

/* Make modebar buttons larger */
.js-plotly-plot .modebar-btn {
    font-size: 20px !important; /* Increase icon size */
    padding: 10px !important;  /* Increase button padding */
    width: 50px !important; /* Make buttons wider */
    height: 50px !important; /* Make buttons taller */
}

/* Change button hover effect */
.js-plotly-plot .modebar-btn:hover {
    background: rgba(0, 102, 255, 0.8) !important; /* Blue hover effect */
    color: white !important;
    transform: scale(1.1); /* Slight hover effect */
}

/* Ensure modebar is always visible */
.js-plotly-plot .modebar {
    opacity: 1 !important;
}

/* Smooth hover effect for the map */
.js-plotly-plot path {
    transition: filter 0.2s ease-in-out, transform 0.1s ease-in-out;
}

/* Apply glow effect when hovering */
.js-plotly-plot path:hover {
    filter: drop-shadow(0px 0px 5px rgba(0, 102, 255, 0.6));
    transform: scale(1.01); /* Slight zoom effect */
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

</style>
  
</head>
<body>
    <?php include('../navbar.php'); ?>

    <h1>🌍 Trouve ton pays ✈️</h1>
    <p>Cliquez sur un pays pour explorer ses données</p>
    <div id="map-container"><div>                        <script type="text/javascript">window.PlotlyConfig = {MathJaxConfig: 'local'};</script>
        <script charset="utf-8" src="https://cdn.plot.ly/plotly-3.0.0.min.js"></script>                <div id="bf8eab3a-d296-4f6c-b019-0979f9d74c85" class="plotly-graph-div" style="height:100%; width:100%;"></div>            <script type="text/javascript">                window.PLOTLYENV=window.PLOTLYENV || {};                                if (document.getElementById("bf8eab3a-d296-4f6c-b019-0979f9d74c85")) {                    Plotly.newPlot(                        "bf8eab3a-d296-4f6c-b019-0979f9d74c85",                        [{"coloraxis":"coloraxis","customdata":[["Afghanistan"],["Albania"],["Algeria"],["American Samoa"],["Andorra"],["Angola"],["Anguilla"],["Antarctica"],["Antigua and Barbuda"],["Argentina"],["Armenia"],["Aruba"],["Australia"],["Austria"],["Azerbaijan"],["Bahamas"],["Bahrain"],["Bangladesh"],["Barbados"],["Belarus"],["Belgium"],["Belize"],["Benin"],["Bermuda"],["Bhutan"],["Bolivia"],["Bonaire, Saint Eustatius and Saba"],["Bosnia and Herzegovina"],["Botswana"],["Bouvet Island"],["Brazil"],["British Indian Ocean Territory"],["Brunei Darussalam"],["Bulgaria"],["Burkina Faso"],["Burundi"],["Cambodia"],["Cameroon"],["Canada"],["Cabo Verde"],["Cayman Islands"],["Central African Republic"],["Chad"],["Chile"],["China"],["Christmas Island"],["Cocos (Keeling) Islands"],["Colombia"],["Comoros"],["Congo Republic"],["DR Congo"],["Cook Islands"],["Costa Rica"],["Croatia"],["Cuba"],["Curacao"],["Cyprus"],["Czechia"],["Cote d'Ivoire"],["Denmark"],["Djibouti"],["Dominica"],["Dominican Republic"],["Ecuador"],["Egypt"],["El Salvador"],["Equatorial Guinea"],["Eritrea"],["Estonia"],["Ethiopia"],["Falkland Islands"],["Faroe Islands"],["Fiji"],["Finland"],["France"],["French Guiana"],["French Polynesia"],["French Southern Territories"],["Gabon"],["Gambia"],["Georgia"],["Germany"],["Ghana"],["Gibraltar"],["Greece"],["Greenland"],["Grenada"],["Guadeloupe"],["Guam"],["Guatemala"],["Guernsey"],["Guinea"],["Guinea-Bissau"],["Guyana"],["Haiti"],["Heard and McDonald Islands"],["Vatican"],["Honduras"],["Hong Kong"],["Hungary"],["Iceland"],["India"],["Indonesia"],["Iran"],["Iraq"],["Ireland"],["Isle of Man"],["Israel"],["Italy"],["Jamaica"],["Japan"],["Jersey"],["Jordan"],["Kazakhstan"],["Kenya"],["Kiribati"],["North Korea"],["South Korea"],["Kuwait"],["Kyrgyz Republic"],["Laos"],["Latvia"],["Lebanon"],["Lesotho"],["Liberia"],["Libya"],["Liechtenstein"],["Lithuania"],["Luxembourg"],["Macau"],["North Macedonia"],["Madagascar"],["Malawi"],["Malaysia"],["Maldives"],["Mali"],["Malta"],["Marshall Islands"],["Martinique"],["Mauritania"],["Mauritius"],["Mayotte"],["Mexico"],["Micronesia, Fed. Sts."],["Moldova"],["Monaco"],["Mongolia"],["Montenegro"],["Montserrat"],["Morocco"],["Mozambique"],["Myanmar"],["Namibia"],["Nauru"],["Nepal"],["Netherlands"],["New Caledonia"],["New Zealand"],["Nicaragua"],["Niger"],["Nigeria"],["Niue"],["Norfolk Island"],["Northern Mariana Islands"],["Norway"],["Oman"],["Pakistan"],["Palau"],["Palestine"],["Panama"],["Papua New Guinea"],["Paraguay"],["Peru"],["Philippines"],["Pitcairn"],["Poland"],["Portugal"],["Puerto Rico"],["Qatar"],["Romania"],["Russia"],["Rwanda"],["Reunion"],["St. Barths"],["St. Helena"],["St. Kitts and Nevis"],["St. Lucia"],["Saint-Martin"],["St. Pierre and Miquelon"],["St. Vincent and the Grenadines"],["Samoa"],["San Marino"],["Sao Tome and Principe"],["Saudi Arabia"],["Senegal"],["Serbia"],["Seychelles"],["Sierra Leone"],["Singapore"],["Sint Maarten"],["Slovakia"],["Slovenia"],["Solomon Islands"],["Somalia"],["South Africa"],["South Georgia and South Sandwich Is."],["South Sudan"],["Spain"],["Sri Lanka"],["Sudan"],["Suriname"],["Svalbard and Jan Mayen Islands"],["Eswatini"],["Sweden"],["Switzerland"],["Syria"],["Taiwan"],["Tajikistan"],["Tanzania"],["Thailand"],["Timor-Leste"],["Togo"],["Tokelau"],["Tonga"],["Trinidad and Tobago"],["Tunisia"],["Turkmenistan"],["Turks and Caicos Islands"],["Tuvalu"],["Uganda"],["Ukraine"],["United Arab Emirates"],["United Kingdom"],["United States"],["United States Minor Outlying Islands"],["Uruguay"],["Uzbekistan"],["Vanuatu"],["Venezuela"],["Vietnam"],["British Virgin Islands"],["United States Virgin Islands"],["Wallis and Futuna Islands"],["Western Sahara"],["Yemen"],["Zambia"],["Zimbabwe"],["Aland Islands"],["Turkey"]],"geo":"geo","hovertemplate":"\u003cb\u003e%{hovertext}\u003c\u002fb\u003e\u003cbr\u003e\u003cbr\u003ecountry_name=%{location}\u003cbr\u003esuitability_score=%{z}\u003cextra\u003e\u003c\u002fextra\u003e","hovertext":["Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bonaire, Saint Eustatius and Saba","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cabo Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo Republic","DR Congo","Cook Islands","Costa Rica","Croatia","Cuba","Curacao","Cyprus","Czechia","Cote d'Ivoire","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Heard and McDonald Islands","Vatican","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","North Korea","South Korea","Kuwait","Kyrgyz Republic","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","North Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia, Fed. Sts.","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Romania","Russia","Rwanda","Reunion","St. Barths","St. Helena","St. Kitts and Nevis","St. Lucia","Saint-Martin","St. Pierre and Miquelon","St. Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Sint Maarten","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and South Sandwich Is.","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen Islands","Eswatini","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","British Virgin Islands","United States Virgin Islands","Wallis and Futuna Islands","Western Sahara","Yemen","Zambia","Zimbabwe","Aland Islands","Turkey"],"locationmode":"country names","locations":["Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bonaire, Saint Eustatius and Saba","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cabo Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo Republic","DR Congo","Cook Islands","Costa Rica","Croatia","Cuba","Curacao","Cyprus","Czechia","Cote d'Ivoire","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Heard and McDonald Islands","Vatican","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","North Korea","South Korea","Kuwait","Kyrgyz Republic","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","North Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia, Fed. Sts.","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Romania","Russia","Rwanda","Reunion","St. Barths","St. Helena","St. Kitts and Nevis","St. Lucia","Saint-Martin","St. Pierre and Miquelon","St. Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Sint Maarten","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and South Sandwich Is.","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen Islands","Eswatini","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","British Virgin Islands","United States Virgin Islands","Wallis and Futuna Islands","Western Sahara","Yemen","Zambia","Zimbabwe","Aland Islands","Turkey"],"name":"","z":{"dtype":"f8","bdata":"ucYwJZw4I0DIkNNGVbEoQLVNxODEFTBABUhNF42VRj9i6SrsqlITQKibJ8HX0xhA7dY9iq8G9j8AAAAAAAAAAP32KCcxdhRASZPUlBq4MkBC4JahQrkgQI6Ix+K0ZxVAGlMmc2M3MkDD+K4Uvrs3QEjybZ7pwDBA81UiBZFyEkB30Om8sdUnQJ1mgbzNNyJAKrX2i5PGI0BS6eCNnCEyQGqnBaIRJDdA+uhpgJaAGEBoFD\u002fl\u002fjImQAbdzBHP\u002fyNAIfBZz1vKKkAzTPEmQx8lQOifioKNFEE\u002f6QbNYZEcJkBoOZckk9gpQAAAAAAAAAAA7B4ScUd1K0AAAAAAAAAAAMWhPO1DjwlAKnVJVLZ4LEAQwvAxBzUlQGW+YMqIHhpAv2atyH9\u002fHUA6xKNFQ58cQC68oirdKTRAqhRKHxunKUDqm8k6f44IQJz6Q2lW2RFAJiEf7EN0A0Dnuhv3zlcxQFG1MHWUcDBAAAAAAAAAAAAAAAAAAAAAAMlZYpNicChAJfvMIW27CEDON9UDKhoIQNeioOEfHvc\u002fOCG5LgICBEAFfVmTPD4sQEAlYv1TDTVA7z\u002f1mak3H0BeBOXkT\u002fICQK34RsP++DVAESmpdPWeM0B6rXXXdT0cQDahBaoEkDdAqRezgA4UGEC+8iIbVysUQCZtRsRcAiNActYYaAUiJkCq465vWyYtQP012KlKDCBAoPOHDAF89j8JsDNuFpraPzxgowO7VDZAE\u002fdqTpZtIEAAAAAAAAAEQAAAAAAAABRAo\u002fKYuTHbIEBYQEHzdHg3QEA\u002fCcezljVAzFl4kXiTFEAJ02Ab01PlPwAAAAAAAARAaPQtCFSBDkAaGTkNhFQSQLYwfwMkkyRA7PWirYyKN0ARgs0sTnQnQC4RnhQk\u002fyBA0szFkqhDNUAUJH9eZXEKQEulC0sKTBVAFr7NXb42CkACNxgwcgWdPyxKkGrFXyRAAAAAAAAAFECRMN1fZ5EEQAmMA8U0rh1AeHwvUnY0HECYnxxZL3wWQAAAAAAAAAAAAAAAAAAAGUCqZbFvsxwaQEypPUnL7ilA02JGSGJANUAm0QT2m9Y0QAiJUldGxipAp0uRsVcxKEBUekTUdEAlQIlV7Mx6GCJA4pLDLEpkN0AAAAAAAIAhQMwjp1BAijdAA7whOHNoNEC8rIM4B88gQBRCOM6z6DFAAAAAAAAAFEBgBRns8LUiQCX7t3TppSRAXzAfay3lI0CCB\u002fVnfVb4P+cxopEhbBlAdO3t0I3\u002fNECIJv7QcRovQJ0IiWKvgTBATpZOxjmsEkBW50vLBtw1QC4IvpTmuCtAZkCfd1PMGECiN4Uqa58ZQIjrUYsbNB5AIzK7QeQhGEB+PA4Ti9U1QCt3bOhhNDdAHRbAcZc3IEAdiDNYPfYsQIaNyp\u002fi+BtAHUQJJFRSJkCe0TEZDgUpQHA1wtC6mSlAwmaKE77ZHEDYta4N+842QLuXqYyGKiNAuZncQ2AyDEBMBryOqMQaQLhnOr6isSxA0zGXCE8KCkDG\u002fv6057UlQOMuUPRxSwpATJC1xHPPLkCc8TCDjzgrQNTMvEMWzydAJq\u002fB59OrKUDGfe5h7mAAQCfpQ+0\u002fRDFA5naWh5HIKECQl\u002fY8VQAdQMMwujwlRSNAeEMAlniRE0BQTxuFsAYnQKuKR+J1fzdA\u002fKubBaEDBEBg4r5WEgs6QJZ5mlICtSJA0xAjbldaIUCYH9lrV60ZQMenHjEOAARAAAAAAAAAFECoZnXrWnCqP5dL8f9pgTdAGm0yTaNDKUBjIAm57j8hQJcsQ+7dA\u002fc\u002ftf+aGd8NKEAoAo8u+dUnQCpgUqjbLxlApWqt8xI9LEA4ThbuW6swQAb8z+12mCRAAAAAAAAABEDNJvME65A2QK4D6AYKlDZAZLB30iO5E0ATSzcs+lkyQJ3xWqySbjNAr+olzAVnMUAcWy7TZz8kQOnxD\u002fHUaxhAAAAAAAAAAAAAAAAAAAAOQCIvRGxDZQtAwuoYa6ReCkDKn1dZnNH2P08Kkj+vsh1A6KTPRkg+H0Dx\u002fgHsF14FQOnMI1Bq5SRA0Ldot8MvLEDD8+0oq2IlQGiMfAXCNCxA35hebaCyMUCWbF32KmUkQM6kDO1cSB1AhjKr9ULdMECIC2A26pODP\u002f8tLllNYDNATEBi1JZLNkAk58IgJRcSQIEgjhGAoRxAyz\u002fz+UgRIUAAAAAAAAAAAM+du1aV7gpA\u002fgLKhUtHNUCp4KgLP9kmQCqG+SGHAxpAHh0ZDJ5pJkAAAAAAAAAAAEsQ3M3QhRxAzz317esrN0BegXyxPLAyQEKB559wexdAolXtB1iALUDsxBIaSxUjQMGcr1AhKCVAIxAnf1muJkCotDANzlMRQJwt4HgqgxdAAAAAAAAAAABHzUfflyAUQDqOecM9eyFAfkOjuX6DMUBgRx8BHE8jQC8sRjUAGBdA\u002fjabmnqP9z\u002fz0o09bEwgQNobar+ORS1A6AIu7kqEK0Dr\u002fi7vxN43QPjwoC9SvzVAAAAAAAAAAABihbG3cd4zQC\u002f88mvfJiJAO1DpkFfiH0C+D1XjfegUQGSBL6PfeitAPP1VU6E7FkBSc6j3aVKDPwAAAAAAAAAAAAAAAAAAFEBJv8OmyjUaQM+gqaI8qSJA9IjLfr3pIkAAAAAAAAAUQFG4HoXr0fI\u002f"},"type":"choropleth"}],                        {"template":{"data":{"histogram2dcontour":[{"type":"histogram2dcontour","colorbar":{"outlinewidth":0,"ticks":""},"colorscale":[[0.0,"#0d0887"],[0.1111111111111111,"#46039f"],[0.2222222222222222,"#7201a8"],[0.3333333333333333,"#9c179e"],[0.4444444444444444,"#bd3786"],[0.5555555555555556,"#d8576b"],[0.6666666666666666,"#ed7953"],[0.7777777777777778,"#fb9f3a"],[0.8888888888888888,"#fdca26"],[1.0,"#f0f921"]]}],"choropleth":[{"type":"choropleth","colorbar":{"outlinewidth":0,"ticks":""}}],"histogram2d":[{"type":"histogram2d","colorbar":{"outlinewidth":0,"ticks":""},"colorscale":[[0.0,"#0d0887"],[0.1111111111111111,"#46039f"],[0.2222222222222222,"#7201a8"],[0.3333333333333333,"#9c179e"],[0.4444444444444444,"#bd3786"],[0.5555555555555556,"#d8576b"],[0.6666666666666666,"#ed7953"],[0.7777777777777778,"#fb9f3a"],[0.8888888888888888,"#fdca26"],[1.0,"#f0f921"]]}],"heatmap":[{"type":"heatmap","colorbar":{"outlinewidth":0,"ticks":""},"colorscale":[[0.0,"#0d0887"],[0.1111111111111111,"#46039f"],[0.2222222222222222,"#7201a8"],[0.3333333333333333,"#9c179e"],[0.4444444444444444,"#bd3786"],[0.5555555555555556,"#d8576b"],[0.6666666666666666,"#ed7953"],[0.7777777777777778,"#fb9f3a"],[0.8888888888888888,"#fdca26"],[1.0,"#f0f921"]]}],"contourcarpet":[{"type":"contourcarpet","colorbar":{"outlinewidth":0,"ticks":""}}],"contour":[{"type":"contour","colorbar":{"outlinewidth":0,"ticks":""},"colorscale":[[0.0,"#0d0887"],[0.1111111111111111,"#46039f"],[0.2222222222222222,"#7201a8"],[0.3333333333333333,"#9c179e"],[0.4444444444444444,"#bd3786"],[0.5555555555555556,"#d8576b"],[0.6666666666666666,"#ed7953"],[0.7777777777777778,"#fb9f3a"],[0.8888888888888888,"#fdca26"],[1.0,"#f0f921"]]}],"surface":[{"type":"surface","colorbar":{"outlinewidth":0,"ticks":""},"colorscale":[[0.0,"#0d0887"],[0.1111111111111111,"#46039f"],[0.2222222222222222,"#7201a8"],[0.3333333333333333,"#9c179e"],[0.4444444444444444,"#bd3786"],[0.5555555555555556,"#d8576b"],[0.6666666666666666,"#ed7953"],[0.7777777777777778,"#fb9f3a"],[0.8888888888888888,"#fdca26"],[1.0,"#f0f921"]]}],"mesh3d":[{"type":"mesh3d","colorbar":{"outlinewidth":0,"ticks":""}}],"scatter":[{"fillpattern":{"fillmode":"overlay","size":10,"solidity":0.2},"type":"scatter"}],"parcoords":[{"type":"parcoords","line":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"scatterpolargl":[{"type":"scatterpolargl","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"bar":[{"error_x":{"color":"#2a3f5f"},"error_y":{"color":"#2a3f5f"},"marker":{"line":{"color":"#E5ECF6","width":0.5},"pattern":{"fillmode":"overlay","size":10,"solidity":0.2}},"type":"bar"}],"scattergeo":[{"type":"scattergeo","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"scatterpolar":[{"type":"scatterpolar","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"histogram":[{"marker":{"pattern":{"fillmode":"overlay","size":10,"solidity":0.2}},"type":"histogram"}],"scattergl":[{"type":"scattergl","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"scatter3d":[{"type":"scatter3d","line":{"colorbar":{"outlinewidth":0,"ticks":""}},"marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"scattermap":[{"type":"scattermap","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"scattermapbox":[{"type":"scattermapbox","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"scatterternary":[{"type":"scatterternary","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"scattercarpet":[{"type":"scattercarpet","marker":{"colorbar":{"outlinewidth":0,"ticks":""}}}],"carpet":[{"aaxis":{"endlinecolor":"#2a3f5f","gridcolor":"white","linecolor":"white","minorgridcolor":"white","startlinecolor":"#2a3f5f"},"baxis":{"endlinecolor":"#2a3f5f","gridcolor":"white","linecolor":"white","minorgridcolor":"white","startlinecolor":"#2a3f5f"},"type":"carpet"}],"table":[{"cells":{"fill":{"color":"#EBF0F8"},"line":{"color":"white"}},"header":{"fill":{"color":"#C8D4E3"},"line":{"color":"white"}},"type":"table"}],"barpolar":[{"marker":{"line":{"color":"#E5ECF6","width":0.5},"pattern":{"fillmode":"overlay","size":10,"solidity":0.2}},"type":"barpolar"}],"pie":[{"automargin":true,"type":"pie"}]},"layout":{"autotypenumbers":"strict","colorway":["#636efa","#EF553B","#00cc96","#ab63fa","#FFA15A","#19d3f3","#FF6692","#B6E880","#FF97FF","#FECB52"],"font":{"color":"#2a3f5f"},"hovermode":"closest","hoverlabel":{"align":"left"},"paper_bgcolor":"white","plot_bgcolor":"#E5ECF6","polar":{"bgcolor":"#E5ECF6","angularaxis":{"gridcolor":"white","linecolor":"white","ticks":""},"radialaxis":{"gridcolor":"white","linecolor":"white","ticks":""}},"ternary":{"bgcolor":"#E5ECF6","aaxis":{"gridcolor":"white","linecolor":"white","ticks":""},"baxis":{"gridcolor":"white","linecolor":"white","ticks":""},"caxis":{"gridcolor":"white","linecolor":"white","ticks":""}},"coloraxis":{"colorbar":{"outlinewidth":0,"ticks":""}},"colorscale":{"sequential":[[0.0,"#0d0887"],[0.1111111111111111,"#46039f"],[0.2222222222222222,"#7201a8"],[0.3333333333333333,"#9c179e"],[0.4444444444444444,"#bd3786"],[0.5555555555555556,"#d8576b"],[0.6666666666666666,"#ed7953"],[0.7777777777777778,"#fb9f3a"],[0.8888888888888888,"#fdca26"],[1.0,"#f0f921"]],"sequentialminus":[[0.0,"#0d0887"],[0.1111111111111111,"#46039f"],[0.2222222222222222,"#7201a8"],[0.3333333333333333,"#9c179e"],[0.4444444444444444,"#bd3786"],[0.5555555555555556,"#d8576b"],[0.6666666666666666,"#ed7953"],[0.7777777777777778,"#fb9f3a"],[0.8888888888888888,"#fdca26"],[1.0,"#f0f921"]],"diverging":[[0,"#8e0152"],[0.1,"#c51b7d"],[0.2,"#de77ae"],[0.3,"#f1b6da"],[0.4,"#fde0ef"],[0.5,"#f7f7f7"],[0.6,"#e6f5d0"],[0.7,"#b8e186"],[0.8,"#7fbc41"],[0.9,"#4d9221"],[1,"#276419"]]},"xaxis":{"gridcolor":"white","linecolor":"white","ticks":"","title":{"standoff":15},"zerolinecolor":"white","automargin":true,"zerolinewidth":2},"yaxis":{"gridcolor":"white","linecolor":"white","ticks":"","title":{"standoff":15},"zerolinecolor":"white","automargin":true,"zerolinewidth":2},"scene":{"xaxis":{"backgroundcolor":"#E5ECF6","gridcolor":"white","linecolor":"white","showbackground":true,"ticks":"","zerolinecolor":"white","gridwidth":2},"yaxis":{"backgroundcolor":"#E5ECF6","gridcolor":"white","linecolor":"white","showbackground":true,"ticks":"","zerolinecolor":"white","gridwidth":2},"zaxis":{"backgroundcolor":"#E5ECF6","gridcolor":"white","linecolor":"white","showbackground":true,"ticks":"","zerolinecolor":"white","gridwidth":2}},"shapedefaults":{"line":{"color":"#2a3f5f"}},"annotationdefaults":{"arrowcolor":"#2a3f5f","arrowhead":0,"arrowwidth":1},"geo":{"bgcolor":"white","landcolor":"#E5ECF6","subunitcolor":"white","showland":true,"showlakes":true,"lakecolor":"white"},"title":{"x":0.05},"mapbox":{"style":"light"}}},"geo":{"domain":{"x":[0.0,1.0],"y":[0.0,1.0]},"center":{},"projection":{"type":"natural earth"},"showcountries":true,"countrycolor":"black","showcoastlines":true,"showocean":true,"oceancolor":"lightblue","showframe":false},"coloraxis":{"colorbar":{"title":{"text":"\ud83c\udf21\ufe0f Suitability Score"},"tickvals":[0.0,6.510812144661571,13.021624289323142,19.532436433984714,26.043248578646285],"ticktext":["\u274c Tr\u00e9s bas (0.0)","\u26a0\ufe0f Bas (6.5)","\ud83d\ude10 Moyen (13.0)","\u2705 Elev\u00e9 (19.5)","\ud83c\udf1f Tr\u00e9s elev\u00e9 (26.0)"]},"colorscale":[[0.0,"rgb(165,0,38)"],[0.1,"rgb(215,48,39)"],[0.2,"rgb(244,109,67)"],[0.3,"rgb(253,174,97)"],[0.4,"rgb(254,224,139)"],[0.5,"rgb(255,255,191)"],[0.6,"rgb(217,239,139)"],[0.7,"rgb(166,217,106)"],[0.8,"rgb(102,189,99)"],[0.9,"rgb(26,152,80)"],[1.0,"rgb(0,104,55)"]]},"legend":{"tracegroupgap":0},"margin":{"t":50,"r":0,"l":0,"b":0},"modebar":{"orientation":"h","bgcolor":"rgba(255,255,255,0.8)","color":"black","activecolor":"blue"},"updatemenus":[{"buttons":[{"args":[{"geo.center.lon":0,"geo.center.lat":20,"geo.zoom":1}],"label":"\ud83c\udf0d World","method":"relayout"},{"args":[{"geo.center.lon":-100,"geo.center.lat":50,"geo.zoom":2.5}],"label":"\ud83c\udf0e North America","method":"relayout"},{"args":[{"geo.center.lon":-60,"geo.center.lat":-15,"geo.zoom":2.5}],"label":"\ud83c\udf0e South America","method":"relayout"},{"args":[{"geo.center.lon":10,"geo.center.lat":50,"geo.zoom":3}],"label":"\ud83c\udf0d Europe","method":"relayout"},{"args":[{"geo.center.lon":20,"geo.center.lat":0,"geo.zoom":2.5}],"label":"\ud83c\udf0d Africa","method":"relayout"},{"args":[{"geo.center.lon":100,"geo.center.lat":40,"geo.zoom":2.5}],"label":"\ud83c\udf0f Asia","method":"relayout"},{"args":[{"geo.center.lon":140,"geo.center.lat":-25,"geo.zoom":3}],"label":"\ud83c\udf0f Oceania","method":"relayout"}],"direction":"down","type":"buttons","x":0.1,"y":1.15}]},                        {"responsive": true}                    )                };            </script>        </div></div>

    <!-- Top 10 Countries List -->
    <div id='top-countries'><h2>Top 10 Pays</h2><ul><li>New Zealand: 26.0</li><li>United Kingdom: 23.9</li><li>Austria: 23.7</li><li>Denmark: 23.6</li><li>Germany: 23.5</li><li>Israel: 23.5</li><li>Norway: 23.5</li><li>Netherlands: 23.5</li><li>Finland: 23.5</li><li>Ireland: 23.4</li></ul></div>

    <div class="footer">
        <p>Data sourced from global statistics. Click on a country to see details.</p>
    </div>

    
<script>

    document.addEventListener('DOMContentLoaded', function () {
    function initializePlotlyEvents() {
        const plotlyGraphDiv = document.querySelector(".plotly-graph-div"); // Select the Plotly div dynamically

        if (!plotlyGraphDiv) {
            console.error("Plotly graph not loaded yet. Retrying...");
            setTimeout(initializePlotlyEvents, 500); // Retry after 500ms
            return;
        }

        console.log("✅ Plotly graph detected. Attaching event listeners...");

        // Click event listener for the Plotly map
        plotlyGraphDiv.on('plotly_click', function (data) {
            if (data.points.length > 0) {
                let country = data.points[0].customdata[0]; // Get country name

                // Convert "Turkey" to "Türkiye"
                if (country === "Turkey") {
                    country = "Türkiye";
                }

                console.log(`🌍 Selected country: ${country}`);

                // Redirect to graph.php with selected country as a parameter
                window.location.href = `dashboard.php?country=${encodeURIComponent(country)}`;
            }
        });
    }

    // Start checking if the graph exists
    initializePlotlyEvents();
});

</script>


</body>
</html>
