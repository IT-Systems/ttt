<?php
session_start(); 
$_DELIGHT['CTRID']  = 5;
include "./load.delight.php";

switch($APP->ID)
{ 	
    case 13: //Lennot (oletus)
	$len = new lennot();
	$session_id = session_id();
				
	if (!isset($_GET['s']) && empty($_REQUEST["showDate"])) {
            $paiva = date("Y-m-d");
            $paiva_print = date("d.m.Y");
	}
        elseif (isset($_GET['s']) AND $_GET['s'] == '1') {
            $paiva = $_POST['paiva'];
            $paiva = date('Y-m-d', strtotime($paiva .' -1 day'));
            $paiva_explode = explode('-', $paiva);
            $paiva_print = $paiva_explode[2] . "." . $paiva_explode[1] . "." . $paiva_explode[0];
	}
	elseif (isset($_GET['s']) AND $_GET['s'] == '2') {
            $paiva = $_POST['paiva'];
            $paiva = date('Y-m-d', strtotime($paiva .' +1 day'));
            $paiva_explode = explode('-', $paiva);
            $paiva_print = $paiva_explode[2] . "." . $paiva_explode[1] . "." . $paiva_explode[0];
	}
        elseif (!empty($_REQUEST["showDate"])) {
            $ak = new aikakalu();
            $paiva = $ak->fiDateToDbDate($_REQUEST["showDate"]);
            $paiva_print = $_REQUEST["showDate"];
        }

        if (isset($_GET["print"])) {
            $lennot = $len->haeOmatLennot($_GET["date"]);
            $APP->VIEWPARTS = array('lennot-print.tpl.php');
        }
        else {
            $lennot = $len->haeOmatLennot($paiva);
            $APP->VIEWPARTS = array('header-main.tpl.php', 'lennot.tpl.php', 'footer-main.tpl.php');
        }
					
        break;
	
    case 14: //Uusi lento
        $len = new lennot();
        if ($_POST["hidAddStatus"] == 1) {
            $aMsg = $len->tallennaLento();
        }

        $koneet = $len->haeIlmaAlus();
        $toim_laadut = $len->haeLennonToiminnanLaadut();
        $henkilot = $len->haeHenkilot('', '1,2,3');
        $opettajat = $len->haeHenkilot('', '1,2');
        $oppilaat = $len->haeHenkilot('', '3');
        $kustannuspaikat = $len->haeKustannuspaikat();
        $syllabukset = $len->haeSyllabukset();
        $lentokentat = $len->haeLentokentat();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'lento-uusi.tpl.php', 'footer-main.tpl.php');
        break;

    case 51: // Lennon poistaminen
        $len = new lennot();
        $len->poistaLento();
        break;

    case 52: // Lennon muokkaaminen
        $len = new lennot();
        $ak = new aikakalu();
        if ($_POST["hidAddStatus"] == 1) {
            $aMsg = $len->tallennaLento();
        }

        $koneet = $len->haeIlmaAlus();
        $toim_laadut = $len->haeLennonToiminnanLaadut();
        $henkilot = $len->haeHenkilot('', '1,2,3');
        $opettajat = $len->haeHenkilot('', '1,2');
        $oppilaat = $len->haeHenkilot('', '3');
        $kustannuspaikat = $len->haeKustannuspaikat();
        $syllabukset = $len->haeSyllabukset();
        $lentokentat = $len->haeLentokentat();
        
        $lento = (!empty($_GET["lento_id"])) ? $len->haeLennot($_GET["lento_id"]) : $len->haeLennot($_POST["lento_id"]);

        $APP->VIEWPARTS = array('header-main.tpl.php', 'lento-muokkaa.tpl.php', 'footer-main.tpl.php');
        break;


    case 53:
        $len = new lennot();
        $ak = new aikakalu();
        $hakupage = ($_GET["mode"] == "simple" || !isset($_GET["mode"])) ? 'lento-etsi.tpl.php' : 'lento-etsi-laaja.tpl.php';
        $lennot = '';
        
        if ($_POST["hidSimpleSearch"] == 1) {
            $lennot = $len->haeLennotSimple();
        }
        elseif ($_POST["hidExtendedSearch"] == 1) {
            $lennot = $len->haeLennotExtended();
        }

        $kustannuspaikat = $len->haeKustannuspaikat();
        $koneet = $len->haeIlmaAlus();
        $konetyypit = $len->haeKoneTyypit();
        $valvojat = $len->haeHenkilot('', '1,2');
        $henkilot = $len->haeHenkilot('', '1,2,3');

        $APP->VIEWPARTS = array('header-main.tpl.php', $hakupage, 'footer-main.tpl.php');
        break;


    case 69: // Harjoitusten listaus
        $len = new lennot();
        $ak = new aikakalu();
        $harjoitukset = $len->haeHarjoitukset();
        $APP->VIEWPARTS = array('header-main.tpl.php', 'lento-harjoitukset.tpl.php', 'footer-main.tpl.php');
        break;


    case 61: // Harjoituksen perustaminen / muokkaus.
        $len = new lennot();
        $ak = new aikakalu();

        if ($_POST["hidAddExercise"] == 1) {
            $aMsg = $len->tallennaHarjoitus();
        }

        $henkilot = $len->haeHenkilot('', '1,2,3');
        $opettajat = $len->haeHenkilot('', '1,2,3');
        $kustannuspaikat = $len->haeKustannuspaikat();
        $syllabukset = $len->haeSyllabukset();
        if ($_GET["harjoitusId"]) $harjoitus = $len->haeHarjoitukset('','',$_GET["harjoitusId"]);
                                
        $APP->VIEWPARTS = array('header-main.tpl.php', 'lento-harjoitus-muokkaa.tpl.php', 'footer-main.tpl.php');
        break;

    case 70:
        $len = new lennot();
        $len->poistaHarjoitus();
        break;

    case 62:
        $len = new lennot();
        $ak = new aikakalu();
        if ($_POST["hidAddStatus"] == 1) {
            $aMsg = $len->tallennaPeruutusLento();
        }
        $viewpage = (empty($_GET["mode"])) ? 'lento-peruutetut.tpl.php' : 'lento-peruutetut-listaus.tpl.php';

        if ($_GET["mode"] == "list") {
            $lennot = $len->haePeruutetutLennot();
        }
        else {
            $koneet = $len->haeIlmaAlus();
            $henkilot = $len->haeHenkilot('', '1,2,3');
            $toim_laadut = $len->haeLennonToiminnanLaadut();
            $kustannuspaikat = $len->haeKustannuspaikat();
            $syyt = $len->haeSyyt();
        }

        $APP->VIEWPARTS = array('header-main.tpl.php', $viewpage , 'footer-main.tpl.php');
        break;

    case 64: // Peruutetun lennon muokkaaminen
        $len = new lennot();
        $ak = new aikakalu();

        if ($_POST["hidAddStatus"] == 1) {
            $aMsg = $len->tallennaPeruutusLento();
        }
        $lento = (!empty($_GET["plento_id"])) ? $len->haePeruutetutLennot($_GET["plento_id"]) : $len->haePeruutetutLennot($_POST["plento_id"]);

        $koneet = $len->haeIlmaAlus();
        $henkilot = $len->haeHenkilot('', '2,3');
        $toim_laadut = $len->haeLennonToiminnanLaadut();
        $kustannuspaikat = $len->haeKustannuspaikat();
        $syyt = $len->haeSyyt();

        $APP->VIEWPARTS = array('header-main.tpl.php','lento-peruutetut-muokkaa.tpl.php', 'footer-main.tpl.php');
        break;


    case 65: // Peruutetun lennon poistaminen
        $len = new lennot();
        $len->poistaPeruutettuLento();
        break;

    case 68:
        $len = new lennot();
        $harjoitukset = $len->haeSyllabuksenHarjoitukset();
        $APP->VIEWPARTS = array('lento-harjoitushaku.tpl.php');
        break;

    case 76:
        $len = new lennot();
        $rooli = $len->haeKayttajanRooli();
        print $rooli;
        break;

    default:
        break;
} //End of switch statement
include "./load.view.php";
?>
