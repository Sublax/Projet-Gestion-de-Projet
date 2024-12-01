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
    </style>
</head>
<body>
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
        <p id="form_beginning">Le temps de réponse est d'environ x minutes.</p>
        <form action="run_generate_map.php" method="POST" id="questionnaireForm">
            
            <!-- Page 1 -->
<div class="questionnaire-section active">
    <!-- Question 1 -->
    <div class="question">
        <label for="question1">Question 1: Afin de débuter le questionnaire, souhaiteriez-vous plutôt vivre dans un nouveau pays ou y voyager ?</label>
        <div class="options">
            <input type="radio" id="q1_option1" name="question1" value="Très insatisfait" required>
            <label for="q1_option1">Je souhaite y vivre</label><br>
            <input type="radio" id="q1_option2" name="question1" value="Insatisfait">
            <label for="q1_option2">Je souhaite y voyager</label><br>
        </div>
    </div>
</div>


            <!-- Page 2 -->
            <div class="questionnaire-section">
    <!-- Question 2 -->
    <div class="question">
        <label for="question2">Question 2: Accordez-vous de l'importance à l'utilisation des énergies propres (électricité, gaz contrairement au charbon et d'autres combustibles polluants) pour la cuisine ?</label>
        <div class="options">
            <input type="radio" id="q2_option1" name="question2" value="Très important" required>
            <label for="q2_option1">Très important</label><br>
            <input type="radio" id="q2_option2" name="question2" value="Important">
            <label for="q2_option2">Important</label><br>
            <input type="radio" id="q2_option3" name="question2" value="Neutre">
            <label for="q2_option3">Neutre</label><br>
            <input type="radio" id="q2_option4" name="question2" value="Peu important">
            <label for="q2_option4">Peu important</label><br>
            <input type="radio" id="q2_option5" name="question2" value="Pas du tout important">
            <label for="q2_option5">Pas du tout important</label><br>
        </div>
    </div>

    <!-- Question 3 -->
    <div class="question">
        <label for="question3">Question 3: Dans quelle mesure êtes-vous d'accord avec l'affirmation suivante : "Le coût d'une alimentation saine est un obstacle pour maintenir une diète équilibrée"</label>
        <div class="options">
            <input type="radio" id="q3_option1" name="question3" value="Tout à fait d'accord" required>
            <label for="q3_option1">Tout à fait d'accord</label><br>
            <input type="radio" id="q3_option2" name="question3" value="Plutôt d'accord">
            <label for="q3_option2">Plutôt d'accord</label><br>
            <input type="radio" id="q3_option3" name="question3" value="Neutre">
            <label for="q3_option3">Neutre</label><br>
            <input type="radio" id="q3_option4" name="question3" value="Plutôt en désaccord">
            <label for="q3_option4">Plutôt en désaccord</label><br>
            <input type="radio" id="q3_option5" name="question3" value="Pas du tout d'accord">
            <label for="q3_option5">Pas du tout d'accord</label><br>
        </div>
    </div>
</div>

    <!-- Page 3 -->
<div class="questionnaire-section">
    <!-- Question 4 -->
    <div class="question">
        <label for="question4">Question 4: Accordez-vous de l'importance à la bienveillance des autres dans votre vie quotidienne ?</label>
        <div class="options">
            <input type="radio" id="q4_option1" name="question4" value="Oui" required>
            <label for="q4_option1">Oui</label><br>
            <input type="radio" id="q4_option2" name="question4" value="Non">
            <label for="q4_option2">Non</label><br>
        </div>
    </div>

    <!-- Question 5 -->
    <div class="question">
        <label for="question5">Question 5: Envisagez-vous d'avoir des enfants et de les scolariser dans le pays en question ?</label>
        <div class="options">
            <input type="radio" id="q5_option1" name="question5" value="Oui" required>
            <label for="q5_option1">Oui</label><br>
            <input type="radio" id="q5_option2" name="question5" value="Non">
            <label for="q5_option2">Non</label><br>
            <input type="radio" id="q5_option3" name="question5" value="Ne sait pas">
            <label for="q5_option3">Ne sait pas</label><br>
        </div>
    </div>
</div>


            <!-- Page 4 -->
            <div class="questionnaire-section">
    <!-- Question 6 -->
    <div class="question">
        <label for="question6">Question 6: Quelle sensibilité présentez-vous concernant les changements de température ?</label>
        <div class="options">
            <input type="radio" id="q6_option1" name="question6" value="Très sensible" required>
            <label for="q6_option1">Très sensible</label><br>
            <input type="radio" id="q6_option2" name="question6" value="Sensible">
            <label for="q6_option2">Sensible</label><br>
            <input type="radio" id="q6_option3" name="question6" value="Moyen">
            <label for="q6_option3">Moyen</label><br>
            <input type="radio" id="q6_option4" name="question6" value="Peu sensible">
            <label for="q6_option4">Peu sensible</label><br>
            <input type="radio" id="q6_option5" name="question6" value="Pas du tout sensible">
            <label for="q6_option5">Pas du tout sensible</label><br>
        </div>
    </div>

    <!-- Question 7 -->
    <div class="question">
        <label for="question7">Question 7: Que représente la religion pour vous en termes d'importance ?</label>
        <div class="options">
            <input type="radio" id="q7_option1" name="question7" value="Important" required>
            <label for="q7_option1">Important</label><br>
            <input type="radio" id="q7_option2" name="question7" value="Plutôt important">
            <label for="q7_option2">Plutôt important</label><br>
            <input type="radio" id="q7_option3" name="question7" value="Plutôt pas important">
            <label for="q7_option3">Plutôt pas important</label><br>
            <input type="radio" id="q7_option4" name="question7" value="Pas important">
            <label for="q7_option4">Pas important</label><br>
            <input type="radio" id="q7_option5" name="question7" value="Ne sais pas">
            <label for="q7_option5">Ne sais pas</label><br>
            <input type="radio" id="q7_option6" name="question7" value="Ne se prononce pas">
            <label for="q7_option6">Ne se prononce pas</label><br>
        </div>
    </div>
</div>

<!-- Page 5 -->
<div class="questionnaire-section">
    <!-- Question 8 -->
    <div class="question">
        <label for="question8">Question 8: Quelles sont vos attentes concernant les conditions de santé d'un pays ?</label>
        <div class="options">
            <input type="radio" id="q8_option1" name="question8" value="Faible mortalité estimée" required>
            <label for="q8_option1">Faible mortalité estimée</label><br>
            <input type="radio" id="q8_option2" name="question8" value="Fort taux de natalité">
            <label for="q8_option2">Fort taux de natalité</label><br>
            <input type="radio" id="q8_option3" name="question8" value="Espérance de vie élevée">
            <label for="q8_option3">Espérance de vie élevée</label><br>
            <input type="radio" id="q8_option4" name="question8" value="Peu m'importe">
            <label for="q8_option4">Peu m'importe</label><br>
        </div>
    </div>

    <!-- Question 9 -->
    <div class="question">
        <label for="question9">Question 9: À quel point est-il important pour vous que votre pays d'accueil soit ouvert aux visiteurs et échanges internationaux ?</label>
        <div class="options">
            <input type="radio" id="q9_option1" name="question9" value="Très important" required>
            <label for="q9_option1">Très important</label><br>
            <input type="radio" id="q9_option2" name="question9" value="Important">
            <label for="q9_option2">Important</label><br>
            <input type="radio" id="q9_option3" name="question9" value="Neutre">
            <label for="q9_option3">Neutre</label><br>
            <input type="radio" id="q9_option4" name="question9" value="Peu important">
            <label for="q9_option4">Peu important</label><br>
            <input type="radio" id="q9_option5" name="question9" value="Pas du tout important">
            <label for="q9_option5">Pas du tout important</label><br>
        </div>
    </div>

    <!-- Question 10 -->
    <div class="question">
        <label for="question10">Question 10: À quel point est-il important pour vous de se déplacer à l'aide des transports en commun ?</label>
        <div class="options">
            <input type="radio" id="q10_option1" name="question10" value="Très important" required>
            <label for="q10_option1">Très important</label><br>
            <input type="radio" id="q10_option2" name="question10" value="Important">
            <label for="q10_option2">Important</label><br>
            <input type="radio" id="q10_option3" name="question10" value="Neutre">
            <label for="q10_option3">Neutre</label><br>
            <input type="radio" id="q10_option4" name="question10" value="Peu important">
            <label for="q10_option4">Peu important</label><br>
            <input type="radio" id="q10_option5" name="question10" value="Pas du tout important">
            <label for="q10_option5">Pas du tout important</label><br>
        </div>
    </div>
</div>


<!-- Page 6 -->
<div class="questionnaire-section">
    <!-- Question 11 -->
    <div class="question">
        <label for="question11">Question 11: À quel point est-il important pour vous le taux de chômage du pays qui vous accueille ?</label>
        <div class="options">
            <input type="radio" id="q11_option1" name="question11" value="Très important" required>
            <label for="q11_option1">Très important</label><br>
            <input type="radio" id="q11_option2" name="question11" value="Important">
            <label for="q11_option2">Important</label><br>
            <input type="radio" id="q11_option3" name="question11" value="Neutre">
            <label for="q11_option3">Neutre</label><br>
            <input type="radio" id="q11_option4" name="question11" value="Peu important">
            <label for="q11_option4">Peu important</label><br>
            <input type="radio" id="q11_option5" name="question11" value="Pas du tout important">
            <label for="q11_option5">Pas du tout important</label><br>
        </div>
    </div>

    <!-- Question 12 -->
    <div class="question">
        <label for="question12">Question 12: Quelles sont vos priorités en matière de conditions sociales dans un pays ?</label>
        <div class="options">
            <input type="radio" id="q12_option1" name="question12" value="Revenu élevé" required>
            <label for="q12_option1">Revenu élevé</label><br>
            <input type="radio" id="q12_option2" name="question12" value="Salaire horaire compétitif">
            <label for="q12_option2">Salaire horaire compétitif</label><br>
            <input type="radio" id="q12_option3" name="question12" value="Accès universel à l'électricité">
            <label for="q12_option3">Accès universel à l'électricité</label><br>
            <input type="radio" id="q12_option4" name="question12" value="Peu importe">
            <label for="q12_option4">Peu importe</label><br>
        </div>
    </div>

    <!-- Question 13 -->
    <div class="question">
        <label for="question13">Question 13: Quelle sensibilité présentez-vous concernant les changements de température ?</label>
        <div class="options">
            <input type="radio" id="q13_option1" name="question13" value="Très sensible" required>
            <label for="q13_option1">Très sensible</label><br>
            <input type="radio" id="q13_option2" name="question13" value="Sensible">
            <label for="q13_option2">Sensible</label><br>
            <input type="radio" id="q13_option3" name="question13" value="Moyen">
            <label for="q13_option3">Moyen</label><br>
            <input type="radio" id="q13_option4" name="question13" value="Peu sensible">
            <label for="q13_option4">Peu sensible</label><br>
            <input type="radio" id="q13_option5" name="question13" value="Pas du tout sensible">
            <label for="q13_option5">Pas du tout sensible</label><br>
        </div>
    </div>
</div>

        <!-- Page 7 -->
        <div class="questionnaire-section">

    <!-- Question 14 -->
    <div class="question">
        <label for="question14">Question 14: Que représente la religion pour vous en termes d'importance ?</label>
        <div class="options">
            <input type="radio" id="q14_option1" name="question14" value="Important" required>
            <label for="q14_option1">Important</label><br>
            <input type="radio" id="q14_option2" name="question14" value="Plutôt important">
            <label for="q14_option2">Plutôt important</label><br>
            <input type="radio" id="q14_option3" name="question14" value="Plutôt pas important">
            <label for="q14_option3">Plutôt pas important</label><br>
            <input type="radio" id="q14_option4" name="question14" value="Pas important">
            <label for="q14_option4">Pas important</label><br>
            <input type="radio" id="q14_option5" name="question14" value="Ne sais pas">
            <label for="q14_option5">Ne sais pas</label><br>
            <input type="radio" id="q14_option6" name="question14" value="Ne se prononce pas">
            <label for="q14_option6">Ne se prononce pas</label><br>
        </div>
    </div>

    <!-- Question 15 -->
    <div class="question">
        <label for="question15">Question 15: Quelles sont vos attentes concernant les conditions de santé d'un pays ?</label>
        <div class="options">
            <input type="radio" id="q15_option1" name="question15" value="Faible mortalité estimée" required>
            <label for="q15_option1">Faible mortalité estimée</label><br>
            <input type="radio" id="q15_option2" name="question15" value="Fort taux de natalité">
            <label for="q15_option2">Fort taux de natalité</label><br>
            <input type="radio" id="q15_option3" name="question15" value="Espérance de vie élevée">
            <label for="q15_option3">Espérance de vie élevée</label><br>
            <input type="radio" id="q15_option4" name="question15" value="Peu m'importe">
            <label for="q15_option4">Peu m'importe</label><br>
        </div>
    </div>

    <!-- Question 16 -->
    <div class="question">
        <label for="question16">Question 16: Quelles sont vos priorités en matière de conditions sociales dans un pays ?</label>
        <div class="options">
            <input type="radio" id="q16_option1" name="question16" value="Revenu élevé" required>
            <label for="q16_option1">Revenu élevé</label><br>
            <input type="radio" id="q16_option2" name="question16" value="Salaire horaire compétitif">
            <label for="q16_option2">Salaire horaire compétitif</label><br>
            <input type="radio" id="q16_option3" name="question16" value="Accès universel à l'électricité">
            <label for="q16_option3">Accès universel à l'électricité</label><br>
            <input type="radio" id="q16_option4" name="question16" value="Peu importe">
            <label for="q16_option4">Peu importe</label><br>
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
        let currentPage = 0;
const sections = document.querySelectorAll(".questionnaire-section"); // Select all pages
const stepContainer = document.getElementById("stepContainer");

function createStepIndicators() {
    sections.forEach((_, index) => {
        const step = document.createElement("div");
        step.classList.add("step");
        step.textContent = index + 1; // Label each step with a page number
        step.addEventListener("click", () => goToPage(index)); // Make each step clickable to navigate pages
        stepContainer.appendChild(step); // Append the step to the step container
    });
    updateStepIndicator(currentPage);
}

function updateStepIndicator(pageIndex) {
    const steps = document.querySelectorAll(".step");
    steps.forEach((step, index) => {
        step.classList.toggle("active", index === pageIndex); // Highlight the active step
        const completionStatus = getPageCompletionStatus(index);
        step.classList.remove("green", "yellow", "red"); // Remove any previous status classes
        step.classList.add(completionStatus); // Add new status class based on the number of answers
    });
}

function getPageCompletionStatus(pageIndex) {
    const section = sections[pageIndex];
    const inputs = section.querySelectorAll("input[type='radio']");
    const totalQuestions = section.querySelectorAll(".question").length;
    const answeredQuestions = Array.from(inputs).filter(input => input.checked).length;

    if (answeredQuestions === 0) {
        return "red"; // No questions answered
    } else if (answeredQuestions === totalQuestions) {
        return "green"; // All questions answered
    } else {
        return "yellow"; // Some questions answered
    }
}

function showPage(pageIndex) {
    sections.forEach((section, index) => {
        section.classList.toggle("active", index === pageIndex); // Display only the current page
    });
    document.getElementById("submitSection").style.display = pageIndex === sections.length - 1 ? "block" : "none"; // Show submit button on last page
    updateStepIndicator(pageIndex);
}

function changePage(step) {
    currentPage += step;
    if (currentPage >= 0 && currentPage < sections.length) {
        showPage(currentPage); // Update page based on step
    }
}

function goToPage(pageIndex) {
    currentPage = pageIndex;
    showPage(currentPage); // Navigate directly to the specified page
}

// Initialize form display
createStepIndicators(); // Create navigation steps based on number of pages
showPage(currentPage); // Show the first page on load

// Add event listeners to radio buttons to update step indicators when a question is answered
document.querySelectorAll("input[type='radio']").forEach(radio => {
    radio.addEventListener("change", () => updateStepIndicator(currentPage));
});

    </script>
</body>
</html>
