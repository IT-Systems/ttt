<?php 
class etusivu {
    /* CONSTRUCTOR */
    function __construct() {
        global $DB,$APP,$USER;
        $this->DB   = $DB;
        $this->APP  = $APP;
        $this->USER = $USER;
        return true;
    }

    function haeTyovuoro() {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}tyovuoro ".
                    "WHERE user_id = :userid AND lopetus = :loppu ".
                    "ORDER BY aloitus DESC LIMIT 1";

        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->bindValue(':loppu', '0000-00-00 00:00:00');
        $oStmtData->execute();

        $tyovuoro = $oStmtData->fetchAll();

        return $tyovuoro;
    }

    function haeAloitusajat() {
        $aloitusajat = array();
        $tunti_nyt = date("H");
        $minuutit_nyt = date("i");

        $x = 0;
        //Tehdään array, jossa on ajat
        for($i = 0; $i <= 23; $i++) {
            $tunnit = $i;

            if($tunnit < 10) {
                $tunnit = '0' . $i;
            }

            for($a = 0; $a < 60; $a += 5) {
                $x++;
                $minuutit = $a;

                if($minuutit < 10) {
                    $minuutit = '0' . $a;
                }

                $aloitusajat[$x]['aika'] = $tunnit . ":" . $minuutit;

                if ($tunti_nyt == $tunnit AND ($minuutit - $minuutit_nyt < 5 AND $minuutit - $minuutit_nyt >= 0)) {
                    $aloitusajat[$x]['nyt'] = $tunnit . ":" . $minuutit;
                    //echo $aloitusajat[$x]['nyt'];
                }
                else {
                    $aloitusajat[$x]['nyt'] = "";
                }

            }
        }

        return $aloitusajat;
    }

    function aloitaVuoro() {
        $pvm = date("Y-m-d");
        $alkuaika = $_POST['aloitusaika'] . ":" . "00";
        $alkuaika = $pvm . ' ' . $alkuaika;
        $loppuaika = '0000-00-00 00:00:00';

        $aikavyohyke = '0';
        $kommentti = '';
        $jatkoa_katk = '0';
        $kahden_ohj = '0';

        // Tarkastetaan onko ulkoinen työvuoro nyt voimassa
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tyovuoro_ulk ";
        $sql.= "WHERE aloitus <= :alku AND lopetus > :alku";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":alku", $alkuaika);
        $stmt->execute();
        $rivit = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($rivit) {
            return 'Et voi aloittaa vuoroa, koska toinen <a href="'.$this->APP->BASEURL.'/tyoaika.php?ID=44">vuoro</a> on vielä voimassa!';
        }

        $query1 = "SELECT * FROM {$this->APP->TABLEPREFIX}tyovuoro WHERE user_id = :userid ".
                  "AND lopetus = :loppu ORDER BY aloitus DESC LIMIT 1";
        $oStmt1 = $this->DB->prepare($query1);
        $oStmt1->bindParam(':userid', $this->USER->ID);
        $oStmt1->bindValue(':loppu', '0000-00-00 00:00:00');
        $oStmt1->execute();
        $vuoro = $oStmt1->fetchAll();

        if (count($vuoro) == 0) {
            $query = "INSERT INTO {$this->APP->TABLEPREFIX}tyovuoro SET ".
                     "user_id = :userid, aloitus = :alku, lopetus = :loppu, " .
                     "aikavyohyke = :aikavyohyke, kommentti = :kommentti, " .
                     "jatkoa_katk = :jatkoa_katk, kahden_ohj = :kahden_ohj";

            $oStmt = $this->DB->prepare($query);
            $oStmt->bindParam(':userid', $this->USER->ID);
            $oStmt->bindParam(':alku', $alkuaika);
            $oStmt->bindParam(':loppu', $loppuaika);
            $oStmt->bindParam(':aikavyohyke', $aikavyohyke);
            $oStmt->bindParam(':kommentti', $kommentti);
            $oStmt->bindParam(':jatkoa_katk', $jatkoa_katk);
            $oStmt->bindParam(':kahden_ohj', $kahden_ohj);
            $oStmt->execute();

            list($year, $month, $day) = explode('-', $pvm);
            $msg = "Vuoro aloitettu " . (int)$day . "." . (int)$month . "." . $year;
            $msg.= " klo " . $_POST["aloitusaika"];
            return $msg;
        }
        else {
            return "Vuoro on jo aloitettu.";
        }
    }

    function lopetaVuoro() {
        $leptus = date("Y-m-d")." ". $_POST["aloitusaika"].":00";
        $leptus0 = "0000-00-00 00:00:00";

        $sql = "UPDATE {$this->APP->TABLEPREFIX}tyovuoro " .
               "SET lopetus = :loppu " .
               "WHERE user_id = :userid AND lopetus = '{$leptus0}'" .
               "ORDER BY aloitus DESC LIMIT 1";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':loppu', $leptus);
        $stmt->bindParam(':userid', $this->USER->ID);
        $stmt->execute();

        return 'Vuoro lopetettu.';
    }

    function haeAikaNyt() {
        $aika_nyt = getdate();
        $aika_nyt = $aika_nyt['hours'] . ":" . $aika_nyt['minutes'];
        return $aika_nyt;
    }

    function haeTiedotteet($luettu) {
        $tiedotteet_array = array();
        $i = 0;

        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet WHERE tyyppi != '1'";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->execute();
        $tiedotteet = $oStmtData->fetchAll();

        foreach ($tiedotteet as $tiedote) {
            $sSqlData2 = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet_kayttaja WHERE kayttaja_id = :kayttajaid AND tiedote_id = :tiedoteid AND tyyppi != '1'";
            $oStmtData2= $this->DB->prepare($sSqlData2);
            $oStmtData2->bindParam(':kayttajaid', $this->USER->ID);
            $oStmtData2->bindParam(':tiedoteid', $tiedote['id']);
            $oStmtData2->execute();
            $tiedote_kayttaja = $oStmtData2->fetchAll();

            //Jos haetaan ei-luettuja:
            if ($luettu == '0') {
                if (count($tiedote_kayttaja) == 0) {
                    $i++;
                    $tiedotteet_array[$i]['tiedote_id'] = $tiedote['id'];
                    $tiedotteet_array[$i]['otsikko'] = $tiedote['otsikko'];
                    $tiedotteet_array[$i]['teksti'] = $tiedote['teksti'];
                    $pvm_explode = explode('-', $tiedote['pvm']);
                    $tiedotteet_array[$i]['pvm'] = $pvm_explode[2] . "." . $pvm_explode[1] . "." . $pvm_explode[0];
                }
            }
            elseif ($luettu == '1') {
                if (count($tiedote_kayttaja) != 0) {
                    $i++;
                    $tiedotteet_array[$i]['tiedote_id'] = $tiedote['id'];
                    $tiedotteet_array[$i]['otsikko'] = $tiedote['otsikko'];
                    $tiedotteet_array[$i]['teksti'] = $tiedote['teksti'];
                    $pvm_explode = explode('-', $tiedote['pvm']);
                    $tiedotteet_array[$i]['pvm'] = $pvm_explode[2] . "." . $pvm_explode[1] . "." . $pvm_explode[0];
                }
            }
        }

        return $tiedotteet_array;
    }

    function haeOhjeet($luettu) {
        $tiedotteet_array = array();
        $i = 0;

        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet WHERE tyyppi = '1'";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->execute();
        $tiedotteet = $oStmtData->fetchAll();

        foreach ($tiedotteet as $tiedote) {
            $sSqlData2 = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet_kayttaja WHERE kayttaja_id = :kayttajaid AND tiedote_id = :tiedoteid AND tyyppi = '1'";
            $oStmtData2= $this->DB->prepare($sSqlData2);
            $oStmtData2->bindParam(':kayttajaid', $this->USER->ID);
            $oStmtData2->bindParam(':tiedoteid', $tiedote['id']);
            $oStmtData2->execute();
            $tiedote_kayttaja = $oStmtData2->fetchAll();

            //Jos haetaan ei-luettuja:
            if ($luettu == '0') {
                if (count($tiedote_kayttaja) == 0) {
                    $i++;
                    $tiedotteet_array[$i]['tiedote_id'] = $tiedote['id'];
                    $tiedotteet_array[$i]['otsikko'] = $tiedote['otsikko'];
                    $tiedotteet_array[$i]['teksti'] = $tiedote['teksti'];
                    $pvm_explode = explode('-', $tiedote['pvm']);
                    $tiedotteet_array[$i]['pvm'] = $pvm_explode[2] . "." . $pvm_explode[1] . "." . $pvm_explode[0];
                }
            }
            elseif ($luettu == '1') {
                if (count($tiedote_kayttaja) != 0) {
                    $i++;
                    $tiedotteet_array[$i]['tiedote_id'] = $tiedote['id'];
                    $tiedotteet_array[$i]['otsikko'] = $tiedote['otsikko'];
                    $tiedotteet_array[$i]['teksti'] = $tiedote['teksti'];
                    $pvm_explode = explode('-', $tiedote['pvm']);
                    $tiedotteet_array[$i]['pvm'] = $pvm_explode[2] . "." . $pvm_explode[1] . "." . $pvm_explode[0];
                }
            }
        }

        return $tiedotteet_array;
    }

    function haeTiedote($tiedote_id) {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet WHERE id = :tiedoteid";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':tiedoteid', $tiedote_id);
        $oStmtData->execute();
        $tiedote = $oStmtData->fetch();
        return $tiedote;
    }

    function haeVaraukset() {
        //Haetaan kaikki käyttäjän varaukset, joiden alku- tai loppupvm on suurempi kuin nykyinen pvm:

        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}huoltovaraukset h " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}od_user u ON u.userid = h.vastuuhenkilo " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}koneet k ON k.id = h.kone " .
                "WHERE kayttaja_id = :kayttajaid AND (alkupvm >= CURDATE() OR loppupvm >= CURDATE())";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':kayttajaid', $this->USER->ID);
        $oStmtData->execute();
        $huoltovaraukset = $oStmtData->fetchAll();

        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}maatyovaraukset m " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}tehtavat t ON t.id = m.tehtava " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}kustannuspaikat k ON k.id = m.kustannuspaikka " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}od_user u ON u.userid = m.kayttaja_id " .
                "WHERE kayttaja_id = :kayttajaid AND (alkupvm >= CURDATE() OR loppupvm >= CURDATE())";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':kayttajaid', $this->USER->ID);
        $oStmtData->execute();
        $maatyovaraukset = $oStmtData->fetchAll();

        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}tilavaraukset tv " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}tilat t ON t.id = tv.tila " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}kustannuspaikat k ON k.id = tv.kustannuspaikka " .
                "LEFT JOIN {$this->APP->TABLEPREFIX}od_user u ON u.userid = tv.kayttaja_id " .
                "WHERE kayttaja_id = :kayttajaid AND pvm >= CURDATE()";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':kayttajaid', $this->USER->ID);
        $oStmtData->execute();
        $tilavaraukset = $oStmtData->fetchAll();

        //Tehdään arrayt ja otetaan tämän hetken mktime talteen vertailua varten:
        $varaukset_lordofthearrays = array();
        $varaukset_array = array();
        $vpaiva_nyt = date("N");
        $nyt_mktime = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        $i = 0;

        $varaukset_lordofthearrays[0] = $huoltovaraukset;
        $varaukset_lordofthearrays[1] = $maatyovaraukset;
        $varaukset_lordofthearrays[2] = $tilavaraukset;

        foreach ($varaukset_lordofthearrays as $gandalf) {
            foreach($gandalf as $varaus) {
                $i++;

                if (isset($varaus['alkupvm']) AND isset($varaus['loppupvm'])) {
                    //Otetaan varauksen alku- ja loppupäivämäärien mktime talteen vertailua varten:
                    $pvm = explode('-', $varaus['alkupvm']);

                    /* $pvm = explode('-', $varaus['loppupvm']);
					$pv = $pvm[2];
					$kk = $pvm[1];
					$v = $pvm[0];
					$loppupvm_mktime = mktime(0, 0, 0, $kk, $pv, $v); */
                }
                else {
                    $pvm = explode('-', $varaus['pvm']);
                }

                $pv = $pvm[2];
                $kk = $pvm[1];
                $v = $pvm[0];
                $alkupvm_mktime = mktime(0, 0, 0, $kk, $pv, $v);

                //Jos alkupvm on suurempi kuin päivämäärä nyt, tutkitaan, onko alkupvm huomenna:
                if ($alkupvm_mktime > $nyt_mktime) {
                    $varaus_vpaiva = date("N", $alkupvm_mktime);

                    if($vpaiva_nyt != '7' AND ($varaus_vpaiva == $vpaiva_nyt + 1) AND ($alkupvm_mktime - $nyt_mktime < 86401)) {
                        $varaukset_array[0][$i]['pvm'] = 'Huomenna';
                    }
                    elseif($vpaiva_nyt == '7' AND ($varaus_vpaiva == '1') AND ($alkupvm_mktime - $nyt_mktime < 86401)) {
                        $varaukset_array[0][$i]['pvm'] = 'Huomenna';
                    }
                    else {
                        $explode_pvm = explode('-', $varaus['alkupvm']);
                        $varaukset_array[0][$i]['pvm'] = $pvm[2] . "." . $pvm[1] . "." . $pvm[0];
                    }
                }
                else {
                    //Muussa tapauksessa alkupvm on menneisyydessä -> varauksen on oltava meneillään oleva,
                    //sillä SQL-lauseessa on rajattu pois kaikki menneet varaukset:

                    $varaukset_array[0][$i]['pvm'] = 'Huomenna';
                }

                $varaukset_array[0][$i]['aika'] = substr($varaus['alkuaika'], 0, 5) . " - " . substr($varaus['loppuaika'], 0, 5);
                $varaukset_array[0][$i]['vastuuhenkilo'] = $varaus['firstname'] . " " . $varaus['lastname'];
                $varaukset_array[0][$i]['kone'] = $varaus['nimi'];
                $varaukset_array[0][$i]['lisatiedot'] = $varaus['lisatietoja'];
            }
        }

        //print_r($varaukset_array);
        return $varaukset_array;
    }

    function merkitseTiedoteLuetuksi($tiedote) {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet " .
                "WHERE id = :tiedote_id";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':tiedote_id', $tiedote);
        $oStmtData->execute();
        $tiedote_haettu = $oStmtData->fetch();

        $sSqlData = "INSERT INTO {$this->APP->TABLEPREFIX}tiedotteet_kayttaja " .
                "SET tyyppi = :tyyppi, kayttaja_id = :userid, tiedote_id = :tiedote_id";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->bindParam(':tiedote_id', $tiedote);
        $oStmtData->bindParam(':tyyppi', $tiedote_haettu['tyyppi']);
        $oStmtData->execute();
        /* $sPath = $_SERVER['PHP_SELF'] . '?ID=6';
		header("Location: $sPath");		 */

    }

    function merkitseOhjeLuetuksi($tiedote) {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet " .
                "WHERE id = :tiedote_id";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':tiedote_id', $tiedote);
        $oStmtData->execute();
        $tiedote_haettu = $oStmtData->fetch();

        $sSqlData = "INSERT INTO {$this->APP->TABLEPREFIX}tiedotteet_kayttaja " .
                "SET tyyppi = :tyyppi, kayttaja_id = :userid, tiedote_id = :tiedote_id";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->bindParam(':tiedote_id', $tiedote);
        $oStmtData->bindParam(':tyyppi', $tiedote_haettu['tyyppi']);
        $oStmtData->execute();
        /* $sPath = $_SERVER['PHP_SELF'] . '?ID=6';
		header("Location: $sPath");		 */

    }

    function haeVahvistukset()
    {
        $sql = "SELECT req.vahvistettu, cal.* FROM {$this->APP->TABLEPREFIX}jqcalendar_requests req ";
        $sql.= "LEFT JOIN {$this->APP->TABLEPREFIX}jqcalendar cal ON cal.Id = req.jqcalendar_id ";
        $sql.= "WHERE req.kutsu_user_id = :kuid AND cal.vahvistus = 1 AND cal.StartTime > :raja";

        $stamp = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("n"), date("j")+1, date("Y")));
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":kuid", $this->USER->ID);
        $stmt->bindParam(":raja", $stamp);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Ilmoituksina esimerkiksi tietyn henkilön kelpoisuuden vanheneminen (jos 3kk sisällä tapahtuu).
     */

    public function haeIlmoitukset() {
        $raja = date("Y-m-d", mktime(0,0,0,date("m")+3,date("d"),date("Y")));
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kelpuutukset_kayttaja ";
        $sql.= "WHERE vanhenee <= :raja AND vanhenee != :eikoskaan ";
        if ($this->USER->ID != 19 && $this->USER->ID != 20 && $this->USER->ID != 21 && $this->USER->ID != 1) {
            $sql.= "AND user_id = :uid ";
        }
        $sql.= "ORDER BY vanhenee";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":raja", $raja);
        $stmt->bindValue(":eikoskaan", "0000-00-00");
        if ($this->USER->ID != 19 && $this->USER->ID != 20 && $this->USER->ID != 21 && $this->USER->ID != 1) {
            $stmt->bindParam(":uid", $this->USER->ID);
        }
        $stmt->execute();
        $ilmoitukset = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ilmoitukset;
    }

    /*
     *
     */

    public function haeYhteystiedot() {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :uid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $_GET["userId"]);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

} //End Of Class Statement
?>

