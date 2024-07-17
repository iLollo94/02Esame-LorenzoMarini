<?php
ini_set("auto_detect_line_endings", true); // Fine linea MAC

require_once 'data/CLASSE_Utility.php';
use LMWebDev\Utility as UT;

/**
 * @var string file con dati comuni a tutte le pagine
 * 
*/
$staticDataFile = 'data/static.json';

/**
 * @var array contenente dati comuni a tutte le pagine
 * 
*/
$staticDataArray = (array)json_decode(file_get_contents($staticDataFile));

/**
 * @var string file con dati pagine privacy e termini
 * 
*/
$privacyTermsDataFile = 'data/privacyTerms.json';

/**
 * @var array con dati pagina privacy e termini
 * 
*/
$privacyTermsDataArray = (array)json_decode(file_get_contents($privacyTermsDataFile));

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
            $termsArray = (array)$privacyTermsDataArray['terms'];
            // print_r($termsArray);

            // Stampa testo Termini e Condizioni
            $termsStr = sprintf('<h1>%s</h1><p>%s</p>', $termsArray['title'], $termsArray['text']);
            foreach ($termsArray['sub'] as $subContent) {
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
            $termsStr .= sprintf('<p class="edit-date">Ultimo aggiornamento: %s</p>', $termsArray['date']);
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
