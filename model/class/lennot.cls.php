<?php 
class lennot {
    /* CONSTRUCTOR */
    public function __construct() {
        global $DB,$APP,$USER;
        $this->DB   = $DB;
        $this->APP  = $APP;
        $this->USER = $USER;
        return true;
    }

    public function haeOmatLennot($paiva) {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot ";
        $sSqlData.= "WHERE kayttaja_id = :userid AND alkamispaiva = :paiva";

        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->bindParam(':paiva', $paiva);
        $oStmtData->execute();
        
        $lennot = $oStmtData->fetchAll(PDO::FETCH_ASSOC);
        return $lennot;
    }

    public function haeLennot($id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot";
        if ($id > 0) $sql.= " WHERE id = :lid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":lid", $id);
        $stmt->execute();

        if ($id > 0) {
            $lento = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$lento) ? false : $lento;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function haePeruutetutLennot($id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot_peruutetut";
        if ($id > 0) $sql.= " WHERE id = :lid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":lid", $id);
        $stmt->execute();

        if ($id > 0) {
            $lento = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$lento) ? false : $lento;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /*
     * Haetaan koneiden ts. ilma-alusten tai yhden sellaisen ($id määrätty kutsussa) tiedot.
     */
    public function haeIlmaAlus($id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}koneet";
        if ($id > 0) $sql.= " WHERE id = :kid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":kid", $id);
        $stmt->execute();

        if ($id > 0) {
            $alus = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$alus) ? false : $alus;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function haeKoneTyypit($id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}konetyypit";
        if ($id > 0) $sql.= " WHERE id = :ktid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":ktid", $id);
        $stmt->execute();

        if ($id > 0) {
            $tyyppi = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$tyyppi) ? false : $tyyppi;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function haeLennonToiminnanLaadut($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lento_toiminnan_laadut ";
        $sql.= ($id > 0) ? "WHERE id = :ltlid" : "ORDER BY lyhenne";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":ltlid", $id);
        $stmt->execute();

        if ($id > 0) {
            $laatu = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$laatu) ? false : $laatu;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /*
     * Haetaan ilman id:tä kaikki tai määrätyt ($role) henkilöt.
     * id:llä haetaan tietty henkilö.
     */
    public function haeHenkilot($id='', $role='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}od_user ";
        if ($id > 0) {
            $sql.= "WHERE userid = :uid";
        }
        else {
            if ($role != '') $sql.= "WHERE roleid IN ({$role}) ";
            $sql.= "ORDER BY lastname";
        }
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":uid", $id);
        $stmt->execute();

        if ($id > 0) {
            $henkilo = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$henkilo) ? false : $henkilo;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function haeKustannuspaikat($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kustannuspaikat ";
        $sql.= ($id > 0) ? "WHERE id = :kpid" : "ORDER BY nimi";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":kpid", $id);
        $stmt->execute();

        if ($id > 0) {
            $kustannuspaikka = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$kustannuspaikka) ? false : $kustannuspaikka;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function haeSyllabukset($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabukset ";
        $sql.= ($id > 0) ? "WHERE id = :sbid" : "ORDER BY nimi";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":sbid", $id);
        $stmt->execute();

        if ($id > 0) {
            $syllabus = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$syllabus) ? false : $syllabus;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /*
     * Lennon tallentaminen
     */
    public function tallennaLento()
    {
        // Tarkistetaan ensin annetut syötteet txt-kentistä (ts. pvm, aika ja int) vaatimusten täyttyminen
        $userid = $this->USER->ID;
        $userrole = $this->USER->iRole;
        $ak = new aikakalu();

        // Onko lähtöpäivämäärä validi päivä?
        $lahtopaiva = $ak->fiDateToDbDate($_POST["strStartDate"]);
        list($year, $month, $day) = explode("-", $lahtopaiva);
        if (!checkdate($month, $day, $year)) {
            $aMsg[0] = "Alkamispäivä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }

        // Matkustajien lukumäärän tulee olla luku
        if (!is_numeric($_POST["intNumOfPassengers"]) || $_POST["intNumOfPassengers"] < 0) {
            $aMsg[0] = "Matkustajien lukumäärä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        $matk_lkm = (int)$_POST["intNumOfPassengers"];

        // kuten myös lähtöjen ja laskeutumisten määrät...
        if (!is_numeric($_POST["intTakeoffsDayTime"]) || $_POST["intTakeoffsDayTime"] < 0) {
            $aMsg[0] = "Lentoon lähtöjen määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        $lentl_paiv = (int)$_POST["intTakeoffsDayTime"];

        if (!is_numeric($_POST["intTakeoffsNightTime"]) || $_POST["intTakeoffsNightTime"] < 0) {
            $aMsg[0] = "Lentoon lähtöjen määrä yöllä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        $lentl_yo = (int)$_POST["intTakeoffsNightTime"];

        if (!is_numeric($_POST["intLandingsDayTime"]) || $_POST["intLandingsDayTime"] < 0) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        $lask_paiv = (int)$_POST["intLandingsDayTime"];

        if (!is_numeric($_POST["intLandingsNightTime"]) || $_POST["intLandingsNightTime"] < 0) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        $lask_yo = (int)$_POST["intLandingsNightTime"];

        // Tarkistetaan myös ajat
        if (!$ak->tarkistaSyotettyAika($_POST["strOffBlockTime"])) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        if (!$ak->tarkistaSyotettyAika($_POST["strTimeOfDeparture"])) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        if (!$ak->tarkistaSyotettyAika($_POST["strTimeOfArrival"])) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        if (!$ak->tarkistaSyotettyAika($_POST["strOnBlockTime"])) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }

        // Sitten tarkistetaan onko valitulla aikavälillä vuoro tallennettuna tai aloitettu, jos kyseessä on opettaja.
        if ($userrole == 2 || $userrole == 1) {
            if ($_POST["intDontCheckShift"] != 1) {
                if (!$ak->onkoVuoroa($userid, $lahtopaiva, $_POST["strOffBlockTime"], $_POST["strOnBlockTime"])) {
                    $aMsg[0] = "Valitulle ajalle ei ole vuoroa voimassa!";
                    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                    $aMsg[2] = "ui-icon ui-icon-alert";
                    return $aMsg;
                }
            }
        }

        // Lennon tallentajan täytyy kuulua miehistöön, jos on opettaja tai oppilas.
        if ($userrole == 2 || $userrole == 3) {
            if ($_POST["intDontCheckParty"] != 1) {
                if ($_POST["intTeacherId"] == $userid || $_POST["intStudentId"] == $userid || $_POST["intVisitorId"] == $userid) { }
                else {
                    $aMsg[0] = "Sinun tulee kuulua lennon miehistöön tallentaaksesi!";
                    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                    $aMsg[2] = "ui-icon ui-icon-alert";
                    return $aMsg;
                }
            }
        }

        // Tarkistetaan lennon aikamääreet
        if (!$ak->tarkistaSyotettyAikaMaare($_POST["strOnAirTime"])) {
            $aMsg[0] = "Ilma-aika on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        if (!$ak->tarkistaSyotettyAikaMaare($_POST["strFlyingTime"])) {
            $aMsg[0] = "Lentoaika on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }

        // IFR-ajat ja Yö-ajat merkitään nolliksi, jos ko. checkbox on valitsematta.
        $iaon = ($_POST["intIfr"] == 1) ? 1 : 0;
        $yaon = ($_POST["intNight"] == 1) ? 1 : 0;        
        if ($iaon == 1 && !$ak->tarkistaSyotettyAikaMaare($_POST["strIfrTime"])) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        else {
            if ($iaon == 0) {
                $_POST["strIfrTime"] == "00:00";
            }
        }

        if ($yaon == 1 && !$ak->tarkistaSyotettyAikaMaare($_POST["strNightTime"])) {
            $aMsg[0] = "Laskeutumisten määrä päivällä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }
        else {
            if ($yaon == 0) {
                $_POST["strNightTime"] == "00:00";
            }
        }
        
        $suoritukset = (isset($_POST['intSuoritusId'])) ? implode(",", $_POST["intSuoritusId"]) : '';

        // Syötteet ok, tallennetaan lento.
        $sql = ($_POST["lento_id"] > 0 ) ? "UPDATE " : "INSERT INTO ";
        $sql.= "{$this->APP->TABLEPREFIX}lennot SET ";
        $sql.= "kayttaja_id = :uid, kone_id = :kid, alkamispaiva = :sdate, ";
        $sql.= "lahtopaikka = :lpaikka, maarapaikka = :mpaikka, toim_laatu = :tlid, ";
        $sql.= "toim_tyyppi_id = :tltid, matkustajia = :matklkm, lentosuunn_paat = :paat, ";
        $sql.= "paallikko_user_id = :pauid, peramies_user_id = :peuid, muu_jasen_user_id = :mjuid, ";
        $sql.= "lentoonlahtoja_paiva = :llp, lentoonlahtoja_yo = :lly, laskeutumisia_paiva = :lp, ";
        $sql.= "laskeutumisia_yo = :ly, off_block_aika = :offba, lahtoaika = :laha, ";
        $sql.= "saapumisaika = :saaa, on_block_aika = :onba, ilma_aika = :ilma, ";
        $sql.= "lentoaika = :lena, ifr_aika_on = :iaon, ifr_aika = :ifra,  ";
        $sql.= "yoaika_on = :yaon, yoaika = :yoaa, matkalento = :matl, ";
        $sql.= "huomautukset = :huom, kustannuspaikka_id = :kpid, valvoja_user_id = :vuid, ";
        $sql.= "syllabus_id = :syid, suoritus = :suor, uudelleen = :uudl";
        if ($_POST["lento_id"] > 0) $sql.= " WHERE id = :lentoid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $userid);
        $stmt->bindParam(":kid", $_POST["intKoneId"]);
        $stmt->bindParam(":sdate", $lahtopaiva);
        $stmt->bindParam(":lpaikka", $_POST["strTakeoffPlace"]);
        $stmt->bindParam(":mpaikka", $_POST["strDestPlace"]);
        $stmt->bindParam(":tlid", $_POST["intActionType"]);
        $stmt->bindParam(":tltid", $_POST["intActionId"]);
        $stmt->bindParam(":matklkm", $matk_lkm);
        $paat = ($_POST["intFlightplanDecided"] == 1) ? 1 : 0;
        $stmt->bindParam(":paat", $paat);
        $stmt->bindParam(":pauid", $_POST["intTeacherId"]);
        $stmt->bindParam(":peuid", $_POST["intStudentId"]);
        $stmt->bindParam(":mjuid", $_POST["intVisitorId"]);
        $stmt->bindParam(":llp", $lentl_paiv);
        $stmt->bindParam(":lly", $lentl_yo);
        $stmt->bindParam(":lp", $lask_paiv);
        $stmt->bindParam(":ly", $lask_yo);
        $stmt->bindParam(":offba", $_POST["strOffBlockTime"]);
        $stmt->bindParam(":laha", $_POST["strTimeOfDeparture"]);
        $stmt->bindParam(":saaa", $_POST["strTimeOfArrival"]);
        $stmt->bindParam(":onba", $_POST["strOnBlockTime"]);
        $stmt->bindParam(":ilma", $_POST["strOnAirTime"]);
        $stmt->bindParam(":lena", $_POST["strFlyingTime"]);
        $stmt->bindParam(":iaon", $iaon);
        $stmt->bindParam(":ifra", $_POST["strIfrTime"]);
        $stmt->bindParam(":yaon", $yaon);
        $stmt->bindParam(":yoaa", $_POST["strNightTime"]);
        $matl = ($_POST["intTravelFlight"] == 1) ? 1 : 0;
        $stmt->bindParam(":matl", $matl);
        $stmt->bindParam(":huom", $_POST["strNotes"]);
        $stmt->bindParam(":kpid", $_POST["intCostPoolId"]);
        $stmt->bindParam(":vuid", $_POST["intSupervisorId"]);
        $stmt->bindParam(":syid", $_POST["intSyllabusId"]);
        $stmt->bindParam(":suor", $suoritukset);
        $uudl = ($_POST["intFlyAgain"] == 1) ? 1 : 0;
        $stmt->bindParam(":uudl", $uudl);
        if ($_POST["lento_id"] > 0) $stmt->bindParam(":lentoid", $_POST["lento_id"]);

        $stmt->execute();

        if (!isset($_POST["lento_id"])) {
            $paluu = $this->APP->BASEURL . "/lennot.php?ID=14&PF=1";
            header("Location: " . $paluu);
            exit();
        }
        else {
            $aMsg[0] = "Lennon tiedot muokattiin onnistuneesti!";
            $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
            return $aMsg;
        }
    }

    public function poistaLento()
    {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}lennot ";
        $sql.= "WHERE id = :lid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":lid", $_GET["lento_id"]);
        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/lennot.php?ID=13&PF=1";
        header("Location: " . $paluu);
        exit();
    }

    public function haeLennotSimple()
    {
        $ak = new aikakalu();
        $alku = $ak->fiDateToDbDate($_POST["strFromDate"]);
        $loppu = $ak->fiDateToDbDate($_POST["strToDate"]);

        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot ";
        $sql.= "WHERE alkamispaiva >= :alku AND alkamispaiva <= :loppu ";
        $sql.= "ORDER BY alkamispaiva ASC, on_block_aika ASC";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":alku", $alku);
        $stmt->bindParam(":loppu", $loppu);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function haeLennotExtended()
    {
        $ak = new aikakalu();
        $alku = $ak->fiDateToDbDate($_POST["strFromDate"]);
        $loppu = $ak->fiDateToDbDate($_POST["strToDate"]);

        $sql = "SELECT l.* FROM {$this->APP->TABLEPREFIX}lennot l ";
        $sql.= "LEFT JOIN {$this->APP->TABLEPREFIX}koneet k ON l.kone_id = k.id ";
        $sql.= "WHERE l.alkamispaiva >= :alku AND l.alkamispaiva <= :loppu ";
        if ($_POST["intPlaneId"] > 0) $sql.= "AND l.kone_id = :kid ";
        if ($_POST["intPlaneTypeId"] > 0) $sql.= "AND k.konetyyppi = :ktid ";
        if ($_POST["intIfrType"] == 1) $sql.= "AND l.ifr_aika_on = :ifr ";
        if ($_POST["intNightType"] == 1) $sql.= "AND l.yoaika_on = :yoyo ";
        if ($_POST["intTravelType"] == 1) $sql.= "AND l.matkalento = :matkis ";
        if ($_POST["intSupervisorId"] > 0) $sql.= "AND l.valvoja_user_id = :vuid ";
        if ($_POST["intTeacherId"] > 0) $sql.= "AND l.paallikko_user_id = :puid ";
        if ($_POST["intStudentId"] > 0) $sql.= "AND l.peramies_user_id = :euid ";
        if ($_POST["intCostPoolId"] > 0) $sql.= "AND l.kustannuspaikka_id = :kpid ";
        
        $sql.= "ORDER BY l.alkamispaiva ASC, l.on_block_aika ASC";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":alku", $alku);
        $stmt->bindParam(":loppu", $loppu);
        if ($_POST["intPlaneId"] > 0) $stmt->bindParam(":kid", $_POST["intPlaneId"]);
        if ($_POST["intPlaneTypeId"] > 0) $stmt->bindParam(":ktid", $_POST["intPlaneTypeId"]);
        if ($_POST["intIfrType"] == 1) $stmt->bindValue(":ifr", 1);
        if ($_POST["intNightType"] == 1) $stmt->bindValue(":yoyo", 1);
        if ($_POST["intTravelType"] == 1) $stmt->bindValue(":matkis", 1);
        if ($_POST["intSupervisorId"] > 0) $stmt->bindParam(":vuid", $_POST["intSupervisorId"]);
        if ($_POST["intTeacherId"] > 0) $stmt->bindParam(":puid", $_POST["intTeacherId"]);
        if ($_POST["intStudentId"] > 0) $stmt->bindParam(":euid", $_POST["intStudentId"]);
        if ($_POST["intCostPoolId"] > 0) $stmt->bindParam(":kpid", $_POST["intCostPoolId"]);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function tallennaPeruutusLento()
    {
        $ak = new aikakalu();
        $pvm = $ak->fiDateToDbDate($_POST["strDate"]);
        list($year, $month, $day) = explode("-", $pvm);
        if (!checkdate($month, $day, $year)) {
            $aMsg[0] = "Alkamispäivä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }

        $sql = (!empty($_POST["plento_id"])) ? "UPDATE " : "INSERT INTO ";
        $sql.= "{$this->APP->TABLEPREFIX}lennot_peruutetut SET ";
        $sql.= "user_id = :uid, paivamaara = :pvm, kone_id = :kid, lahtopaikka = :lahp, ";
        $sql.= "maarapaikka = :maap, paallikko_user_id = :puid, toiminnan_laatu_id = :tlid, ";
        $sql.= "syy_id = :syid, selvennys = :selv, kustannuspaikka_id = :kpid, lentojen_lkm = :llkm";
        if (!empty($_POST["plento_id"])) $sql.= " WHERE id = :plid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->bindParam(":pvm", $pvm);
        $stmt->bindParam(":kid", $_POST["intKoneId"]);
        $stmt->bindParam(":lahp", $_POST["strTakeoffPlace"]);
        $stmt->bindParam(":maap", $_POST["strDestPlace"]);
        $stmt->bindParam(":puid", $_POST["intTeacherId"]);
        $stmt->bindParam(":tlid", $_POST["intActionId"]);
        $stmt->bindParam(":syid", $_POST["intReasonId"]);
        $stmt->bindParam(":selv", $_POST["strNotes"]);
        $stmt->bindParam(":kpid", $_POST["intCostPoolId"]);
        $llkm = (empty($_POST["intFlightCount"])) ? 1 : $_POST["intFlightCount"];
        $stmt->bindParam(":llkm", $llkm);
        if (!empty($_POST["plento_id"])) $stmt->bindParam(":plid", $_POST["plento_id"]);
        $stmt->execute();

        $paluu = (!empty($_POST["plento_id"])) ? $this->APP->BASEURL . "/lennot.php?ID=62&PF=3&mode=list" : $this->APP->BASEURL . "/lennot.php?ID=62&PF=1";
        header("Location: " . $paluu);
        exit();
    }

    public function poistaPeruutettuLento()
    {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}lennot_peruutetut ";
        $sql.= "WHERE id = :plid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":plid", $_GET["plento_id"]);
        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/lennot.php?ID=62&PF=2&mode=list";
        header("Location: " . $paluu);
        exit();
    }

    public function haeSyyt($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot_peruutussyyt";
        if ($id > 0) $sql.= " WHERE id = :syid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(":syid", $id);
        $stmt->execute();

        if ($id > 0) {
            $syy = $stmt->fetch(PDO::FETCH_ASSOC);
            return (!$syy) ? false : $syy;
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function haeSyllabuksenHarjoitukset()
    {
        if (empty($_GET["lentoId"]) && empty($_GET["harjoitusId"])) {
            $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabus_harjoitukset ";
            $sql.= "WHERE syllabus_id = :syid ORDER BY jarjestys";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":syid", $_GET["syllabusId"]);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            // Muokataan lentoa, haetaan lennon mahdollisten suoritusten valinnat.
            $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabus_harjoitukset ";
            $sql.= "WHERE syllabus_id = :syid ORDER BY jarjestys";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":syid", $_GET["syllabusId"]);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($_GET["lentoId"])) {
                foreach($rows as $key => $val) {
                    $harjoitus_id = $val["id"];
                    $pos1 = $harjoitus_id;
                    $pos2 = $harjoitus_id.',%';
                    $pos3 = '%,'.$harjoitus_id.',%';
                    $pos4 = '%,'.$harjoitus_id;

                    $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot ";
                    $sql.= "WHERE suoritus LIKE (:pos1) OR suoritus LIKE (:pos2) ";
                    $sql.= "OR suoritus LIKE (:pos3) OR suoritus LIKE (:pos4)";
                    $stmt = $this->DB->prepare($sql);
                    $stmt->bindParam(":pos1", $pos1);
                    $stmt->bindParam(":pos2", $pos2);
                    $stmt->bindParam(":pos3", $pos3);
                    $stmt->bindParam(":pos4", $pos4);
                    $stmt->execute();
                    $hRow = $stmt->fetch();

                    if ($hRow) {
                        $rows[$key]["valittu"] = 1;
                    } else {
                        $rows[$key]["valittu"] = 0;
                    }
                }
            }
            elseif (!empty($_GET["harjoitusId"])) {
                foreach($rows as $key => $val) {
                    $harjoitus_id = $val["id"];
                    $pos1 = $harjoitus_id;
                    $pos2 = $harjoitus_id.',%';
                    $pos3 = '%,'.$harjoitus_id.',%';
                    $pos4 = '%,'.$harjoitus_id;

                    $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot_harjoitukset ";
                    $sql.= "WHERE suoritukset LIKE (:pos1) OR suoritukset LIKE (:pos2) ";
                    $sql.= "OR suoritukset LIKE (:pos3) OR suoritukset LIKE (:pos4)";
                    $stmt = $this->DB->prepare($sql);
                    $stmt->bindParam(":pos1", $pos1);
                    $stmt->bindParam(":pos2", $pos2);
                    $stmt->bindParam(":pos3", $pos3);
                    $stmt->bindParam(":pos4", $pos4);
                    $stmt->execute();
                    $hRow = $stmt->fetch();

                    if ($hRow) {
                        $rows[$key]["valittu"] = 1;
                    } else {
                        $rows[$key]["valittu"] = 0;
                    }
                }
            }
            return $rows;
        }
    }

    public function haeHarjoitukset($from='', $to='', $id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot_harjoitukset ";

        if ($from != '' || $to != '' || $id != '') $sql.= "WHERE ";
        $where = 0;
        if ($from != '') {
            $sql.= "paivamaara >= :from ";
            $where = 1;
        }
        if ($to != '') {
            if ($where == 1) "AND ";
            $sql.= "paivamaara <= :to ";
            $where = 1;
        }
        if ($id != '') {
            if ($where == 1) "AND ";
            $sql.= "id = :hid";
        }

        $stmt = $this->DB->prepare($sql);
        if ($from != '') $stmt->bindParam(":from", $from);
        if ($to != '') $stmt->bindParam(":to", $to);
        if ($id != '') $stmt->bindParam(":hid", $id);
        $stmt->execute();
        
        return ($id != '') ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tallennaHarjoitus()
    {
        $ak = new aikakalu();
        $lahtopaiva = $ak->fiDateToDbDate($_POST["strDate"]);
        list($year, $month, $day) = explode("-", $lahtopaiva);
        if (!checkdate($month, $day, $year)) {
            $aMsg[0] = "Päivämäärä on virheellinen!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
            return $aMsg;
        }

        $sql = (empty($_POST["harjoitusId"])) ? "INSERT INTO " : "UPDATE ";
        $sql.= "{$this->APP->TABLEPREFIX}lennot_harjoitukset SET ";
        $sql.= "paivamaara = :pvm, toiminnan_laatu = :toiml, kesto = :kesto, ";
        $sql.= "ifr_on = :ifron, dual_on = :dualon, harjoittelija_user_id = :huid, ";
        $sql.= "harjoittelija_peramies_user_id = :hpuid, opettaja_id = :ouid, huomautukset = :huom, ";
        $sql.= "kustannuspaikka_id = :kpid, syllabus_id = :syid, suoritukset = :suor, ";
        $sql.= "uudelleen = :uudelleen ";
        if (!empty($_POST["harjoitusId"])) $sql.= "WHERE id = :hid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":pvm", $lahtopaiva);
        $stmt->bindParam(":toiml", $_POST["intView"]);
        $stmt->bindParam(":kesto", $_POST["strDuration"]);
        $ifron = ($_POST["intIfr"] == 1) ? 1 : 0;
        $stmt->bindParam(":ifron", $ifron);
        $dualon = ($_POST["intDual"] == 1) ? 1 : 0;
        $stmt->bindParam(":dualon", $dualon);
        $stmt->bindParam(":huid", $_POST["intTraineeId"]);
        $stmt->bindParam(":hpuid", $_POST["intTrainee2Id"]);
        $stmt->bindParam(":ouid", $_POST["intTeacherId"]);
        $stmt->bindParam(":huom", $_POST["strNotes"]);
        $stmt->bindParam(":kpid", $_POST["intCostPoolId"]);
        $stmt->bindParam(":syid", $_POST["intSyllabusId"]);
        $suoritukset = implode(",", $_POST["intSuoritusId"]);
        $stmt->bindParam(":suor", $suoritukset);
        $uudelleen = ($_POST["intFlyAgain"] == 1) ? 1 : 0;
        $stmt->bindParam(":uudelleen", $uudelleen);
        if (!empty($_POST["harjoitusId"])) $stmt->bindParam(":hid", $_POST["harjoitusId"]);

        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/lennot.php?ID=69&PF=";
        $paluu.= (empty($_POST["harjoitusId"])) ? "1" : "3";
        header("Location: " . $paluu);
        exit();
    }

    public function poistaHarjoitus() {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}lennot_harjoitukset ";
        $sql.= "WHERE id = :hid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":hid", $_GET["harjoitusId"]);
        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/lennot.php?ID=69&PF=2";
        header("Location: " . $paluu);
        exit();
    }

    public function haeKayttajanRooli() {
        $sql = "SELECT roleid FROM {$this->APP->TABLEPREFIX}od_user ";
        $sql.= "WHERE userid = :uid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $_GET["userId"]);
        $stmt->execute();
        $roolirivi = $stmt->fetch(PDO::FETCH_ASSOC);
        return $roolirivi["roleid"];
    }

    public function haeLentokentat($id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lentokentat ";
        $sql.= (empty($id)) ? "ORDER BY nimi" : "WHERE id = :lkid";
        $stmt = $this->DB->prepare($sql);
        if (!empty($id)) $stmt->bindParam(":lkid", $id);
        $stmt->execute();
        return (!empty($id)) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} //End Of Class Statement
?>
