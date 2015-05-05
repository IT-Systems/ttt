<?php 
class varauskirja {
    /* CONSTRUCTOR */
    function __construct() {
        global $DB,$APP,$USER;
        $this->DB   = $DB;
        $this->APP  = $APP;
        $this->USER = $USER;
        return true;
    }

    function haePaivaNakyma($db_date) {
        list($vuosi, $kk, $pva) = explode('-', $db_date);
        $paiva = $this->haePaivanFiNimi($vuosi, $kk, $pva);
        $viikko = $this->haeViikkoNro($vuosi, $kk, $pva);
        $ed_paiva = date('Y-m-d', mktime(0, 0, 0, $kk, $pva - 1, $vuosi));
        $seur_paiva = date('Y-m-d', mktime(0, 0, 0, $kk, $pva + 1, $vuosi));

        // Piirretään päivänäkymä, ensimmäiselle riville päivämäärä. Toiselle riville checkboxit eri kohteille.
        $nakyma = "\n\t" . '<div class="paivanakyma">';
        $nakyma.= '
        <table class="tbl_paivanakyma" cellpadding="0" cellspacing="0">
            <tr>
                <td class="hrow" colspan="222">
                    <a href="varauskirja.php?date=' . $ed_paiva . '"><img src="view/images/icon_previous.png" height="12px"></a>
                    <a href="varauskirja.php?date=' . $seur_paiva . '"><img src="view/images/icon_next.png" height="12px"></a>
                    ' . $paiva . ' ' . $pva . '.' . $kk . '.' . $vuosi . ', viikko ' . $viikko . '
                 </td>
            </tr>
            <tr>
                <td class="time_boxes">
                    <input type="checkbox" name="planes" id="planes" checked>
                </td>
                <td class="time_icons">
                    <img src="view/images/icon_plane.png" height="25" width="25">
                </td>
                <td class="time_boxes">
                    <input type="checkbox" name="grounds" id="grounds" checked>
                </td>
                <td class="time_icons">
                    <img src="view/images/icon_ground.png" height="25" width="25">
                </td>
                <td class="time_boxes">
                    <input type="checkbox" name="staff" id="staff" checked>
                </td>
                <td class="time_icons">
                    <img src="view/images/icon_staff.png" height="25" width="25">
                </td>';
        // Toiselle riville myös kellonajat klo 6-24, yksi solu = 5 min. (yhteensä 216 solua)
        $i = 0;
        for ($minutes = 360; $minutes < 1440; $minutes = $minutes + 5) {
            $hours_show = ($minutes % 60 == 0) ? $minutes / 60 : '';
            $nakyma.= '
                <td class="time_hours';
            if ($i == 0) $nakyma.= '_first';
            $nakyma.= '"';
            if ($hours_show != '') {
                $nakyma.= ' colspan="6"';
                $minutes = $minutes + 25;
            }
            $nakyma.= '>' . $hours_show . '</td>'."\n";
            $i++;
        }
        $nakyma.= '
            </tr>';

        // Haetaan seuraaville riveille konetyypit (rowspan) ja yksi kone per rivi.
        $nakyma.= $this->piirraKoneidenVaraukset($vuosi, $kk, $pva);

        // Seuraaville riveille maatyövaraukset
        $nakyma.= $this->piirraMaatyoVaraukset($vuosi, $kk, $pva);

        $nakyma.='
        </table>' . "\n";
        $nakyma.= "\t" . '</div>';

        return $nakyma;
    }

