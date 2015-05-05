<?php
session_start();
$_DELIGHT['CTRID']  = 9;
include "./load.delight.php";
include "./lib/pagination.class.php";

switch($APP->ID)
{
    case 31:
        $v = new viestit();
        $navi = new pagination();
        $kansiot = $v->haeKayttajanKansiot();

        if ($_GET["set"] == "important") $v->asetaTarkeaksi(1);
        if ($_GET["set"] == "nimportant") $v->asetaTarkeaksi(0);
        if ($_GET["set"] == "trash") $v->siirraRoskakoriin();
        if ($_POST["hidMoveNotes"] == "1") $v->siirraViestit();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'viestit.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Viestit";
        $APP->PAGEVARS[HEADERTEXT] = "Viestit";
        $APP->PAGEVARS[BREADCRUMB] = "Viestit";
        break;


    case 32: // Uusi viesti
        $v = new viestit();
        if ($_POST["hidSendNote"] == 1)
        {
            $aMsg = $v->lahetaViesti();
        }

        $APP->VIEWPARTS = array('header-main.tpl.php', 'uusi-viesti.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Uusi viesti";
        $APP->PAGEVARS[HEADERTEXT] = "Uusi viesti";
        $APP->PAGEVARS[BREADCRUMB] = "Uusi viesti";
        break;


    case 33:
        $v = new viestit();
        if ($_POST["hidNewFolder"] == 1) $aMsg = $v->luoKansio();
        
        $APP->VIEWPARTS = array('header-main.tpl.php', 'uusi-kansio.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Uusi kansio";
        $APP->PAGEVARS[HEADERTEXT] = "Uusi kansio";
        $APP->PAGEVARS[BREADCRUMB] = "Uusi kansio";
        break;


    case 34:
	$v = new viestit();
        $kayttajat = $v->haeKayttajat();
	$APP->VIEWPARTS = array('kayttaja-search.tpl.php');
        $APP->PAGEVARS[TITLE] = "Salasanatyypit";
        $APP->PAGEVARS[HEADERTEXT] = "Salasanatyypit";
        $APP->PAGEVARS[BREADCRUMB] = "Salasanatyypit";
        break;


    case 35:
        $v = new viestit();
        $h = new hallinta();
        $viesti = $v->haeViesti($_GET["note_id"]);
        $lahettaja = $v->haeKayttaja($viesti["user_id"]);
        $APP->VIEWPARTS = array('header-main.tpl.php', 'lue-viesti.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Lue viesti";
        $APP->PAGEVARS[HEADERTEXT] = "Lue viesti";
        $APP->PAGEVARS[BREADCRUMB] = "Lue viesti";
        break;

    case 36:
        $v = new viestit();
        if ($_POST["hidSmsSend"] == 1) $v->sendSms();
        $tekstarit = $v->haeViimeisimmatSmst();

        $APP->VIEWPARTS = array('header-main.tpl.php', 'sms.tpl.php', 'footer-main.tpl.php');
        $APP->PAGEVARS[TITLE] = "Lähetä tekstiviesti";
        $APP->PAGEVARS[HEADERTEXT] = "Lähetä tekstiviesti";
        $APP->PAGEVARS[BREADCRUMB] = "Lähetä tekstiviesti";
        break;

    case 37:
	$v = new viestit();
        $kayttajat = $v->haeKayttajatSms();
        
	$APP->VIEWPARTS = array('kayttaja-search-sms.tpl.php');
        $APP->PAGEVARS[TITLE] = "Salasanatyypit";
        $APP->PAGEVARS[HEADERTEXT] = "Salasanatyypit";
        $APP->PAGEVARS[BREADCRUMB] = "Salasanatyypit";
        break;

    default:
    break;
} //End of switch statement

include "./load.view.php";
?>
