<?php
session_start();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Questionnaire</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        /* Center the title text */
        .page-header {
            text-align: center;
            margin-top: 50px;
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        /* Subtitle styling */
        .page-subtitle {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 10px;
        }

        /* Add padding to the content for spacing */
        .content-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center; /* Ensures all inner text is centered */
        }

        h2 {
            justify-self: center;
            margin-top: 80px;
        }
        p {
            justify-self: center;
        }
        .step-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ccc;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .step.active {
            border: 2px solid #4CAF50; /* Border for the active step */
        }

        .step.green {
            background-color: #4CAF50; /* Green for fully completed page */
        }

        .step.yellow {
            background-color: #FFEB3B; /* Yellow for partially completed page */
            color: black; /* Black text for better readability on yellow */
        }

        .step.red {
            background-color: #e53935; /* Red for pages with no questions answered */
        
        }
        .questionnaire-section { display: none; }
        .questionnaire-section.active { display: block; }
        .navigation-buttons { display: flex; justify-content: space-between; margin-top: 20px; }
        /* Hide the actual checkbox */

        /* Loading Screen */
        #loadingScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none; /* Initially hidden */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            z-index: 9999;
            transition: opacity 0.5s ease-in-out;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .hide {
            opacity: 0;
            pointer-events: none;
        }
input[type="checkbox"] {
    display: none;
}

/* Style the label to look like a selectable button */
input[type="checkbox"] + label {
    display: inline-block;
    padding: 10px 15px;
    margin: 5px;
    border: white;
    border-radius: 5px;
    cursor: pointer;
    background-color: rgb(236, 236, 236);
    color:rgb(83, 83, 83);
    transition: all 0.3s ease;
}

/* Change background when hovered */
input[type="checkbox"] + label:hover {
    background-color:lightgrey;
    color: black;
}

/* Change appearance when selected */
input[type="checkbox"]:checked + label {
    background-color:rgb(0, 102, 255);
    color: white;
}

    </style>
</head>
<body>

    <!-- Loading Screen (Hidden Initially) -->
    <div id="loadingScreen">
        <div class="loader"></div>
        <p>Chargement de la carte en cours...</p>
    </div>

    <!-- Menu superieur -->
    <header>
        <div class="menu-bar">
        <div class="menu-item">
        <?php
            if (isset($_SESSION['client'])) {
                echo '<a href="questionnaire.php">';
            } else {
                echo '<a href="../connexion/login.php">';
            }
            ?>
            <img src="../images/images_ced/icone1.png" alt="Icone Questionnaire">
            </a>
            <p>Questionnaire</p>
        </div>
        <div class="menu-item">
        <a href="graph.php"><img src="../images/images_ced/icone2.png" alt="Icone Statistiques & Graphs"></a>
            <p>Statistiques & Graphs</p>
        </div>
        <div class="menu-item">
        <a href="../forum/forum.php"><img src="../images/images_ced/icone7.png" alt="Forum"></a>
           <p>Forum</p>
        </div>
        <div class="menu-item logo">
        <a href="../index.php"><img src="../images/images_ced/logo.png" alt="Logo"></a>
            
        </div>
        <div class="menu-item">
        <a href="../informations/informations.php"><img src="../images/images_ced/icone4.png" alt="Icone Informations"></a>
            <p>Informations</p>
        </div>
        <div class="menu-item">
        <a href="../informations/sources.php"><img src="../images/images_ced/icone5.png" alt="Icone Sources données"></a>
            <p>Sources données</p>
        </div>
        <div class="menu-item">
        <a href="../utilisateur/profil.php"><img src="../images/images_ced/icone6.png" alt="Icone Options"></a>
            <p>Profil</p>
        </div>
    </header>

            <!-- QUESTIONNAIRE -->
    <div class="container">
        <h2 id="form_beginning">Début du questionnaire</h2>
        <p id="form_beginning">Le temps de réponse moyen est de <strong>- de 5 minutes</strong>.Vos réponses sont traitées <strong>anonymement</strong>, et vos résultats le seront aussi.
        <br> Attention, vous ne pouvez pas stocker plusieurs tentatives dans votre profil. <strong>Une seule</strong> sera stockée.
        <br>Si vous avez des questions n'hésitez pas à contacter <a href="mailto:corentin.labat-jarleton@etu.univ-montp3.fr">corentin.labat-jarleton@etu.univ-montp3.fr</a></p>
        <form action="run_generate_map.php" method="POST" id="questionnaireForm">

        
 
    <!-- Page 2 -->