    function piirraKoneidenVaraukset($vuosi, $kk, $pva) {
        $varaukset = '';
        $konetyypit = $this->haeKonetyypit();

        foreach ($konetyypit as $type) {
            $koneet = array();
            $koneet = $this->haeKoneet($type['id']);
            $konemaara = sizeof($koneet);

            // Ei laiteta taulukkoon, jos tyypillä ei ole koneita.
            if ($konemaara > 0) {
                $rowspan = ($konemaara > 1) ? ' rowspan="' . $konemaara . '"' : ''; // Rowspanin korkeus koneiden mukaan
                $varaukset.= '
            <tr class="koneet">
                <td' . $rowspan . ' colspan="3" class="typecell">' . $type['nimi'] . '</td>'."\n";
                $i = 0;

                foreach ($koneet as $kone) {
                    if ($i > 0) {
                        $varaukset.= '
            <tr class="koneet">';
                    }
                    $varaukset.= '
                <td colspan="3">' . $kone['nimi'] . '</td>';
                    // Haetaan koneen lennot lähtöaikajärjestyksessä.
                    $lennot = $this->haeKoneenLennot($kone['id'], $vuosi, $kk, $pva);
                    $varaustiedot = array();

                    // Koneella on lentoja tälle päivämäärälle
                    if (is_array($lennot)) {
                        // Muokataan kannasta saatu data minuuttimuotoon uuteen taulukkoon
                        for ($j = 0; $j < sizeof($lennot); $j++) {
                            list($ah, $am) = explode(':', $lennot[$j]['lahtoaika']);
                            list($lh, $lm) = explode(':', $lennot[$j]['saapumisaika']);
                            $varaustiedot[$j]['aloitusmin'] = (int)$ah * 60 + (int)$am;
                            $varaustiedot[$j]['lopetusmin'] = (int)$lh * 60 + (int)$lm;
                            $varaustiedot[$j]['kesto'] = ((int)$lh * 60 + (int)$lm) - ((int)$ah * 60 + (int)$am);
                            $varaustiedot[$j]['aikavali'] = (int)$ah . ':' . $am . ' - ' . (int)$lh . ':' . $lm;
                        }
                        unset($j);
                    }

                    if (sizeof($varaustiedot) > 0) {
                        $j = 0;
                        $k = sizeof($varaustiedot);
                    }

                    // Piirretään koneen varausaikarivi
                    for ($minutes = 360; $minutes < 1440; $minutes = $minutes + 5) {
                        $varaukset.= '
                <td class="timetable_hours';
                        // Aloitusaika löytyi
                        if ($minutes == $varaustiedot[$j]['aloitusmin']) {
                            // Merkitään keston verran colspania ja lisätään minuutteja tässä
                            $spanni = $varaustiedot[$j]['kesto'] / 5;
                            if ($spanni > 1) {
                                $varaukset.= '_start" colspan="' . $spanni . '" style="background-color:#FAC8AF;"';
                                $minutes = $minutes + (($spanni - 1) * 5);
                            }
                            $varaukset.= '>' . $varaustiedot[$j]['aikavali'] . '</td>';
                            $j++;
                        }
                        else {
                            $varaukset.= '"></td>';
                        }
                    }
                    $varaukset.= '
            </tr>';
                    $i++;
                }
            }
        }

        return $varaukset;
    }

    function piirraMaatyoVaraukset($vuosi, $kk, $pva) {
        $varaukset = '';
        $paikat = $this->haeKustannuspaikat();

        // Haetaan ensin kustannuspaikat, eli maatyökohteet.
        if (sizeof($paikat) > 0) {
            foreach ($paikat as $key => $val) {
                $maatyot = $this->haeMaatyovaraukset($val['id'], $vuosi, $kk, $pva);

            }
        }
        return $varaukset;
    }

    function haeKoneenLennot($id, $year, $month, $day) {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot WHERE ilma_alus = " . $id;
        $sql.= " AND alkamispaiva = '{$year}-{$month}-{$day}' ORDER BY lahtoaika";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        $lennot = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return (sizeof($lennot) == 0) ? 0 : $lennot;
    }

    function haePaivanFiNimi($year, $month, $day) {
        $day_no = date('N', mktime(0, 0, 0, $month, $day, $year));
        $fi_name = '';
        switch ($day_no) {
            case '1':
                $fi_name = 'Maanantai';
                break;
            case '2':
                $fi_name = 'Tiistai';
                break;
            case '3':
                $fi_name = 'Keskiviikko';
                break;
            case '4':
                $fi_name = 'Torstai';
                break;
            case '5':
                $fi_name = 'Perjantai';
                break;
            case '6':
                $fi_name = 'Lauantai';
                break;
            case '7':
                $fi_name = 'Sunnuntai';
                break;
            default:
                $fi_name = 'Ei ole päivä';
                break;
        }
        return $fi_name;
    }

    function haeViikkoNro($year, $month, $day) {
        return date('W', mktime(0, 0, 0, $month, $day, $year));
    }

    function haeKonetyypit() {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}konetyypit";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function haeKoneet($tyyppi=0) {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}koneet";
        if ($tyyppi > 0) $sql.= " WHERE konetyyppi = " . $tyyppi;
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function haeKustannuspaikat() {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kustannuspaikat";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function haeMaatyovaraukset($kpid=0, $year, $month, $day) {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}maatyovaraukset ";
        // if ($kpid > 0) $sql.= " AND kustannuspaikka = " . $kpid;
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} //End Of Class Statement
?>
