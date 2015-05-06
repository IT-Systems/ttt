<?php
session_start();
$_DELIGHT['CTRID']  = 8;
include "./load.delight.php";
include "./lib/pagination.class.php";
$hal = new hallinta();

switch($APP->ID) {

    case 23: // Hallinnan etusivu, josta valitaan hallinnoitava kohde.
        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta ";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta ";
        break;


    case 17: //Case for eventname "kayttajat"
        $aUsersDetails = $hal->listPage();
        $pagination = new pagination;

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-kayttajat.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Käyttäjät";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Käyttäjät";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Käyttäjät";
        break;


    case 18: //Case for eventname "kayttajatLisaa"
        if($_POST['hidAddStatus']) {
            $aMsg = $hal->lisaaKayttaja();
        }
        else {
            $aMsg[0] = "Please fill up the following form to create a new user. The <strong>username</strong> and <strong>email</strong> must be unique.";
            $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
        }

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-kayttajat-lisaa.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Lisää käyttäjä";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Lisää käyttäjä";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Lisää käyttäjä";
        break;


    case 19: //Case for eventname "kayttajatMuokkaa"
        $kayttajaID = $_GET['RecID'];

        if($_POST['hidEditStatus']) {
            $aMsg = $hal->muokkaaKayttajaa($kayttajaID);
        }
        else {
            $aMsg[0] = "Please use the following form to edit user details. The <strong>username</strong> and <strong>email</strong> must be unique.";
            $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
        }
        $aEachUsersDetails   = $hal->listEachUser($kayttajaID);

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-kayttajat-muokkaa.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Muokkaa käyttäjää";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Muokkaa käyttäjää";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Muokkaa käyttäjää";
        break;


    case 20: //Case for eventname "kayttajatPoista"
        $kayttajaID = $_GET['RecID'];

        if($kayttajaID) {
            $asiakas = new hallinta();
            $asiakas->kayttajaPoista($kayttajaID);
        }
        
        $APP->PAGEVARS[TITLE] = "Hallinta - Poista käyttäjä";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Poista käyttäjä";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Poista käyttäjä";
        break;


    case 27: // Koneiden listaus
        $koneet = $hal->haeKoneet();
        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-koneet.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Koneet";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Koneet";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Koneet";
        break;


    case 26: // Koneen lisäys
        if ($_POST["hidAddStatus"] == 1) $aMsg = $hal->lisaaKone();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-koneet-lisaa.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Lisää kone";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Lisää kone";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Lisää kone";
        break;


    case 25: // Koneen muokkaus
        if ($_POST["hidEditStatus"] == 1) $hal->lisaaKone($_POST["kone_id"]);
        $kone = $hal->haeKoneet($_GET["kone_id"]);

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-koneet-muokkaa.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Muokkaa kone";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Muokkaa kone";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Muokkaa kone";
        break;


    case 24: // Koneen poisto
        $hal->poistaKone();
        break;


    case 28: // Konetyypit
        if ($_POST["hidEditStatus"] == 1) $hal->tallennaKonetyypit();
        if ($_GET['act'] == 2) $hal->poistaKonetyyppi();
        $konetyypit = $hal->haeKonetyypit();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-konetyypit.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Konetyypit";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Konetyypit";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Konetyypit";
        break;


    case 29: // Kustannuspaikat
        if ($_GET['act'] == 2) $hal->poistaKustannuspaikka();
        if ($_POST["hidEditStatus"] == 1) $hal->tallennaKustannuspaikat();
        $kustannuspaikat = $hal->haeKustannuspaikat();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinat-kustannuspaikat.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Kustannuspaikat";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Kustannuspaikat";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Kustannuspaikat";
        break;


    case 30: // Tilat
        if ($_GET['act'] == 2) $hal->poistaTila();
        if ($_POST["hidEditStatus"] == 1) $hal->tallennaTilat();
        $tilat = $hal->haeTilat();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-tilat.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Tilat";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Tilat";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Tilat";
        break;


    case 38:
        $tiedotteet = $hal->haeTiedotteet();
    
        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-tiedotteet.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Tiedotteet";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Tiedotteet";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Tiedotteet";
        break;

    case 39:
        if ($_POST["hidAddStatus"] == 1) $hal->lisaaTiedote();
        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-tiedotteet-lisaa.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Lisää tiedote";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Lisää tiedote";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Lisää tiedote";
        break;


    case 40:
        if ($_POST["hidEditStatus"] == 1) $hal->tallennaTiedote();
        $tiedote = $hal->haeTiedotteet();
        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-tiedotteet-muokkaa.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Muokkaa tiedotetta";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Muokkaa tiedotetta";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Muokkaa tiedotetta";
        break;


    case 41:
        $hal->poistaTiedote();
        break;


    case 50:
        if ($_GET['act'] == 2) $hal->poistaKelpuutus();
        if ($_POST["hidEditStatus"] == 1) $hal->tallennaKelpuutukset();
        $kelpuutukset = $hal->haeKelpuutukset();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta_kelpuutukset.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Kelpuutukset";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Kelpuutukset";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Kelpuutukset";
        break;


    case 66:
        $syllabukset = $hal->haeSyllabukset();
        if (isset($_POST['syllabus'])) {
            if ($hal->talSyllabukset($_POST['syllabus'])) {
                header('Location: hallinta.php?ID=66&PF=1');
            }
        }
        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-syllabukset.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Syllabukset";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Syllabukset";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Syllabukset";
        break;

    case 67:
        if ($_GET["act"] == "1") {
            $hal->poistaHarjoitus();
        }
        if ($_POST["hidEditStatus"] == "1") {
            $hal->tallennaHarjoitukset();
        }
        if ($_GET["act"] == "moveup" || $_GET["act"] == "movedown") {
            $hal->muutaHarjoitustenJarjestys($_GET["syllabus_id"], $_GET["harjoitus_id"], $_GET["act"]);
        }
        $syllabus = $hal->haeSyllabukset($_GET["syllabus_id"]);
        $harjoitukset = $hal->haeSyllabuksenHarjoitukset($_GET["syllabus_id"]);

        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-syllabukset-harkat.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Harjoitukset ({$syllabus["nimi"]})";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Harjoitukset ({$syllabus["nimi"]})";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Harjoitukset ({$syllabus["nimi"]})";
        break;

    case 71:
        $titadd = "";
        if ($_GET["mode"] == "add" || $_GET["mode"] == "modify") {
            $mView = 'hallinta-kurssit-muokkaa.tpl.php';
            if ($_GET["mode"] == "add") {
                $titadd = " - uusi";
            }
            if ($_GET["mode"] == "modify") {
                $kurssi = $hal->haeKurssit($_GET["kurssiId"]);
                $titadd = " - muokkaa";
            }
        }
        else {
            if ($_GET["mode"] == "save") $hal->tallennaKurssi();
            $kurssit = $hal->haeKurssit();
            $mView = 'hallinta-kurssit.tpl.php';
        }

        $len = new lennot();
        $opettajat = $len->haeHenkilot("", '1,2');
        $oppilaat = $len->haeHenkilot("", '3');
        $syllabukset = $hal->haeSyllabukset();

        $APP->VIEWPARTS = array('header-main.tpl.php', $mView, 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Hallinta - Kurssit" . $titadd;
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Kurssit" . $titadd;
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Kurssit" . $titadd;
        break;


    case 79:
        $len = new lennot();
        $opettajat = $len->haeHenkilot("", '1,2');

        if ($_POST["updateShift"] == 1) {
            $start = $_GET["strFromDate"];
            $to = $_GET["strToDate"];
            $tiitseri = $_GET["teacherId"];
            $hal->paivitaVuoro();
        }
        elseif ($_POST["insertShift"] == 1) {
            $hal->lisaaVuoro();
        }
        elseif ($_GET["addShift"] == 1) {
            $start = $_GET["strFromDate"];
            $to = $_GET["strToDate"];
            $tiitseri = $_GET["teacherId"];
        }
        elseif ((!empty($_POST["teacherId"])) || (!empty($_GET["teacherId"]) && empty($_GET["shiftId"]))) {
            $start = (!empty($_POST["strFromDate"])) ? $_POST["strFromDate"] : $_GET["strFromDate"];
            $to = (!empty($_POST["strToDate"])) ? $_POST["strToDate"] : $_GET["strToDate"];
            $tiitseri = (!empty($_POST["teacherId"])) ? $_POST["teacherId"] : $_GET["teacherId"];
            // $vuorot = $hal->haeTuovuorot("", $tiitseri);
            $ak = new aikakalu();
            $vuorot = $ak->haeKaikkiVuorot($tiitseri, $start, $to);
            if (!$vuorot) $blaah = 1;
        }
        elseif (!empty($_GET["shiftId"])) {
            $start = $_GET["strFromDate"];
            $to = $_GET["strToDate"];
            $tiitseri = $_GET["teacherId"];
            $vuoro = $hal->haeTuovuorot($_GET["shiftId"], "");
        }

        if ($_POST["printPage"] == "1") {
            $ak = new aikakalu();
            $vuorot = $ak->haeKaikkiVuorot($_POST["intUser"],$_POST["strFromDate"],$_POST["strToDate"]);
            $APP->VIEWPARTS = array("hallinta-tyoajat-print.tpl.php");
        }
        else {
            $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-tyoajat.tpl.php', 'footer-main.tpl.php');
        }
        
        $APP->PAGEVARS[TITLE] = "Hallinta - Työvuorot";
        $APP->PAGEVARS[HEADERTEXT] = "Hallinta - Työvuorot";
        $APP->PAGEVARS[BREADCRUMB] = "Hallinta - Työvuorot";
        break;

    case 80:
        $Hallinta = new hallinta();
        if (isset($_POST['qualities'])) {
            if ($hal->talToiminnanLaadut($_POST['qualities'])) {
                header('Location: hallinta.php?ID=80&PF=1');
            }
        }        
        $laadut = $Hallinta->haeToiminnanLaadut();
        $APP->VIEWPARTS = array('header-main.tpl.php', 'hallinta-toiminnan-laadut.tpl.php', 'footer-main.tpl.php');
        break;

    default:
        break;

} //End of switch statement
include "./load.view.php";
?>