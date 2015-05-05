<?php

/**
 * Varauksen prosessoinnissa tapahtuvat virheet. 
 * 
 * <pre>
 * Koodi    Virhe
 * -------------------
 * 201      Tietokannassa päällekkäinen varaus.
 * 202      Virhe aliluokan tietojen kustomoidussa setterissä.
 * 203      Virhe varauksen normaalien tietojen tallennuksessa.
 * 301      Kaikkia rajoittavia tietoja ei ole määritelty.
 * 302      Kaikkia aikoja ei ole määritelty.
 * </pre>
 */
class VarausException extends Exception {

    /**
     * <pre>
     * Koodi    Virhe
     * -------------------
     * 201      Tietokannassa päällekkäinen varaus.
     * 202      Virhe aliluokan tietojen kustomoidussa setterissä.
     * 203      Virhe varauksen normaalien tietojen tallennuksessa.
     * 301      Kaikkia rajoittavia tietoja ei ole määritelty.
     * 302      Kaikkia aikoja ei ole määritelty.
     * </pre>
     * @param int $code virhekoodi
     * @param VarausException $previous edellinen virhe (optional)
     */
    public function __construct($code, $message = '') {
        switch ($code) {
            case 302:
                $message = 'Seuraavia aikoja ei ole määritelty: ' . implode(', ', $message);
                break;
            case 301:
                $message = 'Kaikkia rajoittavia tietoja ei ole määritelty.';
                break;
            case 203:
                $message = 'Virhe varauksen normaalien tietojen tallennuksessa: ' . $message;
                break;
            case 202:
                $message = 'Virhe aliluokan kustomoidussa setterissä: ' . $message;
                break;
            case 201:
                $message = 'Tietokannassa on päällekkäinen varaus.';
                break;
        }
        parent::__construct($message, $code);
    }

}

?>
