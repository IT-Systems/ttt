<?php
session_start();
$_DELIGHT['CTRID']  = 10;
include "./load.delight.php";
$ak = new aikakalu();

switch($APP->ID)
{
    case 43: // Uusi työvuoro
        if ($_POST["hidAddStatus"] == 1) {
            $aMsg = $ak->tallennaUlkTyovuoro();
        }
        $timezones = $ak->getTimeZones();
        
        $APP->VIEWPARTS = array('header-main.tpl.php', 'tyoaika.tpl.php', 'footer-main.tpl.php');
        break;

    case 44: // Yhteenveto
        $pvmval = $ak->haeKuukaudetSelect();
        if (!empty($_GET["kk"])) {
           $_POST["kk"] = $_GET["kk"]; // printtiin
        }
        $vuorot = $ak->haeKuukaudenVuorot();

        if (isset($_GET["print"])) {
            $APP->VIEWPARTS = array('tyoaika_yhteenveto-print.tpl.php');
        }
        else {
            $APP->VIEWPARTS = array('header-main.tpl.php', 'tyoaika_yhteenveto.tpl.php', 'footer-main.tpl.php');
        }

        break;

    case 45: // Palkkaerittely
        if (isset($_GET["new"])) {
            if ($_POST["hidAddStatus"] == 1) $aMsg = $ak->tallennaKorvaus();
            $mview = 'tyoaika_uusi_korvaus.tpl.php';
            $lajit = $ak->haeKorvausLajit();
        }
        elseif (isset($_GET["delete"])) {
            $ak->poistaKorvaus();
        }
        else {
            $mview = 'tyoaika_palkkaerittely.tpl.php';
            $pvmval = $ak->haeKuukaudetSelect();
            $korvaukset = $ak->haeHenkilonKorvaukset();
        }

        $APP->VIEWPARTS = array('header-main.tpl.php', $mview, 'footer-main.tpl.php');
        break;

    default:
    break;
} //End of switch statement

include "./load.view.php";
?>
