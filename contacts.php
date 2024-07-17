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
 * @var file con dati pagina contatti
 * 
*/
$contactsDataFile = 'data/contacts.json';

/**
 * @var array con dati pagina contatti
 * 
*/
$contactsDataArray = (array)json_decode(file_get_contents($contactsDataFile));

/**
 * @var string titolo sito web
 * 
*/
$title = 'Lorenzo Marini WEB DEV';

/**
 * @var array con link files(s) CSS
 * 
*/
$css = array('scss/css/style.min', 'scss/css/contacts.min');

/**
 * @var string conferma invio modulo
 * 
*/
$inviato = UT::richiestaHTTP('inviato');
$inviato = ($inviato == null) ? false : true;

// VALIDAZIONE FORM
if ($inviato) {
    $firstName = UT::richiestaHTTP('firstName');
    $lastName = UT::richiestaHTTP('lastName');
    $email = UT::richiestaHTTP('email');
    $phone = UT::richiestaHTTP('phone');
    $request = UT::richiestaHTTP('request');
    $message = UT::richiestaHTTP('message');

    $validate = 0; // Contatore errori

    // Validazione campi
    // Validazione NOME
    if (($firstName == "") || !UT::ctrlLunghezzaStringa($lastName, 0, 32)) {
        $validate++;
        $firstName = "";
    }

    // Validazione COGNOME
    if (($lastName == "") || !UT::ctrlLunghezzaStringa($lastName, 0, 32)) {
        $validate++;
        $lastName = "";
    }

    // Validazione EMAIL
    if (($email == "") || !UT::ctrlLunghezzaStringa($email, 6, 100) || (filter_var($email, FILTER_VALIDATE_EMAIL) == false)) {
        $validate++;
        $email = "";
    }

    // Validazione TELEFONO
    if (($phone == "") || !UT::ctrlLunghezzaStringa($phone, 6, 32)) {
        $validate++;
        $phone = "";
    }

    // Validazione MESSAGGIO
    if (($message == "") || !UT::ctrlLunghezzaStringa($message, 10, 300)) {
        $validate++;
        $message = "";
    }

    $inviato = ($validate == 0) ? true : false;
} else {
    $firstName = "";
    $lastName = "";
    $email = "";
    $phone = "";
    $message = "";
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
        <div class="container">
            <div class="box contact-box">
                <?php
                $recapiti = $contactsDataArray['recapiti'];
                // print_r($recapiti);
                $recapitiStr = '';
                foreach ($recapiti as $group) {
                    // APRO DIV GRUPPO RECAPITI
                    $recapitiStr .= sprintf('<div class="%s">', $group->class);
                    // STAMPA TITOLO
                    $recapitiStr .= sprintf('<h3>%s</h3>', $group->title);
                    // CON IF DIFFEREZIO TRA ARRAY CON LINK A CONTATTI/SOCIAL E STRINGA INDIRIZZO
                    if (is_array($group->content)) {
                        // STAMPA LISTA LINK
                        $recapitiStr .= '<ul>';
                        foreach ($group->content as $line) {
                            $recapitiStr .= sprintf('<li><a href="%s" title="%s">%s</a></li>', $line->url, $line->name, $line->text);
                        }
                        $recapitiStr .= '</ul>';
                    } else {
                        // STAMPA STRINGA INDIRIZZO
                        $recapitiStr .= sprintf('<address><p>%s</p></address>', $group->content);
                    }
                    // CHIUDO DIV GRUPPO RECAPITI
                    $recapitiStr .= '</div>';
                }
                echo $recapitiStr;
                ?>

                <div class="map">
			        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d45600.19474726633!2d12.159309708483734!3d44.41239595702793!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x477df9505a24da17%3A0x2a74a33c9c54a776!2sRavenna%20RA!5e0!3m2!1sit!2sit!4v1715759193390!5m2!1sit!2sit" width="350" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <div class="box form">
                <?php
                $formArray = $contactsDataArray['form'];
                if (!$inviato) {
                    // APRO FORM
                    $formStr = '<form action="?inviato=1" method="post" novalidate>';
                    // APRO FIELDSET E STAMPO LEGEND
                    $formStr .= '<fieldset><legend><h3>Compila il form qui sotto e verrai ricontattato</h3></legend>';
                    // CICLO ARRAY DA contacts.json PER STAMPARE GLI INPUT
                    foreach ($formArray as $input) {
                        // STAMPA LABEL
                        $formStr .= sprintf('<label for="%s">%s</label>', $input->cssId, $input->label);
                        // STAMPA INPUT DIFFERENZIANDO TRA SELECT, TEXTAREA E TEXT/TEL
                        if ($input->type == 'select') {
                            // STAMPA SELECT
                            $formStr .= sprintf('<select name="%s" id="%s" required>', $input->name, $input->cssId);
                            // CICLO ARRAY sub INTERNO A SELECT PER STAMPA OPZIONI
                            if (isset($input->sub) && is_array($input->sub)) {
                                foreach ($input->sub as $option) {
                                    if (isset($option->label)) {
                                        $formStr .= sprintf('<option label="%s" selected disabled></option>', $option->label);
                                    } else {
                                        $formStr .= sprintf('<option value="%s">%s</option>', $option->value, $option->text);
                                    }
                                }
                            }
                            $formStr .= '</select>';
                        } elseif ($input->type == 'textarea') {
                            // STAMPA TEXTAREA
                            $formStr .= sprintf('<textarea name="%s" id="%s" placeholder="%s" minlength="%u" maxlength="%u" required></textarea>', $input->name, $input->cssId, $input->placeholder, $input->minlength, $input->maxlength);
                        } else {
                            // STAMPA INPUT TYPE TEXT/TEL
                            $formStr .= sprintf('<input type="%s" name="%s" id="%s" placeholder="%s" minlength="%u" maxlength="%u" required>', $input->type, $input->name, $input->cssId, $input->placeholder, $input->minlength, $input->maxlength);
                        }
                    }
                    // STAMPA CHECKBOX PRIVACY
                    $formStr .= '<input type="checkbox" name="privacy" id="privacy" required>';
                    $formStr .= '<label for="privacy">Ho letto l\'<a href="privacy.php" title="Leggi l\'informatica privacy">informativa privacy</a> e presto il mio consenso.</label>';
                    // STAMPA BOTTONI
                    $formStr .= '<div class="buttons"><input type="submit" id="submit" value="INVIA"><input type="reset" id ="reset" value="RESET"></div>';
                    // CHIUSURA FORM
                    $formStr .= '</fieldset></form>';
                    echo $formStr;
                    // STAMPA MESSAGGIO ERRORE IN CASO DI ERRORE NEI CAMPI
                    if (UT::richiestaHTTP('inviato') == 1 && $validate != 0) {
                        echo '<p class="error">Compilare tutti i campi correttamente</p>';
                    }
                } else {
                    switch ($request) {
                        case ("website"):
                            $request = "Sviluppo Sito Web";
                            break;
                        case ("webapp"):
                            $request = "Sviluppo Web Application";
                            break;
                        case ("server"):
                            $request = "Sviluppo Web Server o Database";
                            break;
                        case ("maintenance"):
                            $request = "Risoluzione problemi o modifiche";
                            break;
                        case ("consul"):
                            $request = "Consulenza";
                            break;
                        case ("info"):
                            $request = "Informazioni";
                            break;
                    }

                    $str = "<strong>Nome:</strong> %s<br>" .
                           "<strong>Cognome:</strong> %s<br>" .
                           "<strong>Email:</strong> %s<br>" .
                           "<strong>Telefono:</strong> %s<br>" .
                           "<strong>Richiesta:</strong> %s<br>" .
                           "<strong>Messaggio:</strong> %s<br>";
                    
                    $str = sprintf($str, $firstName, $lastName, $email, $phone, $request, $message);

                    echo ("<h3>Grazie per il suo messaggio</h2>Ecco il riepilogo del messaggio: <br>" . $str);

                    $str = str_replace("<br>", chr(10), $str);
                    $str = str_replace("<strong>", "", $str);
                    $str = str_replace("</strong>", "", $str);

                    $messagesFile = 'uploads/receivedContacts.txt';

                    $str = chr(10) . str_repeat("-", 30) . chr(10) . $str . str_repeat("-", 30);
                    $rit = UT::fileInsert($messagesFile, $str);

                    if ($rit) {
                        echo "<br>" . str_repeat("-", 30) . "<br> Modulo inviato correttamente<br><br>";
                        echo '<a href="contacts.php" title="Contatti">Aggiorna la pagina</a>';
                    } else {
                        echo "<br>" . str_repeat("-", 30) . "<br> Errore nell'invio del modulo<br><br>";
                        echo '<a href="contacts.php" title="Contatti">Aggiorna la pagina</a>';
                    }
                }
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
