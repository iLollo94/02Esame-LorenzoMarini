<?php
ini_set("auto_detect_line_endings", true); // Fine linea MAC

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
 * @var array dati pagina servizi
 */
$servicesDataArray = (array)$dataArray['services'];

/**
 * @var string titolo sito web
 * 
*/
$title = 'Lorenzo Marini WEB DEV';

/**
 * @var array con link files(s) CSS
 * 
*/
$css = array('scss/css/style.min', 'scss/css/services.min');
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
        <div class="serv-container">
            <?php
            $servCardStr = '';
            foreach ($servicesDataArray['cards'] as $card) {
                $front = (array)$card->front[0];
                $back = (array)$card->back[0];
                // APRO DIV FLIP-CARD (Corpo della card) e DIV FLIP-CARD-INNER (container di posizionamento front e back)
                $servCardStr .= '<div class="flip-card"><div class="flip-card-inner">';
                // APRO DIV CARD-FACE CARD-FRONT (Faccia frontale della card)
                $servCardStr .= '<div class="card-face card-front">';
                // STAMPA FRONT
                $servCardStr .= sprintf('<div class="title"><h1>%s</h1></div><div class="image"><img src="%s" alt="%s" title="%s"></div>', $front['text'], $front['image'], $front['imagealt'], $front['text']);
                // CHIUDO DIV CARD-FACE CARD-FRONT E APRO DIV CARD-FACE CARD-BACK (con lista)
                $servCardStr .= '</div><div class="card-face card-back"><ul>';
                foreach ($back as $key => $line) {
                    $servCardStr .= sprintf('<li><p>%s</p></li>', $line);
                }
                // CHIUDO LISTA, DIV CARD-FACE CARD-BACK, DIV FLIP-CARD-INNER e DIV FLIP-CARD
                $servCardStr .= '</ul></div></div></div>';
            }
            echo $servCardStr;
            ?>
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
