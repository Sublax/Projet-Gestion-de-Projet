<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Questionnaire</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        h2{
            justify-self: center;
            margin-top: 80px;
        }

        p{
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
            background-color: #4CAF50; /* Active color */
        }
        .step.completed {
            background-color: #4CAF50; /* Green for completed */
        }
        .step.incomplete {
            background-color: #e53935; /* Red for incomplete */
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
            echo '<a href="connexion/login.php">';
        }
        ?>
        <img src="images/images_ced/icone1.png" alt="Icone Questionnaire">
        </a>
        <p>Questionnaire</p>
    </div>
    <div class="menu-item">
    <a href="graph.php"><img src="images/images_ced/icone2.png" alt="Icone Statistiques & Graphs"></a>
        <p>Statistiques & Graphs</p>
    </div>
    <div class="menu-item">
    <a href="forum/forum.php"><img src="images/images_ced/icone7.png" alt="Forum"></a>
       <p>Forum</p>
   </div>
    <div class="menu-item logo">
    <a href="index.php"><img src="images/images_ced/icone3.png" alt="Logo"></a>
        
    </div>
    <div class="menu-item">
    <a href="informations/informations.php"><img src="images/images_ced/icone4.png" alt="Icone Informations"></a>
        <p>Informations</p>
    </div>
    <div class="menu-item">
    <a href="informations/sources.php"><img src="images/images_ced/icone5.png" alt="Icone Sources données"></a>
        <p>Sources données</p>
    </div>
    <div class="menu-item">
    <a href="profil.php"><img src="images/images_ced/icone6.png" alt="Icone Options"></a>
        <p>Profil</p>
    </div>
    </header>

    <div class="container">
        <h2 id="form_beginning">Début du questionnaire</h2>
        <p id="form_beginning">Le temps de réponse est d'environ x minutes.</p>
        <form action="graphs_stats.php" method="POST" id="questionnaireForm">
            <!-- Page 1 -->
            <div class="questionnaire-section active">
                <div class="question">
                    <label for="question1">Question 1: Quel est votre niveau de satisfaction ?</label>
                    <div class="options">
                        <input type="radio" id="q1_option1" name="question1" value="Très insatisfait" required>
                        <label for="q1_option1">Très insatisfait</label><br>
                        <input type="radio" id="q1_option2" name="question1" value="Insatisfait">
                        <label for="q1_option2">Insatisfait</label><br>
                        <input type="radio" id="q1_option3" name="question1" value="Neutre">
                        <label for="q1_option3">Neutre</label><br>
                        <input type="radio" id="q1_option4" name="question1" value="Satisfait">
                        <label for="q1_option4">Satisfait</label><br>
                        <input type="radio" id="q1_option5" name="question1" value="Très satisfait">
                        <label for="q1_option5">Très satisfait</label><br>
                    </div>
                </div>
            </div>

            <!-- Page 2 -->
            <div class="questionnaire-section">
                <div class="question">
                    <label for="question2">Question 2: Quelle est votre fréquence de voyage ?</label>
                    <div class="options">
                        <input type="radio" id="q2_option1" name="question2" value="Jamais" required>
                        <label for="q2_option1">Jamais</label><br>
                        <input type="radio" id="q2_option2" name="question2" value="Rarement">
                        <label for="q2_option2">Rarement</label><br>
                        <input type="radio" id="q2_option3" name="question2" value="Parfois">
                        <label for="q2_option3">Parfois</label><br>
                        <input type="radio" id="q2_option4" name="question2" value="Souvent">
                        <label for="q2_option4">Souvent</label><br>
                        <input type="radio" id="q2_option5" name="question2" value="Très souvent">
                        <label for="q2_option5">Très souvent</label><br>
                    </div>
                </div>
            </div>

            <!-- Step indicator -->
            <div class="step-container" id="stepContainer">
                <!-- JavaScript will populate this -->
            </div>
            
            <!-- Submit Button (hidden by default) -->
            <div id="submitSection" style="display: none;">
                <input type="submit" value="Envoyer" class="gradient-button">
            </div>
        </form>
    </div>

    <script>
        let currentPage = 0;
        const sections = document.querySelectorAll(".questionnaire-section");
        const stepContainer = document.getElementById("stepContainer");

        function createStepIndicators() {
            sections.forEach((_, index) => {
                const step = document.createElement("div");
                step.classList.add("step");
                step.textContent = index + 1;
                step.addEventListener("click", () => goToPage(index)); // Makes bubbles clickable
                stepContainer.appendChild(step);
            });
            updateStepIndicator(currentPage);
        }

        function updateStepIndicator(pageIndex) {
            const steps = document.querySelectorAll(".step");
            steps.forEach((step, index) => {
                step.classList.toggle("active", index === pageIndex);
                if (isQuestionAnswered(index)) {
                    step.classList.add("completed");
                    step.classList.remove("incomplete");
                } else {
                    step.classList.add("incomplete");
                    step.classList.remove("completed");
                }
            });
        }

        function isQuestionAnswered(pageIndex) {
            const section = sections[pageIndex];
            const inputs = section.querySelectorAll("input[type='radio']");
            return Array.from(inputs).some(input => input.checked);
        }

        function showPage(pageIndex) {
            sections.forEach((section, index) => {
                section.classList.toggle("active", index === pageIndex);
            });
            document.getElementById("submitSection").style.display = pageIndex === sections.length - 1 ? "block" : "none";
            updateStepIndicator(pageIndex);
        }

        function changePage(step) {
            currentPage += step;
            showPage(currentPage);
        }

        function goToPage(pageIndex) {
            currentPage = pageIndex;
            showPage(currentPage);
        }

        // Initialize form display
        createStepIndicators();
        showPage(currentPage);

        // Add event listeners to radio buttons to update step indicator when answered
        document.querySelectorAll("input[type='radio']").forEach(radio => {
            radio.addEventListener("change", () => updateStepIndicator(currentPage));
        });
    </script>
</body>
</html>