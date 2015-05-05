<?php
session_start(); 
$_DELIGHT['CTRID']  = 6;
include "load.delight.php";
$vk = new varauskirja();
$hallinta = new hallinta();
/* Move the control to the respective pages according to the Event ID */
switch($APP->ID) {

    case 15:
        $APP->PAGEVARS[TITLE] = "Varauskirja";
        $APP->VIEWPARTS = array('header-main.tpl.php', 'varauskirja_pt_napit.tpl.php', 'varauskirja_pt_etusivu.tpl.php', 'footer-main.tpl.php');
    break;

    case 54:
        $APP->PAGEVARS[TITLE] = "Tilastot";
        $APP->VIEWPARTS = array('header-main.tpl.php', 'tilastot.tpl.php', 'footer-main.tpl.php');
    break;

    case 56:
        $APP->PAGEVARS[TITLE] = "Varauskirja - suunnittelutila";
        $suunnittelutila = true;
        $APP->VIEWPARTS = array('header-main.tpl.php', 'varauskirja_pt_napit.tpl.php', 'varauskirja_pt_etusivu.tpl.php', 'footer-main.tpl.php');
    break;


    case 57: // tallennaVarus
        if ($_GET['poista'] == '1') { // Admin ja staffi voi poistaa lentovarauksen
            if (in_array($USER->iRole, array(1,2))) {
                $lv = new lentoVaraus((int)$_GET['varaus_id']);
                $lv->poista();
                header('Location: varauskirja.php?ID=56');
            }
        }
        $post_ajat = array('alku-pvm' => $_POST['alkuaika-pvm'], 'alku-aika' => $_POST['alkuaika-aika'], 'loppu-pvm' => $_POST['loppuaika-pvm'], 'loppu-aika' => $_POST['loppuaika-aika']);
        // Tarkistetaan, onko joku ajoista tyhjä. Jos on, heitetään virhe
        try {
            $errors = array();
            foreach ($post_ajat as $key => $val) {
                if (empty($val)) {
                    $errors[] = $key;
                }
            }
            if (!empty($errors)) {
                throw new VarausException(302, $errors);
            }
        } catch (VarausException $aika_virhe) {
            echo $aika_virhe->getCode();
        }
        // Jos ajoissa ei ole virheitä
        if (!$aika_virhe) {
            $alkuaika = $post_ajat['alku-pvm'] . ' ' . $post_ajat['alku-aika'];
            $loppuaika = $post_ajat['loppu-pvm'] . ' ' . $post_ajat['loppu-aika'];

            switch ($_POST['varaus_tyyppi']) {
                case 'lento':
                    for ($i = 0; $i < 6; $i++) {
                        $miehisto[$i] = $_POST['miehisto_' . $i];
                    }
                    $tiedot = array(
                        'kustannuspaikka' => (int) $_POST['kustannuspaikka'],
                        'kone' => (int) $_POST['kone'],
                        'miehisto' => $miehisto,
                        'valvoja' => (int) $_POST['valvoja'],
                        'ilma_aika' => $_POST['ilma_aika'],
                        'lisatieto' => $_POST['lisatieto'],
                        'tarkennukset' => $_POST['tarkennukset'],
                        'tekstiviesti' => (int) $_POST['tekstiviesti']
                    );
                    $luokka = 'lentoVaraus';
                    break;
                case 'huolto':
                    $tiedot = array(
                        'kone' => (int) $_POST['kone'],
                        'vastuuhenkilo' => (int) $_POST['vastuuhenkilo'],
                        'lisatieto' => $_POST['lisatieto'],
                        'nimike' => (int) $_POST['nimike']
                    );
                    $luokka = 'huoltoVaraus';
                    break;
                case 'tila':
                    for ($i = 0; $i < 6; $i++) {
                        $vastuuhenkilot[$i] = $_POST['vastuuhenkilo_' . $i];
                    }
                    $osallistujat = explode(',', $_POST['osallistujat-string']);
                    $tiedot = array(
                        'tila' => (int) $_POST['tila'],
                        'kustannuspaikka' => (int) $_POST['kustannuspaikka'],
                        'lisatieto' => $_POST['lisatieto'],
                        'tarkennukset' => $_POST['tarkennukset'],
                        'vastuuhenkilot' => $vastuuhenkilot,
                        'osallistujat' => $osallistujat,
                        'tekstiviesti' => (int) $_POST['tekstiviesti']
                    );
                    $luokka = 'tilaVaraus';
                    break;
                case 'paikallaoloilmoitus':
                    
                    $tiedot = array(
                        'userid'                => ($USER->iRole == 1) ? (int) $_POST['henkilo'] : $USER->ID,
                        'lisatieto'             => $_POST['lisatieto'],
                        'saatavuus'             => (int) $_POST['saatavuus'],
                        'koskee_lentoja'        => (int) $_POST['koskee_lentoja'],
                        'koskee_teoriaopetusta' => (int) $_POST['koskee_teoriaopetusta'],
                        
                    );
                    $luokka = 'paikallaoloVaraus';
                    break;
            }
            $tiedot['alkuaika'] = strtotime($alkuaika);
            $tiedot['loppuaika'] = strtotime($loppuaika);
            if (!$_POST['varaus_id']) {
                $varaus = new $luokka($tiedot);
            } else {
                $varaus = new $luokka((int) $_POST['varaus_id']);
                $varaus->tallennaTiedot($tiedot);
            }
            try {
                $varaus->tallennaVaraus();
            } catch (VarausException $ve) {
                echo $ve->getCode();
            }
            if (!$ve) {
                echo 777;
            }
        }
    break; // endcase tallennaVaraus

    case 63: // muokkaaVarausta
        switch($_POST['tyyppi']) {
            case 'tila':
                include('view/html/varauskirja_uusi_tilavaraus.tpl.php');
            break;
            case 'lento':
                include('view/html/varauskirja_uusi_lentovaraus.tpl.php');
            break;
            case 'huolto':
                include('view/html/varauskirja_uusi_huoltovaraus.tpl.php');
            break;
        }
    break; // endcase muokkaaVarausta


    case 73: // paikallaoloilmoitus
        $poid = ($_POST['poid'] > 0) ? (int) $_POST['poid'] : '';
        
        $paikallaolo = new paikallaoloVaraus($poid);
        if (($USER->iRole != 1) && ($paikallaolo->haeTiedot('userid') != $USER->ID)) {
            $paikallaolo = new paikallaoloVaraus();
            $poid = null;
        }
        $muokataan_paikallaoloilmoitusta = (is_int($poid)) ? true : false;

        $tulevat_ilmoitukset = $vanhat_ilmoitukset = array();
        $kayttaja = ($USER->iRole == 1) ? '0' : $USER->ID;
        foreach ($paikallaolo->haeKayttajanVaraukset($kayttaja) as $ilmoitus) {
            $tulevat_ilmoitukset[$ilmoitus->id] = $ilmoitus->haeTiedot();
        }
        foreach ($paikallaolo->haeKayttajanVanhatVaraukset($kayttaja) as $ilmoitus) {
            $vanhat_ilmoitukset[$ilmoitus->id] = $ilmoitus->haeTiedot();
        }
        
        if ($USER->iRole == 1) {
            $hallinta = new hallinta();
            $kayttajat = $hallinta->haeKayttajat('nimitiedot', array('lastname' => 'asc', 'firstname' => 'asc'));
            its::array_group($kayttajat, 'userid', false, true);
            its::left_join($tulevat_ilmoitukset, $kayttajat, 'userid');
            its::left_join($vanhat_ilmoitukset, $kayttajat, 'userid');
        }
        $APP->PAGEVARS[TITLE] = "Uusi paikallaoloilmoitus";
        $APP->VIEWPARTS = array('header-main.tpl.php', 'varauskirja_pt_napit.tpl.php', 'varauskirja_paikallaoloilmoitus.tpl.php', 'footer-main.tpl.php');
        
    break; // endcase paikallaoloilmoitus

    case 74: // poistaPaikallaoloIlmoitus
        $poid = (int) htmlentities($_POST['poid'], ENT_COMPAT);
        $paikallaolo = new paikallaoloVaraus($poid);
        if (($USER->iRole == 1) || ($USER->ID == $paikallaolo->haeTiedot('userid'))) {
            if ($paikallaolo->poistaVaraus($poid)) {
                echo '1';
            }
        } else {
            echo '0';
        }
    break; // endcase poistaPaikallaoloilmoitus
    
    
    case 75: //naytaVaraus
        switch($_POST['tyyppi']) {
            case 'tila':
                $varaus = new tilaVaraus((int) $_POST['varaus_id']);
                $tiedot = $varaus->haeTiedot();
                $tila_tiedot = $hallinta->haeTilat($tiedot['tila']);
                $kustannuspaikka_tiedot = $hallinta->haeKustannuspaikat($tiedot['kustannuspaikka']);
                include('view/html/varauskirja_nayta_tilavaraus.tpl.php');
            break;
            case 'lento':
                $varaus = new lentoVaraus((int) $_POST['varaus_id']);
                $tiedot = $varaus->haeTiedot();
                $kustannuspaikka_tiedot = $hallinta->haeKustannuspaikat($tiedot['kustannuspaikka']);
                $kone_tiedot = $hallinta->haeKoneet($tiedot['kone'], true);
                include('view/html/varauskirja_nayta_lentovaraus.tpl.php');
            break;
            case 'huolto':
                $varaus = new huoltoVaraus((int) $_POST['varaus_id']);
                $tiedot = $varaus->haeTiedot();
                $kustannuspaikka_tiedot = $hallinta->haeKustannuspaikat($tiedot['kustannuspaikka']);
                $kone_tiedot = $hallinta->haeKoneet($tiedot['kone'], true);
                include('view/html/varauskirja_nayta_huoltovaraus.tpl.php');
            break;
        }
    break; //endcase naytaVaraus
} //End of switch statement
include "load.view.php";
?>
