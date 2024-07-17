<?php
require_once 'data/CLASSE_Utility.php';

use LMWebDev\Utility as UT;

$staticDataFile = 'data/static.json';
$staticDataArray = (array)json_decode(file_get_contents($staticDataFile));

$portfolioDataFile = 'data/portfolio.json';
$portfolioDataArray = (array)json_decode(file_get_contents($portfolioDataFile));

$title = 'Lorenzo Marini WEB DEV';
$css = array('scss/css/style.min', 'scss/css/portfolio.min');
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
        <h1>Portfolio</h1>

        <!-- Stampa container portfolio e cards-->
        <?php
        $portfolioStr = '<div class="flex-portfolio">';
        foreach ($portfolioDataArray['cards'] as $card) {
            // Apertura DIV CARD
            $portfolioStr .= sprintf('<div class="card %s %s">', $card->class, $card->mobileHideClass);
            // Stampa immagine preview e link
            $portfolioStr .= sprintf('<div class="preview"><a href="%s" title="%s"><img src="%s" alt="%s" title="%s"></a></div>', $card->url, $card->title, $card->image, $card->title, $card->title);
            // Stampa testo descrittivo e data
            $portfolioStr .= sprintf('<div class="text"><h4>%s</h4><p>%s</p><p>%s</p></div>', $card->title, $card->description, $card->type);
            $portfolioStr .= sprintf('<div class="date"><p>%s</p></div>', $card->date);
            // Chiusura DIV CARD
            $portfolioStr .= '</div>';
        }
        // Chiusura DIV FLEX-PORTFOLIO
        $portfolioStr .= '</div>';
        echo $portfolioStr;
        ?>
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