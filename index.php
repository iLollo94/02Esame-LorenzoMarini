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
 * @var array dati homepage
 */
$homepageDataArray = (array)$dataArray['homepage'];

/**
 * @var string titolo sito web
 * 
*/
$title = 'Lorenzo Marini WEB DEV';

/**
 * @var array con link a file(s) CSS
 * 
*/
$css = array('scss/css/style.min', 'scss/css/homepage.min');
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
		<!-- Container servizi offerti -->
		<div class="serv-container">
			<?php
			$servStr = '';
			// Stampa una card per ogni servizio presente in homepage.json
			foreach ($homepageDataArray['cards'] as $card) {
				// Apro DIV CARD
				$servStr .= '<div class="card">';
				// Stampa IMMAGINE e TESTO
				$servStr .= sprintf('<div class="title"><h1>%s</h1></div>', $card->text);
				$servStr .= sprintf('<div class="image"><img src="%s" alt="%s" title="%s"></div>', $card->image, $card->name, $card->text);
				// Chiudo DIV CARD
				$servStr .= '</div>';
			}
			echo $servStr;
			?>

			<div class="serv-row">
                <a href="portfolio.php" title="Portfolio">Visita il portfolio</a>
                <a href="services.php" title="Servizi">Esplora i Servizi</a>
            </div>
		</div>

		<!-- Container clienti -->
		<div class="clients-container">
			<?php
			// Stampa logo per ogni cliente presente in homepage.json
			foreach ($homepageDataArray['clienti'] as $logo) {			
				$logoStr = '<div class="client-logo">';					
				// Se presente logo cliente stampa immagine, altrimenti stampa placeholder
				if ($logo->image != "") {
					$logoStr .= sprintf('<img src="%s" alt="%s" title="%s">', $logo->image, $logo->name, $logo->title);
				} else {
					$logoStr .= sprintf('<div class="client-placeholder"><h1>%s</h1></div>', $logo->title);
				}
				$logoStr .= '</div>';
				echo $logoStr;
			}
			?>
		</div>

		<!-- Container bio -->
		<?php
		$bioData = $homepageDataArray['bio'][0];
		?>
		<div class="bio-container">
			<div class="title">
				<h1><?php echo $bioData->title; ?></h1>
			</div>

			<div class="self">
				<?php
				$imageStr = sprintf('<img src="%s" alt="%s" title="%s">', $bioData->image, $bioData->imagealt, $bioData->imagetitle);
				echo $imageStr;
				?>
			</div>

			<div class="bio">
				<p>
				<?php
				// Estrae testo biografia da file inserito in homepage.json, convertendo ogni cambiolinea in <br>
				$bioText = nl2br(UT::leggiTesto($bioData->text));
				echo $bioText;
				?>
				</p>
			</div>
		</div>

		<!-- Container linguaggi -->
		<div class="lang-container">
			<?php
			$langStr = '<h2>Linguaggi utilizzati:</h2>';
			$langStr .= '<div class="flex-lang">';
			foreach ($homepageDataArray['linguaggi'] as $lang) {
				$langStr .= '<div class="lang-logo">';
				$langStr .= sprintf('<img src="%s" alt="%s" title="%s">', $lang->image, $lang->name, $lang->name);
				$langStr .= '</div>';
			}
			$langStr .= '</div>';
			echo $langStr;
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
