<?php
namespace LMWebDev;

/**
 * Classe che raggruppa tutti i metodi utili per la compilazione e il funzionamento dei sito LmWebDev
 * 
 * @author Lorenzo Marini
 * @copyright 2024
*/
class Utility {
    /**
     * Metodo richiestaHTTP
     * Metodo per estrarre la propietà richiesta da $_POST o $_GET
     * 
     * @param string -- proprietà da ricercare
     * @return string|null
     * 
    */
    public static function richiestaHTTP($str) {
        $rit = null;
        if ($str !== null) {
            if (isset($_POST[$str])) {
                $rit = $_POST[$str];
            } elseif (isset($_GET[$str])) {
                $rit = $_GET[$str];
            }
        }
        return $rit;
    }
    // Fine metodo

    /**
     * Metodo fileInsert
     * Scrive la stringa ricevuta dal form
     * all'interno del file $file.
     * Se $file non esiste viene creato.
     * Metodo fopen "a"
     * 
     * @param string -- $file Nome file
     * @param string -- $text Testo da inserire
     * @param bool -- $commenta Scrive a video se l'operazione è andata a buon fine
     * @return bool
     * 
    */
    public static function fileInsert($file, $text, $commenta = false) {
        $rit = false;
        if (!$fp = fopen($file, 'a')) {
            echo "Errore di comunicazione con il server<br>";
        } else {
            if (is_writable($file) === false) {
                echo "Errore di scrittura nel server<br>";
            } else {
                if (!fwrite($fp, $text)) {
                    echo "Si è verificato un errore. Riprovare :(";
                } else {
                    if ($commenta) echo "Il tuo messaggio è stato inserito nel server!";
                    $rit = true;
                }
            }
        }
        fclose($fp);
        return $rit;
    }
    // Fine metodo

    /**
     * Metodo ctrlLunghezzaStringa
     * Controlla che la stringa abbia un numero di caratteri interno ad un dato range
     * @param string -- $str Stringa da controllare
     * @param int -- $min Valore di lunghezza minimo
     * @param int -- $max Valore di lunghezza massimo
     * @return bool
    */
    public static function ctrlLunghezzaStringa ($str, $min = null, $max = null) {
        $rit = 0;
        $n = strlen($str);

        if ($min != null && $n < $min) {
            $rit++;
        }

        if ($max =! null && $n > $max) {
            $rit++;
        }

        return ($rit == 0);
    }
    // Fine metodo

    /**
     * generateHead()
     * Stampa l'head html
     * Per CSS multipli passare $css[] di tipo array
     * Per CSS singolo passare $css di tipo string
     * 
     * @param string $title titolo del documento
     * @param array $css array contentente nomi file(s) CSS (senza .css)
     * @return string
    */
    public static function generateHead(string $title, array $css) {
        $str = '';
        $str .= '<meta charset="UTF-8">';
        $str .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $str .= sprintf('<meta name="description" content="%s">', $title);
        $str .= '<link rel="icon" type="image/x-icon" href="img/favicon.ico">';
        $str .= '<link rel="preconnect" href="https://fonts.googleapis.com">';
        $str .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        $str .= '<link href="https://fonts.googleapis.com/css2?family=Reddit+Mono:wght@200..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">';
        $str .= sprintf('<title>%s</title>', $title);
        foreach ($css as $file) {
            $str.= sprintf('<link rel="stylesheet" href="%s.css">', $file);
        }

        return $str;
    }
    // Fine metodo

    /**
     * Funzione leggiTesto
     * Legge testo all'interno di un file
     * 
     * @param string -- $file Nome del file
     * @return boolean|string
     * 
    */
    public static function leggiTesto($file) {
        $rit = false;
        $length = (filesize($file) != 0) ? filesize($file) : 1 ;
        if (!$fp = fopen($file, 'r')) {
            echo "Non posso aprile il file";
        } else {
            if (is_readable($file) === false) {
                echo "Il file non è leggibile";
            } else {
                $rit = fread($fp, $length);
            }
        }

        fclose($fp);
        return $rit;
    }
    // Fine metodo

    /**
     * Funzione generaHeader
     * Stampa header in HTML
     * 
     * @param array $staticDataArray array con dati comuni a tutte le pagine (header, nav, footer)
     * @return string
    */
    public static function generaHeader(array $staticDataArray) {
        // Separo dati header e nav da staticDataArray
        $headerArray = (array)$staticDataArray['header'];
        $navArray = $staticDataArray['nav'];

        // Stampa logo e titolo
        $headerStr = '<div class="flex-header">';
        $headerStr .= '<div class="logo">';
        $headerStr .= sprintf('<a href="index.php" title="Vai alla Homepage"><img src="%s" alt="Logo" title="Lorenzo Marini Web Dev"></a>', $headerArray['logo']);
        $headerStr .= '</div>';
        $headerStr .= '<div class="title">';
        $headerStr .= sprintf('<h1>%s</h1><h2>%s</h2>', $headerArray['title'], $headerArray['subtitle']);
        $headerStr .= '</div>';

        // Stampa menu hamburger
        $headerStr .= '<nav><div class="hamburger-menu">';
        $headerStr .= '<input type="checkbox" title="controllo" id="controllo"><label for="controllo" class="label-controllo"><span></span></label>';
        $headerStr .= '<ul class="menu">';
        foreach ($navArray as $link) {
            $headerStr .= sprintf('<li><a href="%s" title="%s">%s</a></li>', $link->url, $link->title, $link->name);
        }
        $headerStr .= '</ul></div></nav></div>';

        return $headerStr;
    }

    /**
     * Funzione generaFooter
     * Genera HTML footer
     * 
     * @param array $staticDataArray array con dati comuni a tutte le pagine (header, nav, footer)
     * @return string
    */
    public static function generaFooter(array $staticDataArray) {
        // Separo dati footer da array staticDataArray
        $footerArray = (array)$staticDataArray['footer'];
        // Stampa riga recapiti e indirizzi
        $footerStr = '<div class="footer-row">';
        foreach ($footerArray['colonne'] as $col) {
            $footerStr .= sprintf('<div class="%s"><h4>%s</h4>%s</div>', $col->class, $col->title, $col->content);
        }
        $footerStr .= '</div>';
        // Stampa riga link privacy e copyright
        foreach ($footerArray['righe'] as $row) {
            $footerStr .= sprintf('<div class="%s">%s</div>', $row->class, $row->content);
        }

        return $footerStr;
    }
}
// Fine classe