<div class="questionnaire-section active">
    <!-- Question 7 -->
    <div class="question">
        <label for="question7">Afin de débuter le questionnaire, souhaiteriez-vous plutôt vivre dans un nouveau pays ou y voyager ?</label>
        <div class="options">
            <input type="radio" id="q7_option1" name="choix" value="vivre" required>
            <label for="q7_option1">Je souhaite y vivre</label><br>
            <input type="radio" id="q7_option2" name="choix" value="voyager">
            <label for="q7_option2">Je souhaite y voyager</label><br>
        </div>
    </div>
</div>


            <!-- Page 3 -->
            <div class="questionnaire-section">
    <!-- Question 8 -->
    <div class="question">
        <label for="question8">Accordez-vous de l'importance à l'utilisation des énergies propres (électricité, gaz contrairement au charbon et d'autres combustibles polluants) pour la cuisine ? (Un seul choix)</label>
        <div class="options">
            <input type="radio" id="q8_option1" name="agroalimentaire-cleanfuelandcookingequipment" value=1 required>
            <label for="q8_option1">Très important</label><br>
            <input type="radio" id="q8_option2" name="agroalimentaire-cleanfuelandcookingequipment" value=0.75>
            <label for="q8_option2">Important</label><br>
            <input type="radio" id="q8_option3" name="agroalimentaire-cleanfuelandcookingequipment" value=0.5>
            <label for="q8_option3">Neutre</label><br>
            <input type="radio" id="q8_option4" name="agroalimentaire-cleanfuelandcookingequipment" value=0.25>
            <label for="q8_option4">Peu important</label><br>
            <input type="radio" id="q8_option5" name="agroalimentaire-cleanfuelandcookingequipment" value=0>
            <label for="q8_option5">Pas du tout important</label><br>
        </div>
    </div>

    <!-- Question 9 -->
    <div class="question">
        <label for="question9">Dans quelle mesure êtes-vous d'accord avec l'affirmation suivante : "Le coût d'une alimentation saine est un obstacle pour maintenir une diète équilibrée (Un seul choix)"</label>
        <div class="options">
            <input type="radio" id="q9_option1" name="agroalimentaire-costhealthydiet" value=1 required>
            <label for="q9_option1">Tout à fait d'accord</label><br>
            <input type="radio" id="q9_option2" name="agroalimentaire-costhealthydiet" value=0.75>
            <label for="q9_option2">Plutôt d'accord</label><br>
            <input type="radio" id="q9_option3" name="agroalimentaire-costhealthydiet" value=0.5>
            <label for="q9_option3">Neutre</label><br>
            <input type="radio" id="q9_option4" name="agroalimentaire-costhealthydiet" value=0.25>
            <label for="q9_option4">Plutôt en désaccord</label><br>
            <input type="radio" id="q9_option5" name="agroalimentaire-costhealthydiet" value=0>
            <label for="q9_option5">Pas du tout d'accord</label><br>
        </div>
    </div>
</div>

    <!-- Page 4 -->
<div class="questionnaire-section">
    <!-- Question 10 -->
    <div class="question">
        <label for="question10">Quel impact apporte dans votre vie la bienveillance des autres? (Un seul choix)</label>
        <div class="options">
            <input type="radio" id="q10_option1" name="bonheur-score_bonheur" value=1 required>
            <label for="q10_option1">Trop Important</label><br>
            <input type="radio" id="q10_option2" name="bonheur-score_bonheur" value=0.75>
            <label for="q10_option2">Important</label><br>
            <input type="radio" id="q10_option3" name="bonheur-score_bonheur" value=0.5>
            <label for="q10_option3">Neutre</label><br>
            <input type="radio" id="q10_option4" name="bonheur-score_bonheur" value=0.25>
            <label for="q10_option4">Pas trop important</label><br>
            <input type="radio" id="q10_option5" name="bonheur-score_bonheur" value=0>
            <label for="q10_option5">Pas du tout important</label><br>
        </div>
    </div>


    <!-- Question 11 -->
    <div class="question">
        <label for="question11">Quel type d'education concerne directement votre vie personnelle? (Choix multiple)</label>
        <div class="options">
            <input type="checkbox" id="q11_option1" name="education-taux_classe_primaire" value=0.5>
            <label for="q11_option1">Cycle Primaire</label><br>
            <input type="checkbox" id="q11_option2" name="education-taux_classe_secondaire" value=0.5>
            <label for="q11_option2">Cycle Secondaire</label><br>
            <input type="checkbox" id="q11_option3" name="education-taux_classe_primaire" value=0>
            <label for="q11_option3">Non Concerné(e)/Pas Intéréssé(e)</label><br>
        </div>
    </div>
</div>


            <!-- Page 5 -->
            <div class="questionnaire-section">
    
    <!-- Header -->
    <div class="content-container">
        <h1 class="page-header">Veuillez sellectionner les intervales de temperature idéales pour votre pays de destination pendant (Un seul choix) :</h1>
    </div>
    
    <!-- Question 12 -->
    <h2 class="page-subtitle">La période d'été</h2>
    <div class="question">
        <label for="question12"></label>
        <div class="options">
            <input type="radio" id="q12_option1" name="meteo-ete_tavg-minimal" value=10>
            <label for="q12_option1">Plus petit que 10°C</label><br>
            <input type="radio" id="q12_option2" name="meteo-ete_tavg-middle_10_20" value=20>
            <label for="q12_option2">Entre 10°C et 20°C</label><br>
            <input type="radio" id="q12_option3" name="meteo-ete_tavg-middle_20_30" value=30>
            <label for="q12_option3">Entre 20°C et 30°C</label><br>
            <input type="radio" id="q12_option4" name="meteo-ete_tavg-maximal" value=30>
            <label for="q12_option4">Plus que 30°C</label><br>
        </div>
    </div>

    <!-- Question 13 -->
    <h2 class="page-subtitle">L'automne</h2>
    <div class="question">
        <label for="question13"></label>
        <div class="options">
            <input type="radio" id="q13_option1" name="meteo-automne_tavg-minimal" value=5>
            <label for="q13_option1">Plus petit que 5°C</label><br>
            <input type="radio" id="q13_option2" name="meteo-automne_tavg-middle_5_15" value=15>
            <label for="q13_option2">Entre 5°C et 15°C</label><br>
            <input type="radio" id="q13_option3" name="meteo-automne_tavg-middle_15_25" value=25>
            <label for="q13_option3">Entre 15°C et 25°C</label><br>
            <input type="radio" id="q13_option4" name="meteo-automne_tavg-maximal" value=25>
            <label for="q13_option4">Plus que 25°C</label><br>
        </div>
    </div>

    <!-- Question 14 -->
    <h2 class="page-subtitle">La période d'hiver</h2>
    <div class="question">
        <label for="question14"></label>
        <div class="options">
            <input type="radio" id="q14_option1" name="meteo-hiver_tavg-minimal" value=0>
            <label for="q14_option1">Plus petit que 0°C</label><br>
            <input type="radio" id="q14_option2" name="meteo-hiver_tavg-middle_0_10" value=10>
            <label for="q14_option2">Entre 0°C et 10°C</label><br>
            <input type="radio" id="q14_option3" name="meteo-hiver_tavg-middle_10_20" value=20>
            <label for="q14_option3">Entre 10°C et 20°C</label><br>
            <input type="radio" id="q14_option4" name="meteo-hiver_tavg-maximal" value=20>
            <label for="q14_option4">Plus que 20°C</label><br>
        </div>
    </div>
    
    <!-- Question 15 -->
    <h2 class="page-subtitle">Le printemps</h2>
    <div class="question">
        <label for="question15"></label>
        <div class="options">
            <input type="radio" id="q15_option1" name="meteo-printemps_tavg-minimal" value=5>
            <label for="q15_option1">Plus petit que 5°C</label><br>
            <input type="radio" id="q15_option2" name="meteo-printemps_tavg-middle_5_15" value=15>
            <label for="q15_option2">Entre 5°C et 15°C</label><br>
            <input type="radio" id="q15_option3" name="meteo-printemps_tavg-middle_15_25" value=25>
            <label for="q15_option3">Entre 15°C et 25°C</label><br>
            <input type="radio" id="q15_option4" name="meteo-printemps_tavg-maximal" value=25>
            <label for="q15_option4">Plus que 25°C</label><br>
        </div>
    </div>

    
</div>

<!-- Page 6 -->
<div class="questionnaire-section">
    <!-- Question 16 -->
    <div class="question">
        <label for="question16">Que représente la religion pour vous en termes d'importance ? (Un seul choix)</label>
        <div class="options">
            <input type="radio" id="q16_option1" name="religion-important" value=1>
            <label for="q16_option1">Important</label><br>
            <input type="radio" id="q16_option2" name="religion-plutot_important" value=1>
            <label for="q16_option2">Plutôt important</label><br>
            <input type="radio" id="q16_option3" name="religion-plutot_pas_important" value=1>
            <label for="q16_option3">Plutôt pas important</label><br>
            <input type="radio" id="q16_option4" name="religion-pas_important" value=1>
            <label for="q16_option4">Pas important</label><br>
            <input type="radio" id="q16_option5" name="religion-ne_sais_pas" value=1>
            <label for="q16_option5">Ne sais pas</label><br>
            <input type="radio" id="q16_option6" name="religion-ne_se_prononce_pas" value=1>
            <label for="q16_option6">Ne se prononce pas</label><br>
        </div>
    </div>

    <!-- Question 17 -->
    <div class="question">
        <label for="question17">Quelle importance a pour vous le taux de criminalité d'un pays? (Un seul choix)</label>
        <div class="options">
            <input type="radio" id="q17_option1" name="crime-taux" value="1" required>
            <label for="q17_option1">Important</label><br>
            <input type="radio" id="q17_option2" name="crime-taux" value="0.75">
            <label for="q17_option2">Plutôt imoportant</label><br>
            <input type="radio" id="q17_option3" name="crime-taux" value="0.5">
            <label for="q17_option3">Neutre</label><br>
            <input type="radio" id="q17_option4" name="crime-taux" value="0.25">
            <label for="q17_option4">Plutôt pas imoportant</label><br>
            <input type="radio" id="q17_option5" name="crime-taux" value="0">
            <label for="q17_option5">Peu m'importe</label><br>
        </div>
    </div>

    <!-- Question 18 -->
    <div class="question">
        <label for="question18">À quel point est-il important pour vous que votre pays d'accueil soit ouvert aux visiteurs et échanges internationaux ? (Un seul choix)</label>
        <div class="options">
            <input type="radio" id="q18_option1" name="tourisme-inbound_arrival" value=1 required>
            <label for="q18_option1">Très important</label><br>
            <input type="radio" id="q18_option2" name="tourisme-inbound_arrival" value=0.75>
            <label for="q18_option2">Important</label><br>
            <input type="radio" id="q18_option3" name="tourisme-inbound_arrival" value=0.5>
            <label for="q18_option3">Neutre</label><br>
            <input type="radio" id="q18_option4" name="tourisme-inbound_arrival" value=0.25>
            <label for="q18_option4">Peu important</label><br>
            <input type="radio" id="q18_option5" name="tourisme-inbound_arrival" value=0>
            <label for="q18_option5">Pas du tout important</label><br>
        </div>
    </div>
</div>


<!-- Page 7 -->
<div class="questionnaire-section">
    <!-- Question 20 -->
    <div class="question">
        <label for="question20">Quel type de chomage vous concerne sur un pays? (Choix multiple)</label>
        <div class="options">
            <input type="checkbox" id="q20_option1" name="travail-sans_emploi_femme" value=1>
            <label for="q20_option1">Taux de chomage des hommes</label><br>
            <input type="checkbox" id="q20_option2" name="travail-sans_emploi_homme" value=1>
            <label for="q20_option2">Taux de chomage des femmes</label><br>
            <input type="checkbox" id="q20_option3" name="travail-sans_emploi_femme" value=0>
            <label for="q20_option3">Pas intérésant</label><br>
        </div>
    </div>

    <!-- Question 21 -->
    <div class="question">
        <label for="question21">Quelles sont vos prioritées en matière de conditions sociales dans un pays ? (Choix multiple)</label>
        <div class="options">
            <input type="checkbox" id="q21_option1" name="social-salaire_min_annuel" value=1>
            <label for="q21_option1">Revenu mensuel élevé</label><br>
            <input type="checkbox" id="q21_option2" name="social-salaire_min_heure" value=1>
            <label for="q21_option2">Salaire horaire compétitif</label><br>
            <input type="checkbox" id="q21_option3" name="social-acces_elect" value=1>
            <label for="q21_option3">Accès universel à l'électricité</label><br>
            <input type="checkbox" id="q21_option4" name="social-salaire_min_annuel" value=0>
            <label for="q21_option4">Autre</label><br>
            <input type="checkbox" id="q21_option5" name="social-salaire_min_annuel" value=0>
            <label for="q21_option5">Peu importe</label><br>
        </div>
    </div>

    <!-- Question 22 -->
    <div class="question"> 
    <label for="question22">À quel point la générosité des autres est importante pour vous ? (Un seul choix)</label>
    <div class="options">
        <input type="radio" id="q22_option1" name="bonheur-generosite" value="0" required>
        <label for="q22_option1">Pas important du tout</label><br>
        <input type="radio" id="q22_option2" name="bonheur-generosite" value="0.25">
        <label for="q22_option2">Pas trop important</label><br>
        <input type="radio" id="q22_option3" name="bonheur-generosite" value="0.5">
        <label for="q22_option3">Neutre</label><br>
        <input type="radio" id="q22_option4" name="bonheur-generosite" value="0.75">
        <label for="q22_option4">Plutôt important</label><br>
        <input type="radio" id="q22_option5" name="bonheur-generosite" value="1">
        <label for="q22_option5">Très important</label><br>
    </div>
</div>
</div>

<!-- Page 8 -->
<div class="questionnaire-section">

    <!-- Question 23 -->
    <div class="question"> 
    <label for="question23">Séléctionnez les concepts que vous considerez nécéssaires pour votre pays de destination : (Choix multiple)</label>
    <div class="options">
        <input type="checkbox" id="q23_option1" name="corruption-liberte_expression" value=1>
        <label for="q23_option1">Pouvoir de s'exprimér libre</label><br>
        <input type="checkbox" id="q23_option2" name="corruption-corruption_politique" value=1>
        <label for="q23_option2">Taux de corruption politique faible</label><br>
        <input type="checkbox" id="q23_option3" name="corruption-rule_of_law" value=1>
        <label for="q23_option3">La rigeur de l'application des lois</label><br>
        <input type="checkbox" id="q23_option4" name="corruption-liberte_expression" value=0>
        <label for="q23_option4">Peu importe</label><br>
    </div>
</div>

    <!-- Question 24 -->
    <div class="question">
        <label for="question24">Quelles sont vos attentes concernant les conditions de santé d'un pays ? (Choix multiple)</label>
        <div class="options">
            <input type="checkbox" id="q24_option1" name="sante-mort" value=1>
            <label for="q24_option1">Faible mortalité estimée</label><br>
            <input type="checkbox" id="q24_option2" name="sante-naissance" value=1>
            <label for="q24_option2">Fort taux de natalité</label><br>
            <input type="checkbox" id="q24_option3" name="sante-esperance_vie" value=1>
            <label for="q24_option3">Espérance de vie élevée</label><br>
            <input type="checkbox" id="q24_option4" name="sante-mort" value=0>
            <label for="q24_option4">Peu m'importe</label><br>
        </div>
    </div>
    </div>

    <!-- Question 25 -->
    <div class="questionnaire-section">
    <div class="question"> 
    <label for="question25">Marquez toutes les concepts économiques importants pour vous : (Choix multiple)</label>
    <div class="options">
        <input type="checkbox" id="q25_option1" name="economie-Per capita GNI" value=1>
        <label for="q25_option1">Revenu par habitant</label><br>
        <input type="checkbox" id="q25_option2" name="economie-Agriculture, hunting, forestry, fishing (ISIC A-B)" value=1>
        <label for="q25_option2">Agriculture</label><br>
        <input type="checkbox" id="q25_option3" name="economie-Construction (ISIC F)" value=1>
        <label for="q25_option3">Construction</label><br>
        <input type="checkbox" id="q25_option4" name="economie-Exports of goods and services" value=1>
        <label for="q25_option4">Exports</label><br>
        <input type="checkbox" id="q25_option5" name="economie-Final consumption expenditure" value=1>
        <label for="q25_option5">Investition de gouvernement dans l'infrastructure et services</label><br>
        <input type="checkbox" id="q25_option6" name="economie-Gross capital formation" value=1>
        <label for="q25_option6">Investition dans des biens durables</label><br>
        <input type="checkbox" id="q25_option7" name="economie-Household consumption expenditure" value=1>
        <label for="q25_option7">Depenses pour le menage d'un famille</label><br>
        <input type="checkbox" id="q25_option8" name="economie-Imports of goods and services" value=1>
        <label for="q25_option8">Imports of goods and services</label><br>
        <input type="checkbox" id="q25_option9" name="economie-Manufacturing (ISIC D)" value=1>
        <label for="q25_option9">Manufacturing</label><br>
        <input type="checkbox" id="q25_option10" name="economie-Mining, Manufacturing, Utilities (ISIC C-E)" value=1>
        <label for="q25_option10">Mining et services publiques</label><br>
        <input type="checkbox" id="q25_option11" name="economie-Total Value Added" value=1>
        <label for="q25_option11">TVA</label><br>
        <input type="checkbox" id="q25_option12" name="economie-Transport, storage and communication (ISIC I)" value=1>
        <label for="q25_option12">Investitions dans le secteur de transports</label><br>
        <input type="checkbox" id="q25_option13" name="economie-Wholesale, retail trade, restaurants and hotels (ISIC G-H)" value=1>
        <label for="q25_option13">Secteur retail trade et hotels</label><br>
        <input type="checkbox" id="q25_option14" name="economie-Gross National Income(GNI) in USD" value=1>
        <label for="q25_option14">Somme de tous revenus gagnés par les habitants</label><br>
        <input type="checkbox" id="q25_option15" name="economie-Gross Domestic Product (GDP)" value=1>
        <label for="q25_option15">PIB (Richese de la pays en biens et services)</label><br>
    </div>
</div>

<!-- Question 19 -->
<div class="question">
        <label for="question19">À quel point est-il important pour vous de se déplacer à l'aide des transports en commun ? (Un seul choix)</label>
        <div class="options">
            <input type="radio" id="q19_option1" name="transport-taux_acces_transport" value=1 required>
            <label for="q19_option1">Très important</label><br>
            <input type="radio" id="q19_option2" name="transport-taux_acces_transport" value=0.75>
            <label for="q19_option2">Important</label><br>
            <input type="radio" id="q19_option3" name="transport-taux_acces_transport" value=0.5>
            <label for="q19_option3">Neutre</label><br>
            <input type="radio" id="q19_option4" name="transport-taux_acces_transport" value=0.25>
            <label for="q19_option4">Peu important</label><br>
            <input type="radio" id="q19_option5" name="transport-taux_acces_transport" value=0>
            <label for="q19_option5">Pas du tout important</label><br>
        </div>
    </div>
</div>



            <!-- Step indicator and submit button -->
            <div class="step-container" id="stepContainer"></div>
            <div id="submitSection" style="display: none;">
                <input type="submit" value="Envoyer" class="gradient-button">
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    let currentPage = 0;
    const sections = document.querySelectorAll(".questionnaire-section"); // Select all pages
    const stepContainer = document.getElementById("stepContainer");

    const form = document.getElementById("questionnaireForm");
            form.addEventListener("submit", function (event) {
                document.getElementById("loadingScreen").style.display = "flex"; // Show loader
                event.target.querySelector("input[type='submit']").disabled = true; // Disable submit button
            });

    function createStepIndicators() {
        sections.forEach((_, index) => {
            const step = document.createElement("div");
            step.classList.add("step");
            step.textContent = index + 1; // Label each step with a page number
            step.addEventListener("click", () => goToPage(index)); // Make each step clickable to navigate pages
            stepContainer.appendChild(step);
        });
        updateStepIndicator(currentPage);
    }

    function updateStepIndicator(pageIndex) {
        const steps = document.querySelectorAll(".step");
        steps.forEach((step, index) => {
            step.classList.toggle("active", index === pageIndex); // Highlight active step
            const completionStatus = getPageCompletionStatus(index);
            step.classList.remove("green", "yellow", "red");
            step.classList.add(completionStatus);
        });
    }

    function getPageCompletionStatus(pageIndex) {
        const section = sections[pageIndex];

        if (section.style.display === "none") return "hidden"; // Ignore hidden pages ✅

        const answeredRadios = section.querySelectorAll("input[type='radio']:checked").length;
        const checkboxQuestions = section.querySelectorAll(".question input[type='checkbox']");
        const uniqueCheckboxNames = [...new Set(Array.from(checkboxQuestions).map(input => input.name))];
        const answeredCheckboxes = uniqueCheckboxNames.filter(name =>
            section.querySelector(`input[name="${name}"]:checked`)
        ).length;
        const numberInputs = section.querySelectorAll("input[type='number']");
        const answeredNumbers = Array.from(numberInputs).filter(input => input.value.trim() !== "").length;

        const totalQuestions = section.querySelectorAll(".question").length;

        if (answeredRadios + answeredCheckboxes + answeredNumbers === 0) {
            return "red";
        } else if (answeredRadios + answeredCheckboxes + answeredNumbers >= totalQuestions) {
            return "green";
        } else {
            return "yellow";
        }
    }

    function showPage(pageIndex) {
        sections.forEach((section, index) => {
            section.classList.toggle("active", index === pageIndex);
        });

        // Ensure only visible pages are counted
        document.getElementById("submitSection").style.display = pageIndex === getLastVisiblePageIndex() ? "block" : "none";
        updateStepIndicator(pageIndex);
    }

    function getLastVisiblePageIndex() {
        let lastVisibleIndex = 0;
        sections.forEach((section, index) => {
            if (section.style.display !== "none") lastVisibleIndex = index;
        });
        return lastVisibleIndex;
    }

    function changePage(step) {
        do {
            currentPage += step;
        } while (currentPage >= 0 && currentPage < sections.length && sections[currentPage].style.display === "none");

        if (currentPage >= 0 && currentPage < sections.length) {
            showPage(currentPage);
        }
    }

    function goToPage(pageIndex) {
        if (sections[pageIndex].style.display !== "none") {
            currentPage = pageIndex;
            showPage(currentPage);
        }
    }

    // ✅ Initialize step indicators and show first page
    createStepIndicators();
    showPage(currentPage);

    // ✅ Get "vivre/voyager" selection & dependent questions
    function getQuestionsByLabel(...labels) {
        return labels.map(label => {
            const input = document.querySelector(`label[for="${label}"]`);
            return input ? input.closest(".question") : null;
        }).filter(q => q !== null);
    }

    const vivreQuestions = getQuestionsByLabel("question9", "question16", "question20", "question21", "question24", "question25");

    const choixRadios = document.querySelectorAll("input[name='choix']");

    function updateQuestionVisibility() {
        const selectedValue = document.querySelector("input[name='choix']:checked")?.value;

        if (selectedValue === "voyager") {
            // Hiding and making 'vivre' questions inactive
            vivreQuestions.forEach(el => el.style.display = "none");
            vivreQuestions.forEach(el => el.querySelectorAll("input").forEach(input => input.disabled = true));
            // Modifying values for 'voyager' questions
            document.getElementById("q10_option1").value = parseFloat(0.5);
            document.getElementById("q10_option2").value = parseFloat(0.37);
            document.getElementById("q10_option3").value = parseFloat(0.25);
            document.getElementById("q10_option4").value = parseFloat(0.12);
            document.getElementById("q10_option5").value = parseFloat(0);

        } else if (selectedValue === "vivre") {
            // Reverting the changes back to normal
            // Visibility
            vivreQuestions.forEach(el => el.style.display = "block");
            vivreQuestions.forEach(el => el.querySelectorAll("input").forEach(input => input.disabled = false));
            // Values
            document.getElementById("q10_option1").value = parseFloat(1);    
            document.getElementById("q10_option2").value = parseFloat(0.75);
            document.getElementById("q10_option3").value = parseFloat(0.5);
            document.getElementById("q10_option4").value = parseFloat(0.25);
            document.getElementById("q10_option5").value = parseFloat(0);
        }

        // **Force re-selection to ensure correct values**
        document.querySelectorAll("input[name='bonheur-score_bonheur']").forEach(radio => {
            if (radio.checked) {
                radio.checked = false;
                setTimeout(() => (radio.checked = true), 0); // Re-select after a short delay
            }
        });

        // ✅ Update step indicators dynamically
        updateStepIndicator(currentPage);
    }

    // ✅ Attach listener to choix radio buttons
    choixRadios.forEach(radio => {
        radio.addEventListener("change", () => {
            updateQuestionVisibility();
            updateStepIndicator(currentPage); // Ensure the progress bar is updated after hiding questions
        });
    });

    updateQuestionVisibility(); // Run once in case of pre-selected option

    // ✅ Update step indicators when a question is answered
    document.querySelectorAll("input[type='radio'], input[type='checkbox'], input[type='number']").forEach(input => {
        input.addEventListener("change", () => updateStepIndicator(currentPage));
    });

    // ✅ Uncheck other options when selecting within a temperature or religion category
    function setupExclusiveSelection(groupSelector) {
        const groupRadios = document.querySelectorAll(groupSelector);
        groupRadios.forEach(radio => {
            radio.addEventListener("change", function () {
                groupRadios.forEach(r => {
                    if (r !== this) r.checked = false;
                });
            });
        });
    }

    setupExclusiveSelection("input[id^='q12_option']");
    setupExclusiveSelection("input[id^='q13_option']");
    setupExclusiveSelection("input[id^='q14_option']");
    setupExclusiveSelection("input[id^='q15_option']");
    setupExclusiveSelection("input[id^='q16_option']");
});

    </script>
</body>
</html>
