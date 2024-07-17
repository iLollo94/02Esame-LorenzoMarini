<?php
require_once 'data/CLASSE_Utility.php';

use LMWebDev\Utility as UT;

/**
 * @var file con dati comuni a tutte le pagine
 * 
*/
$staticDataFile = 'data/static.json';

/**
 * @var array contenente dati comuni a tutte le pagine
 * 
*/
$staticDataArray = (array)json_decode(file_get_contents($staticDataFile));

/**
 * @var file con dati pagina progetto Megacorp
 * 
*/
$megacorpDataFile = 'data/megacorp.json';

/**
 * @var array con dati pagina progetto Megacorp
 * 
*/
$megacorpDataArray = (array)json_decode(file_get_contents($megacorpDataFile));

/**
 * @var string titolo sito web
 * 
*/
$title = 'Lorenzo Marini WEB DEV';

/**
 * @var array con link files(s) CSS
 * 
*/
$css = array('scss/css/style.min', 'scss/css/project.min');
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <?php
    // STAMPA HEAD
    $headStr = UT::generateHead($title, $css);
    echo $headStr;
    ?>
</head>

<body>
    <header>
        <?php
        // STAMPA HEADER
        $headerStr = UT::generaHeader($staticDataArray);
        echo $headerStr;
        ?>
    </header>

    <main>
        <div class="work-container">
            <div class="title-bar">
                <h1><?php echo $megacorpDataArray['title']; ?></h1>
                <a href="portfolio.php" title="Portfolio">Torna al Portfolio</a>
            </div>

            <!-- STAMPA GALLERIA ANTEPRIMA da megacorp.json -->
            <?php
            $galleryArray = $megacorpDataArray['gallery'];
            // APERTURA DIV PROJECT-PREVIEW (Carosello immagini)
            $galleryStr = '<div class="project-preview">';
            foreach ($galleryArray as $card) {
                // Apertura DIV IMAGE-CARD
                $galleryStr .= '<div class="image-card">';
                // STAMPA IMMAGINE PREVIEW
                $galleryStr .= sprintf('<div class="image"><img src="%s" alt="%s" title="%s"></div>', $card->image, $card->imagealt, $card->description);
                // STAMPA DESCRIZIONE
                $galleryStr .= sprintf('<div class="description"><p>%s</p></div>', $card->description);
                // CHIUSURA DIV
                $galleryStr .= '</div>';
            }
            // CHIUSURA DIV
            $galleryStr .= '</div>';
            echo $galleryStr;
            ?>

            <!-- STAMPA DESCRIZIONE PROGETTO da megacorp.json -->
            <?php
            $descArray = (array)$megacorpDataArray['description'];
            // APERTURA DIV PROJECT-DESC
            $descStr = '<div class="project-desc">';
            // STAMPA TITOLO E DESCRIZIONE GENERALE
            $descStr .= sprintf('<h2>%s</h2><p>%s</p>', $descArray['title'], $descArray['text']);
            // STAMPO LISTA E CICLO $descArray['sub'] PER STAMPARE SOTTOTITOLI E DESCRIZIONI PARTI
            $descStr .= '<ul>';
            foreach ($descArray['sub'] as $sub) {
                $descStr .= sprintf('<li><h3>%s</h3><p>%s</p></li>', $sub->subtitle, $sub->text);
            }
            // CHIUSURA LISTA E DIV PROJECT-DESC
            $descStr .= '</ul></div>';
            echo $descStr;
            ?>

            <!-- STAMPA LINK AL SITO (se disponibile in json) -->
            <?php
            if ($descArray['url'] != "") {
                $linkStr = sprintf('<a href="%s" id="project-link" title="%s">Visita il sito</a>', $descArray['url'], $megacorpDataArray['name']);
                echo $linkStr;
            }
            ?>
        </div>

        <div class="other">
            <!-- STAMPA TITOLO -->
            <h1><?php echo $megacorpDataArray['other']->title; ?></h1>
            <div class="flex-other">
                <?php
                // STAMPA CARD PER ALTRI PROGETTI SIMILI da megacorp.json
                $otherArray = (array)$megacorpDataArray['other']->sub;
                $otherStr = '';
                foreach ($otherArray as $card) {
                    // APERTURA DIV CARD
                    $otherStr .= '<div class="card">';
                    // STAMPA IMMAGINE PREVIEW
                    $otherStr .= sprintf('<div class="preview"><a href="%s" title="%s"><img src="%s" alt="%s" title="%s"></a></div>', $card->url, $card->title, $card->image, $card->name, $card->title);
                    // STAMPA TESTO DESCRIZIONE
                    $otherStr .= sprintf('<div class="text"><h4>%s</h4><p>%s</p><p>%s</p></div>', $card->title, $card->description, $card->type);
                    // STAMPA DATA
                    $otherStr .= sprintf('<div class="date">%s</div>', $card->date);
                    // CHIUSURA DIV
                    $otherStr .= '</div>';
                }
                echo $otherStr;
                ?>
            </div>
        </div>
    </main>

    <footer>
    <?php
		// STAMPA FOOTER
		$footerStr = UT::generaFooter($staticDataArray);
		echo $footerStr;
		?>
    </footer>
</body>

</html>
