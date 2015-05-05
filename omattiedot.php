<?php
session_start(); 
$_DELIGHT['CTRID']  = 2;
include "./load.delight.php";
include "./lib/pagination.class.php";

switch($APP->ID) {

    case 10: //Case for eventname "Default"
        $ot = new omattiedot();
        $session_id = session_id();
        $omat_tiedot = $ot->haeOmatTiedot();
        $kustannuspaikat = $ot->haeKustannuspaikat();
        $syllabukset = $ot->haeSyllabukset();

        // Omien tietojen tallennuskutsut
        if (isset($_POST['hidEditStatus']) AND $_POST['hidEditStatus'] == '1') $ot->tallennaYhteystiedot();
        if (isset($_POST['hidEditStatus']) AND $_POST['hidEditStatus'] == '2') $ot->tallennaMuutTiedot();
        if ($_POST['hidEditStatus'] === '3') $ot->tallennaKuva();
        if (isset($_POST['hidEditStatus']) AND $_POST['hidEditStatus'] == '4') $ot->tallennaSyllabus();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'omattiedot.tpl.php', 'footer-main.tpl.php');

        break;


    case 46:
        $ot = new omattiedot();
        if ($_POST["hidAddStatus"] == 1) $ot->vaihdaSalasana();
        $APP->VIEWPARTS = array('header-main.tpl.php', 'omattiedot_salis.tpl.php', 'footer-main.tpl.php');
        break;


    case 47:
        $ot = new omattiedot();
        $kokemukset = $ot->haeLentojenKokemukset();
        if ($_POST["hidAddStatus"] == 1) {
            $aMsg = $ot->tallennaKokemusMuutokset($kokemukset);
            /*if (!is_array($aMsg)) {
                $aMsg = $ot->tallennaKonetyyppiKokemukset();
            }*/
            if (!is_array($aMsg)) {
                $aMsg[0] = "Omat lentokokemustiedot päivitettiin.";
                $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
                $aMsg[2] = "ui-icon ui-icon-info";
            }
        }

        $len = new lennot();
        $ak = new aikakalu();

        $konetyypit = $len->haeKoneTyypit();
        $muutokset = $ot->haeMuutoksetKokemuksiin();

        $kokonaislentokokemus = $ak->muunnaMinuutitAjaksi($kokemukset["kokonais"] + $muutokset["kokonais"]);
        $ifrkokemus = $ak->muunnaMinuutitAjaksi($kokemukset["ifr"] - $muutokset["ifr"]);
        $yokokemus = $ak->muunnaMinuutitAjaksi($kokemukset["yo"] - $muutokset["yo"]);
        $simkokemus = $ak->muunnaMinuutitAjaksi($kokemukset["sim"] - $muutokset["sim"]);
        $pickokemus = $ak->muunnaMinuutitAjaksi($kokemukset["pic"] - $muutokset["pic"]);
        $copkokemus = $ak->muunnaMinuutitAjaksi($kokemukset["cop"] - $muutokset["cop"]);
        $dualkokemus = $ak->muunnaMinuutitAjaksi($kokemukset["dual"] - $muutokset["dual"]);
        $opettajakokemus = $ak->muunnaMinuutitAjaksi($kokemukset["teach"] - $muutokset["teach"]);
        $lahtojapaivallakokemus = $kokemukset["toffs_day"] - $muutokset["toffs_day"];
        $lahtojayollakokemus = $kokemukset["toffs_night"] - $muutokset["toffs_night"];
        $laskujapaivallakokemus = $kokemukset["lands_day"] - $muutokset["lands_day"];
        $laskujayollakokemus = $kokemukset["lands_night"] - $muutokset["lands_night"];
        $valvontakokemus = $kokemukset["supervisor"] - $muutokset["supervisor"];

        $konetyyppikokemukset = $ot->haeKonetyyppiKokemukset($konetyypit);

        $APP->VIEWPARTS = array('header-main.tpl.php', 'omattiedot_lentokokemus.tpl.php', 'footer-main.tpl.php');
        break;


    case 48:
        $ot = new omattiedot();
        if ($_GET["act"] == 3) $ot->poistaKayttajanKelpuutus();
        if ($_POST["hidAddStatus"] == '1') $ot->lisaaKelpuutusKayttajalle();
        $kayt_kelpt = $ot->haeKayttajanKelpuutukset();
        
        $APP->VIEWPARTS = array('header-main.tpl.php', 'omattiedot_kelpuutukset.tpl.php', 'footer-main.tpl.php');
        break;


    case 49:
        $ot = new omattiedot();
        $navi = new pagination();
        $users = $ot->haeKayttajat();
        $APP->VIEWPARTS = array('header-main.tpl.php', 'omattiedot_kayttajat.tpl.php', 'footer-main.tpl.php');
        break;

    case 72:
        $len = new lennot();
        $ot = new omattiedot();
        $oppilaat = $len->haeHenkilot("", 3);

        $APP->VIEWPARTS = array('header-main.tpl.php', 'omattiedot-lentoseuranta.tpl.php', 'footer-main.tpl.php');
        break;
    
    default:
        break;
} //End of switch statement
include "./load.view.php";
?>
