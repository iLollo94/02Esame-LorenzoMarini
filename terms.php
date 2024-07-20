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
 * @var array dati termini e condizioni
 */
$termsDataArray = (array)$dataArray['terms'];

/**
 * @var string titolo sito web
 * 
*/
$title = 'Lorenzo Marini WEB DEV';

/**
 * @var array con link files(s) CSS
 * 
*/
$css = array('scss/css/style.min', 'scss/css/privacyTerms.min');
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
        <div class="terms-container">
            <?php
            // Stampa testo Termini e Condizioni
            $termsStr = sprintf('<h1>%s</h1><p>%s</p>', $termsDataArray['title'], $termsDataArray['text']);
            foreach ($termsDataArray['sub'] as $subContent) {
                $termsStr .= sprintf('<h2>%s</h2>', $subContent->subtitle);
                // Differenzio 'text' tra array e stringa, stampando arrai come liste ordinate e stringhe come paragrafi
                if (is_array($subContent->text)) {
                    $listItems = (array)$subContent->text['0'];
                    $termsStr .= '<ol>';
                    foreach ($listItems as $key => $item) {
                        $termsStr .= sprintf('<li><p>%s</p></li>', $item);
                    }
                    $termsStr .= '</ol>';
                } else {
                    $termsStr .= sprintf('<p>%s</p>', $subContent->text);
                }
            }
            $termsStr .= sprintf('<p class="edit-date">Ultimo aggiornamento: %s</p>', $termsDataArray['date']);
            echo $termsStr;
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
