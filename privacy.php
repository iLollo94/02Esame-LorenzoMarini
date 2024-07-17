<?php
require_once 'data/CLASSE_Utility.php';

use LMWebDev\Utility as UT;

$staticDataFile = 'data/static.json';
$staticDataArray = (array)json_decode(file_get_contents($staticDataFile));

$privacyTermsDataFile = 'data/privacyTerms.json';
$privacyTermsDataArray = (array)json_decode(file_get_contents($privacyTermsDataFile));

$title = 'Lorenzo Marini WEB DEV';
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
            $privacyArray = (array)$privacyTermsDataArray['privacy'];

            // Stampa testo privacy
            $privacyStr = sprintf('<h1>%s</h1><p>%s</p>', $privacyArray['title'], $privacyArray['text']);
            foreach ($privacyArray['sub'] as $subContent) {
                $privacyStr .= sprintf('<h2>%s</h2><p>%s</p>', $subContent->subtitle, $subContent->text);
            }
            $privacyStr .= sprintf('<p class="edit-date">Ultimo aggiornamento: %s</p>', $privacyArray['date']);
            echo $privacyStr;
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