<?php session_start(); ?>
<header>
    <div class="menu-bar">
    <div class="menu-item">
    <?php
        if (isset($_SESSION['client'])) {
            echo '<a href="/datavizio/questionnaire.php">';
        } else {
            echo '<a href="/connexion/login.php">';
        }
        ?>
        <img src="/images/images_ced/icone1.png" alt="Icone Questionnaire">
        </a>
        <p>Questionnaire</p>
    </div>
    <div class="menu-item">
    <a href="/graph.php"><img src="/images/images_ced/icone2.png" alt="Icone Statistiques & Graphs" ></a>
        <p>Statistiques & Graphs</p>
    </div>
    <div class="menu-item">
    <a href="/forum/forum.php"><img src="/images/images_ced/icone3.png" alt="Forum"></a>
        <p>Forum</p>
    </div>
    <div class="menu-item logo">
    <a href="/index.php"><img src="/images/images_ced/logo.png" alt="Logo"></a>
        
    </div>
    <div class="menu-item">
    <a href="/informations/informations.php"><img src="/images/images_ced/icone4.png" alt="Icone Informations"></a>
        <p>Informations</p>
    </div>
    <div class="menu-item">
    <a href="/informations/sources.php"><img src="/images/images_ced/icone5.png" alt="Icone Sources données"></a>
        <p>Sources données</p>
    </div>
    <div class="menu-item">
    <a href="/utilisateur/profil.php"><img src="/images/images_ced/icone6.png" alt="Icone Options"></a>
        <p>Profil</p>
    </div>
    </header>
    <link rel="icon" type="image/png" href="/images/logo_ico.png">