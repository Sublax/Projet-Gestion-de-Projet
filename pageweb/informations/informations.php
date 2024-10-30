<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations</title>
    <link rel="stylesheet" href="../styles/style_ced.css">
</head>
<header>
<div class="menu-bar">
    <div class="menu-item">
        <img src="../images/images_ced/icone1.png" alt="Icone Questionnaire">
        <p>Questionnaire</p>
    </div>
    <div class="menu-item">
        <img src="../images/images_ced/icone2.png" alt="Icone Statistiques & Graphs">
        <p>Statistiques & Graphs</p>
    </div>
    <div class="menu-item logo">
        <img src="../images/images_ced/icone3.png" alt="Logo">
    </div>
    <div class="menu-item">
        <img src="../images/images_ced/icone4.png" alt="Icone Informations">
        <p>Informations</p>
    </div>
    <div class="menu-item">
        <img src="../images/images_ced/icone5.png" alt="Icone Sources données">
        <p>Sources données</p>
    </div>
    <div class="menu-item">
        <img src="../images/images_ced/icone6.png" alt="Icone Options">
        <p>Options</p>
    </div>
</div>
</header>
<body>

<main>
    <?php
    $rss_url = 'https://www.courrierinternational.com/feed/rubrique/geopolitique/rss.xml';
    $rss_content = simplexml_load_file($rss_url);

    if ($rss_content === false) {
        echo "<p>Erreur lors du chargement du flux RSS.</p>";
    } else {
        echo '<div class="articles-grid">';
        $excerpt_length = 100;

        foreach ($rss_content->channel->item as $item) {
            $title = $item->title;
            $link = $item->link;
            $description = $item->description;
            $excerpt = substr($description, 0, $excerpt_length) . '...'; // Crée un extrait de texte
            $pubDate = date('d M Y', strtotime($item->pubDate));
        
            // Code pour récupérer l'image
            $namespace_media = $item->children('media', true);
            $image_url = '';
            if ($namespace_media && $namespace_media->content) {
                $image_url = $namespace_media->content->attributes()->url;
            } elseif ($item->enclosure) {
                $image_url = $item->enclosure->attributes()->url;
            }
        
            echo '<div class="article-card">';
            if ($image_url) {
                echo '<img src="' . $image_url . '" alt="Image de l\'article" class="article-image">';
            }
            echo '<div class="article-content">';
            echo '<h2><a href="' . $link . '" target="_blank">' . $title . '</a></h2>';
            echo '<p class="description">' . $excerpt . '</p>';
            echo '<a href="' . $link . '" target="_blank" class="read-more">Lire la suite</a>';
            echo '<p class="pubDate">' . $pubDate . '</p>';
            echo '</div></div>';
        }
    }
    ?>
</main>
<footer>
    <p>&copy; 2024 Payspédia. Tous droits réservés.</p>
</footer>
</body>
</html>
