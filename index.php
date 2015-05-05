<?php
session_start(); 
$_DELIGHT['CTRID']  = 2;
include "./load.delight.php";
/* Move the control to the respective pages according to the Event ID */
switch($APP->ID) {
    case 6: //Case for eventname "Default"
        $etusivu = new etusivu();
        $session_id = session_id();

        $lentovaraus = new lentoVaraus();
        $huoltovaraus = new huoltoVaraus();
        $tilavaraus = new tilaVaraus();
        
        $kaikki_varaukset = array(
            'lentovaraus'   =>    $lentovaraus->haeKayttajanVaraukset($USER->ID),
            'huoltovaraus'  =>    $huoltovaraus->haeKayttajanVaraukset($USER->ID),
            'tilavaraus'    =>    $tilavaraus->haeKayttajanVaraukset($USER->ID)
        );
        
        $aloitusajat = $etusivu->haeAloitusajat();
        $tyovuoro = $etusivu->haeTyovuoro();
        $aika_nyt = $etusivu->haeAikaNyt();

        $tiedotteet = $etusivu->haeTiedotteet(0);
        $luetut_tiedotteet = $etusivu->haeTiedotteet(1);

        $ohjeet = $etusivu->haeOhjeet(0);
        $luetut_ohjeet = $etusivu->haeOhjeet(1);


        $vahvistukset = $etusivu->haeVahvistukset();

        if (count($tyovuoro) > 0) {
            list($dates, $times) = explode(' ', $tyovuoro[0]['aloitus']);
            list($year, $month, $day) = explode('-', $dates);
            list($hour, $min, $sec) = explode(':', $times);

            $vuoro_aloitettu = (int)$day . "." . (int)$month . "." . $year . " klo " . (int)$hour . ":" . $min;
            $vuoro_aloitettu_msg = "Vuoro aloitettu " . $vuoro_aloitettu;
        }
        else {
            $vuoro_aloitettu_msg = "Ei käynnissä olevaa työvuoroa.";
        }

        if (count($tiedotteet) == 0) {
            $msgTiedotteet = "Ei uusia tiedotteita";
        }

        if (count($ohjeet) == 0) {
            $msgOhjeet = "Ei uusia ohjeita";
        }

        if(isset($_POST['aloitavuoro'])) {
            $vuoro_aloitettu_msg = $etusivu->aloitaVuoro();
            header("Location: $_SERVER[PHP_SELF]");
        }

        if(isset($_POST['lopetavuoro'])) {
            $vuoro_aloitettu_msg = $etusivu->lopetaVuoro();
            header("Location: $_SERVER[PHP_SELF]");
        }

        $ilmoitukset = $etusivu->haeIlmoitukset();
        $lennot = new lennot();
        $henkilot = $lennot->haeHenkilot('', '2,3');

        $APP->VIEWPARTS = array('header-main.tpl.php', 'etusivu.tpl.php', 'footer-main.tpl.php');

        break;

    case 7:
        $etusivu = new etusivu();
        session_start();
        $session_id = session_id();
        $etusivu->aloitaVuoro($session_id);
        $APP->VIEWPARTS = array('header-main.tpl.php', 'etusivu.tpl.php', 'footer-main.tpl.php');
        break;

    case 8:
        $etusivu = new etusivu();
        $session_id = session_id();
        $etusivu->lopetaVuoro($session_id);
        $APP->VIEWPARTS = array('header-main.tpl.php', 'etusivu.tpl.php', 'footer-main.tpl.php');
        break;

    case 9:
        $tiedote_id = $_GET['tiedote_id'];
        $etusivu = new etusivu();
        $tiedote = $etusivu->haeTiedote($tiedote_id);
        $APP->VIEWPARTS = array('tiedote.tpl.php');
        break;

    case 42:
        $help = new helper();
        $help->vahvistaOsallistuminen();
        break;

    case 77:
        $etusivu = new etusivu();
        $yhttiedot = $etusivu->haeYhteystiedot();
        $APP->VIEWPARTS = array('yhteystiedot.tpl.php');
        break;

    default:
        break;
} //End of switch statement
include "./load.view.php";
?>
