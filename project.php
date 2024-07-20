<?php
ini_set('auto_detect_line_endings', true); // Fine linea MAC

require_once 'data/CLASSE_Utility.php';
use LMWebDev\Utility as UT;

/**
 * @var string JSON con dati sito web
 */
$dataFile = 'data/data.json';

/**
 * @var array dati sito web
 */
$dataArray = (array)json_decode(file_get_contents($dataFile));

/**
 * @var array dati statici comuni a tutte le pagine
 */
$staticDataArray = (array)$dataArray['static'];

/**
 * @var array dati di dettagli dei progetti
 */
$detailedProjectsDataArray = (array)$dataArray['detailedProjects'];

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

// In caso di mancanza di query-string all'interno dell'URL, riporta alla pagina portfolio.php
if (isset($_GET) && $_GET['id'] != null) {
    $idProgetto = UT::richiestaHTTP('id');

    // In caso di idProgetto non presente in detailedProjects (PLACEHOLDER), riporta alla pagina portfolio.php
    if ($idProgetto > count($detailedProjectsDataArray)) {
        header("refresh:0;url=portfolio.php");
        die;
    }
} else {
    header("refresh:0;url=portfolio.php");
    die;
}
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
        <?php
        // Ciclo array progetti per trovare il progetto richiesto dalla query-string
        foreach ($detailedProjectsDataArray as $project) {
            if ($project->id == $idProgetto) {
                $projectArray = (array)$project;
                break;
            }
        }
        ?>
        <div class="work-container">
            <div class="title-bar">
                <h1><?php echo $projectArray['name']; ?></h1>
                <h3><?php echo $projectArray['title'] ?></h3>
                <a href="portfolio.php" title="Portfolio">Torna al Portfolio</a>
            </div>

            <!-- STAMPA GALLERIA ANTEPRIMA da data.json -> detailedProjects -->
            <?php
            $galleryArray = $projectArray['gallery'];
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

            <!-- STAMPA DESCRIZIONE PROGETTO da data.json -> detailedProjects -->
            <?php
            $descArray = (array)$projectArray['description'];
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
                $linkStr = '<div class="link-row">';
                $linkStr .= sprintf('<a href="%s" id="project-link" title="%s">Visita il sito</a>', $descArray['url'], $projectArray['name']);
                $linkStr .= '</div>';
                echo $linkStr;
            }
            ?>
        </div>

        <div class="other">
            <!-- STAMPA TITOLO -->
            <h1><?php echo $projectArray['other']->title; ?></h1>
            <div class="flex-other">
                <?php
                // STAMPA CARD PER ALTRI PROGETTI SIMILI da data.json -> detailedProjects
                $otherArray = (array)$projectArray['other']->sub;
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