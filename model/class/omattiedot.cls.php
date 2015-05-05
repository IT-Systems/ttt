<?php 
class omattiedot {

    function __construct() {
        global $DB,$APP,$USER;
        $this->DB   = $DB;
        $this->APP  = $APP;
        $this->USER = $USER;
        return true;
    }

    function haeOmatTiedot() {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->execute();
        $omat_tiedot = $oStmtData->fetch();
        return $omat_tiedot;
    }

    function tallennaYhteystiedot() {
        $sSqlData = "UPDATE {$this->APP->TABLEPREFIX}od_user " .
                "SET email = :email, puhelin = :puhelin, katuosoite = :katuosoite, postinumero = :postinumero, kaupunki = :kaupunki " .
                "WHERE userid = :userid";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->bindParam(':email', $_POST['sahkoposti']);
        $oStmtData->bindParam(':puhelin', $_POST['puhelin']);
        $oStmtData->bindParam(':katuosoite', $_POST['katuosoite']);
        $oStmtData->bindParam(':postinumero', $_POST['postinumero']);
        $oStmtData->bindParam(':kaupunki', $_POST['kaupunki']);
        $oStmtData->execute();
        $sPath = $_SERVER['PHP_SELF'] . '?ID=10&PF=1';
        header("Location: $sPath");
    }

    function haeKustannuspaikat() {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}kustannuspaikat";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->execute();
        $kustannuspaikat = $oStmtData->fetchAll();
        return $kustannuspaikat;
    }

    function tallennaMuutTiedot() {
        $kouluttaja = '0';

        if ($_POST['kouluttajana'] == 'on') {
            $kouluttaja = '1';
        }

        $sSqlData = "UPDATE {$this->APP->TABLEPREFIX}od_user " .
                "SET oletuskustp = :oletuskustp, lupakirja = :lupakirja, kouluttaja = :kouluttaja " .
                "WHERE userid = :userid";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->bindParam(':oletuskustp', $_POST['kustannuspaikka']);
        $oStmtData->bindParam(':lupakirja', $_POST['lupakirja']);
        $oStmtData->bindParam(':kouluttaja', $kouluttaja);
        $oStmtData->execute();
        $sPath = $_SERVER['PHP_SELF'] . '?ID=10&PF=2';
        header("Location: $sPath");
    }

    function haeSyllabukset() {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabukset";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->execute();
        $syllabukset = $oStmtData->fetchAll();
        return $syllabukset;
    }

    function tallennaSyllabus() {
        $sSqlData = "UPDATE {$this->APP->TABLEPREFIX}od_user " .
                "SET syllabus = :syllabus " .
                "WHERE userid = :userid";
        $oStmtData= $this->DB->prepare($sSqlData);
        $oStmtData->bindParam(':userid', $this->USER->ID);
        $oStmtData->bindParam(':syllabus', $_POST['syllabus']);
        $oStmtData->execute();
        $sPath = $_SERVER['PHP_SELF'] . '?ID=10&PF=4';
        header("Location: $sPath");
    }

    function tallennaKuva() {
        // Kuvan tallennus, vain jpg ja png ok.
        extract($_FILES);
        $valids = array("png", "jpg", "jpeg");
        $itype = explode("/", $kuva["type"]);

        if ($kuva["error"] == UPLOAD_ERR_OK && in_array($itype[1], $valids)) {
            // Alustukset
            $uploads_dir = 'files/userimages';
            $name = $kuva["name"];
            $tmp_name = $kuva["tmp_name"];

            // Katsotaan poistetaanko (unlink) vanhaa kuvaa.
            $sql = "SELECT kuva FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :uid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(':uid', $this->USER->ID);
            $stmt->execute();
            $uRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($uRow["kuva"])) {
                $old_name = $uRow["kuva"];
                if (file_exists("$uploads_dir/$old_name")) unlink ("$uploads_dir/$old_name");
            }

            // Tarkistetaan löytyykö samanlaista kuvaa.
            if (file_exists("$uploads_dir/$name")) {
                // randomstr kuvan perään ennen tiedostopäätettä
                $fparts = explode('.', $name);
                $fpartsls = sizeof($fparts) - 1;
                $name_woext = ''; // Nimi ilman tiedostopäätettä

                $i = 0;
                foreach ($fparts as $part) {
                    if ($fpartsls > $i) $name_woext.= $part;
                    $i++;
                }

                $found = false;
                while ($found == false) {
                    $name = $name_woext . '_' . $this->createRandomString(3) . '.' . $fparts[$fpartsls];
                    if (!file_exists("$uploads_dir/$name")) $found = true;
                }
            }

            // Tallennetaan kuva palvelimelle
            include('SimpleImage.cls.php');
            $image = new SimpleImage();
            $image->load($tmp_name);
            $image->resizeToWidth(100);
            $image->save("$uploads_dir/$name");

            // Päivitetään tieto kantaan
            $sql = "UPDATE {$this->APP->TABLEPREFIX}od_user SET kuva = :kuva WHERE userid = :uid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(':kuva', $name);
            $stmt->bindParam(':uid', $this->USER->ID);
            $stmt->execute();

            $sPath = $_SERVER['PHP_SELF'] . '?ID=10&PF=5';
            header("Location: $sPath");
        }
        else {
            if ($_POST["poistakuva"] == 1) {
                $sql = "SELECT kuva FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :uid";
                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(':uid', $this->USER->ID);
                $stmt->execute();
                $uRow = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!empty($uRow["kuva"])) {
                    $old_name = $uRow["kuva"];
                    if (file_exists("$uploads_dir/$old_name")) unlink ("$uploads_dir/$old_name");
                }
                $sql = "UPDATE {$this->APP->TABLEPREFIX}od_user SET kuva = :kuva WHERE userid = :uid";
                $stmt = $this->DB->prepare($sql);
                $stmt->bindValue(':kuva', '');
                $stmt->bindParam(':uid', $this->USER->ID);
                $stmt->execute();

                $sPath = $_SERVER['PHP_SELF'] . '?ID=10&PF=7';
                header("Location: $sPath");
            }
            elseif (!empty($_FILES["kuva"]["name"])) {
                $sPath = $_SERVER['PHP_SELF'] . '?ID=10&PF=6';
                header("Location: $sPath");
            }
            else {
                $sPath = $_SERVER['PHP_SELF'] . '?ID=10&PF=8';
                header("Location: $sPath");
            }
        }
    }

    function createRandomString($length=4) {
        $str = '';
        $chars = '1234567890';
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $chars.= $letters . strtoupper($letters);
        $charsl = sizeof($chars) - 1;

        for ($i = 0; $i < $length; $i++) $str.= $chars[mt_rand(0, $charsl)];

        return $str;
    }

    function vaihdaSalasana() {
        // Tarkistetaan syötetyt salasanat
        $sql = "SELECT password FROM {$this->APP->TABLEPREFIX}od_user ";
        $sql.= "WHERE userid = :uid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();
        $uRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $oldpw = md5($_POST["txtOldPassword"]);

        // Väärin syötetty salasana
        if ($oldpw != $uRow["password"]) {
            $sPath = $this->APP->BASEURL . '/omattiedot.php?ID=46&PF=4';
            header("Location: $sPath");
            exit();
        }

        // Uudet salasanat eivät mätsää
        if ($_POST["txtNewPassword2"] != $_POST["txtNewPassword"]) {
            $sPath = $this->APP->BASEURL . '/omattiedot.php?ID=46&PF=2';
            header("Location: $sPath");
            exit();
        }

        // Vanha ja uusi salasana ovat samoja
        if ($_POST["txtNewPassword"] == $_POST["txtOldPassword"]) {
            $sPath = $this->APP->BASEURL . '/omattiedot.php?ID=46&PF=3';
            header("Location: $sPath");
            exit();
        }

        $newpw = md5($_POST["txtNewPassword"]);

        $sql = "UPDATE {$this->APP->TABLEPREFIX}od_user SET ";
        $sql.= "password = :pw WHERE userid = :uid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":pw", $newpw);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();

        $sPath = $this->APP->BASEURL . '/omattiedot.php?ID=46&PF=1';
        header("Location: $sPath");
        exit();
    }

    function haeKayttajanKelpuutukset($id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kelpuutukset_kayttaja ";
        $sql.= "WHERE user_id = :uid";
        if (!empty($id)) $sql.= " AND id = :kkid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $this->USER->ID);
        if (!empty($id)) $stmt->bindParam(":kkid", $id);
        $stmt->execute();

        return (!empty($id)) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function lisaaKelpuutusKayttajalle() {

        $sql = (empty($_POST["ownAuthId"])) ? "INSERT INTO " : "UPDATE ";
        $sql.= "{$this->APP->TABLEPREFIX}kelpuutukset_kayttaja SET ";
        $sql.= "kelpuutus_id = :kid, vanhenee = :stamppi";
        $sql.= (empty($_POST["ownAuthId"])) ? ", user_id = :uid" : " WHERE id = :kkid";
        $stmt = $this->DB->prepare($sql);

        $ak = new aikakalu();
        $stmt->bindParam(":kid", $_POST["selAuthorization"]);
        $stmt->bindParam(":stamppi", $ak->fiDateToDbDate($_POST["dateValidUntil"]));
        if (empty($_POST["ownAuthId"])) {
            $stmt->bindParam(":uid", $this->USER->ID);
        }
        else {
            $stmt->bindParam(":kkid", $_POST["ownAuthId"]);
        }
        $stmt->execute();

        $sPath = $this->APP->BASEURL . '/omattiedot.php?ID=48&PF=';
        $sPath.= (empty($_POST["ownAuthId"])) ? "1" : "2";
        header("Location: $sPath");
        exit();

    }

    function haeKelpuutukset($kelp_id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kelpuutukset ";
        if (!empty($kelp_id)) $sql.= "WHERE id = :kid";
        $stmt = $this->DB->prepare($sql);
        if (!empty($kelp_id)) $stmt->bindParam(":kid", $kelp_id);
        $stmt->execute();

        return (!empty($kelp_id)) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function poistaKayttajanKelpuutus() {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}kelpuutukset_kayttaja ";
        $sql.= "WHERE id = :kkid AND user_id = :uid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":kkid", $_GET["omakelp_id"]);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();


        $sPath = $this->APP->BASEURL . '/omattiedot.php?ID=48&PF=3';
        header("Location: " . $sPath);
        exit();
    }

    public function haeKayttajat() {
        $sql = "SELECT u.*, r.rolename FROM {$this->APP->TABLEPREFIX}od_user u ";
        $sql.= "LEFT JOIN {$this->APP->TABLEPREFIX}od_role r ON u.roleid = r.roleid ";
        $sql.= "ORDER BY u.username ";
        $sql.= ($_GET["order"] == "down" || empty($_GET["order"])) ? "ASC" : "DESC";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Haetaan käyttäjän syöttämistä lennoista tarvittavat tiedot lentokokemussivun sarakkeisiin
    */
    public function haeLentojenKokemukset() {
        $sql = "SELECT paallikko_user_id, peramies_user_id, valvoja_user_id, lentoonlahtoja_paiva, ";
        $sql.= "lentoonlahtoja_yo, laskeutumisia_paiva, laskeutumisia_yo, ";
        $sql.= "lentoaika, ifr_aika_on, ifr_aika, yoaika_on, yoaika, kone_id ";
        $sql.= "FROM {$this->APP->TABLEPREFIX}lennot ";
        $sql.= "WHERE paallikko_user_id = :kuid OR peramies_user_id = :druid OR valvoja_user_id = :spruid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":kuid", $this->USER->ID);
        $stmt->bindParam(":druid", $this->USER->ID);
        $stmt->bindParam(":spruid", $this->USER->ID);
        $stmt->execute();

        $lennot = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $hal = new hallinta();
        $kokemukset = array("kokonais" => 0, "ifr" => 0, "yo" => 0, "sim" => 0,
                "pic" => 0, "cop" => 0, "dual" => 0, "teach" => 0,
                "toffs_day" => 0, "toffs_night" => 0, "lands_day" => 0,
                "lands_night" => 0, "supervisor" => 0, "konetyypit" => array());
        $konetyypit = $hal->haeKonetyypit();
        foreach ($konetyypit as $key => $val) {
            $kokemukset["konetyypit"][$val["id"]] = 0;
        }

        $ak = new aikakalu();
        foreach ($lennot as $lento) {
            // Lasketaan "peruslentokokemukset" jos henkilö on koneessa (ei valvoja)
            if ($lento["paallikko_user_id"] == $this->USER->ID || $lento["peramies_user_id"] == $this->USER->ID) {
                $kokemukset["kokonais"] += $ak->muunnaAikaMinuuteiksi($lento["lentoaika"]);

                if ($lento["ifr_aika_on"] == 1) $kokemukset["ifr"] += $ak->muunnaAikaMinuuteiksi($lento["ifr_aika"]);
                if ($lento["yoaika_on"] == 1) $kokemukset["yo"] += $ak->muunnaAikaMinuuteiksi($lento["yoaika"]);

                $kokemukset["toffs_day"] += $lento["lentoonlahtoja_paiva"];
                $kokemukset["toffs_night"] += $lento["lentoonlahtoja_yo"];
                $kokemukset["lands_day"] += $lento["laskeutumisia_paiva"];
                $kokemukset["lands_night"] += $lento["laskeutumisia_yo"];
                
                $kone = $hal->haeKoneet($lento["kone_id"]);
                $kokemukset["konetyypit"][$kone["konetyyppi"]] += $ak->muunnaAikaMinuuteiksi($lento["lentoaika"]);
            }

            if ($lento["paallikko_user_id"] == $this->USER->ID) {
                $kokemukset["pic"] += $ak->muunnaAikaMinuuteiksi($lento["lentoaika"]);
                $kokemukset["teach"] += $ak->muunnaAikaMinuuteiksi($lento["lentoaika"]);
            }

            if ($lento["peramies_user_id"] == $this->USER->ID) {
                $kokemukset["cop"] += $ak->muunnaAikaMinuuteiksi($lento["lentoaika"]);
                $kokemukset["dual"] += $ak->muunnaAikaMinuuteiksi($lento["lentoaika"]);
            }
            
            if ($lento["valvoja_user_id"] == $this->USER->ID) {
                $kokemukset["supervisor"]++;
            }
        }

        return $kokemukset;
    }

    public function haeMuutoksetKokemuksiin() {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kokemukset_muutokset ";
        $sql.= "WHERE kayttaja_id = :kuid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":kuid", $this->USER->ID);
        $stmt->execute();
        $muutokset = $stmt->fetch(PDO::FETCH_ASSOC);

        $kokemukset = array("kokonais" => 0, "ifr" => 0, "yo" => 0, "sim" => 0,
                "pic" => 0, "cop" => 0, "dual" => 0, "teach" => 0,
                "toffs_day" => 0, "toffs_night" => 0, "lands_day" => 0,
                "lands_night" => 0, "supervisor" => 0, "konetyypit" => array());

        if ($muutokset) {
            $kokemukset["kokonais"] = $muutokset["kokonais_aika"];
            $kokemukset["ifr"] = $muutokset["ifr_aika"];
            $kokemukset["yo"] = $muutokset["yo_aika"];
            $kokemukset["pic"] = $muutokset["pic_aika"];
            $kokemukset["teach"] = $muutokset["opettaja_aika"];
            $kokemukset["cop"] = $muutokset["cop_aika"];
            $kokemukset["dual"] = $muutokset["dual_aika"];
            $kokemukset["toffs_day"] = $muutokset["lahtoja_paiva_aikana"];
            $kokemukset["toffs_night"] = $muutokset["lahtoja_yo_aikana"];
            $kokemukset["lands_day"] = $muutokset["laskuja_paiva_aikana"];
            $kokemukset["lands_night"] = $muutokset["laskuja_yo_aikana"];
            $kokemukset["supervisor"] = $muutokset["valvojana"];
        }

        $sql = "SELECT konetyyppi_id, muutos_laskennalliseen FROM {$this->APP->TABLEPREFIX}kokemukset_konetyypit ";
        $sql.= "WHERE kayttaja_id = :uid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();
        $kt_kok = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($kt_kok as $key => $val) {
            $kokemukset["konetyypit"][$val["konetyyppi_id"]] = $val["muutos_laskennalliseen"];
        }

        return $kokemukset;
    }

    public function haeKonetyyppiKokemukset($konetyypit) {
        $kokemukset = array();
        foreach ($konetyypit as $type) {
            $sql = "SELECT kokemus_aika_mins FROM {$this->APP->TABLEPREFIX}kokemukset_konetyypit ";
            $sql.= "WHERE kayttaja_id = :kuid AND konetyyppi_id = :ktid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":kuid", $this->USER->ID);
            $stmt->bindParam(":ktid", $type["id"]);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                $kokemukset[$type["id"]] = 0;
            }
            else {
                $kokemukset[$type["id"]] = $row["kokemus_aika_mins"];
            }
        }
        return $kokemukset;
    }

    public function tallennaKokemusMuutokset($laskettu) {
        $muutos = array();
        $ak = new aikakalu();

        if (!$ak->tarkistaSyotettyAikaMaare($_POST["strExpAll"]) || !$ak->tarkistaSyotettyAikaMaare($_POST["strExpIfr"]) || !$ak->tarkistaSyotettyAikaMaare($_POST["strExpNight"]) ||
        !$ak->tarkistaSyotettyAikaMaare($_POST["strExpSimulator"]) || !$ak->tarkistaSyotettyAikaMaare($_POST["strExpPic"]) || !$ak->tarkistaSyotettyAikaMaare($_POST["strExpCop"]) ||
        !$ak->tarkistaSyotettyAikaMaare($_POST["strExpDual"]) || !$ak->tarkistaSyotettyAikaMaare($_POST["strExpTeacher"])) {
            $aMsg[0] = "Aikasyötteissä virheellisyyksiä! Arvoja ei tallennettu. Tarkista, että syöttämäsi tiedot ovat (hh:mm) -muodossa, ja yritä tallentaa uudelleen.";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
            return $aMsg;
        }
        foreach($_POST["strExpPlaneType"] as $type => $timeval) {
            if (!$ak->tarkistaSyotettyAikaMaare($timeval)) {
                $aMsg[0] = "Konekohtaisissa aikasyötteissä on virheellisyyksiä! Arvoja ei tallennettu. Tarkista, että syöttämäsi tiedot ovat (hh:mm) -muodossa, ja yritä tallentaa uudelleen.";
                $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                $aMsg[2] = "ui-icon ui-icon-info";
            }
        }

        $muutos["kok"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpAll"]) - $laskettu["kokonais"];
        $muutos["ifr"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpIfr"]) - $laskettu["ifr"];
        $muutos["yo"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpNight"]) - $laskettu["yo"];
        $muutos["sim"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpSimulator"]) - $laskettu["sim"];
        $muutos["pic"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpPic"]) - $laskettu["pic"];
        $muutos["cop"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpCop"]) - $laskettu["cop"];
        $muutos["dual"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpDual"]) - $laskettu["dual"];
        $muutos["teach"] = $ak->muunnaAikaMinuuteiksi($_POST["strExpTeacher"]) - $laskettu["teach"];
        $muutos["lahp"] = $_POST["strExpTakeoffsDay"] - $laskettu["toffs_day"];
        $muutos["lahy"] = $_POST["strExpTakeoffsNight"] - $laskettu["toffs_night"];
        $muutos["lasp"] = $_POST["strExpLandingsDay"] - $laskettu["lands_day"];
        $muutos["lasy"] = $_POST["strExpLandingsNight"] - $laskettu["lands_night"];
        if (isset($_POST["strExpSupervisor"])) $muutos["valv"] = $_POST["strExpSupervisor"] - $laskettu["supervisor"];
        foreach ($_POST["strExpPlaneType"] as $type => $timeval) {
            $muutos["konetyypit"][$type] = $ak->muunnaAikaMinuuteiksi($timeval) - $laskettu["konetyypit"][$type];
        }

        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kokemukset_muutokset ";
        $sql.= "WHERE kayttaja_id = :kuid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":kuid", $this->USER->ID);
        $stmt->execute();
        $row = $stmt->fetch();

        $sql = (!$row) ? "INSERT INTO " : "UPDATE ";
        $sql.= "{$this->APP->TABLEPREFIX}kokemukset_muutokset SET ";
        $sql.= "kokonais_aika = :koka, ifr_aika = :ifra, yo_aika = :yoa, ";
        $sql.= "simulaattori_aika = :sima, pic_aika = :pica, cop_aika = :copa, ";
        $sql.= "dual_aika = :duaa, opettaja_aika = :opea, lahtoja_paiva_aikana = :lahpa, ";
        $sql.= "lahtoja_yo_aikana = :lahya, laskuja_paiva_aikana = :laspa, laskuja_yo_aikana = :lasya";
        if (isset($muutos["valv"])) $sql.= ", valvojana = :valva";
        $sql.= (!$row) ? ", kayttaja_id = :kuid" : " WHERE kayttaja_id = :kuid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":koka", $muutos["kok"]);
        $stmt->bindParam(":ifra", $muutos["ifr"]);
        $stmt->bindParam(":yoa", $muutos["yo"]);
        $stmt->bindParam(":sima", $muutos["sim"]);
        $stmt->bindParam(":pica", $muutos["pic"]);
        $stmt->bindParam(":copa", $muutos["cop"]);
        $stmt->bindParam(":duaa", $muutos["dual"]);
        $stmt->bindParam(":opea", $muutos["teach"]);
        $stmt->bindParam(":lahpa", $muutos["lahp"]);
        $stmt->bindParam(":lahya", $muutos["lahy"]);
        $stmt->bindParam(":laspa", $muutos["lasp"]);
        $stmt->bindParam(":lasya", $muutos["lasy"]);
        if (isset($muutos["valv"])) $stmt->bindParam(":valva", $muutos["valv"]);
        $stmt->bindParam(":kuid", $this->USER->ID);
        $stmt->execute();

        foreach ($muutos["konetyypit"] as $type => $muutos) {
            $sql = "SELECT id FROM {$this->APP->TABLEPREFIX}kokemukset_konetyypit ";
            $sql.= "WHERE kayttaja_id = :uid AND konetyyppi_id = :ktid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":uid", $this->USER->ID);
            $stmt->bindParam(":ktid", $type);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = (!$row) ? "INSERT INTO " : "UPDATE ";
            $sql.= "{$this->APP->TABLEPREFIX}kokemukset_konetyypit SET ";
            $sql.= "muutos_laskennalliseen = :muutos";
            $sql.= (!$row) ? ", kayttaja_id = :uid, konetyyppi_id = :ktid" : " WHERE id = :kkid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":muutos", $muutos);
            if (!$row) {
                $stmt->bindParam(":uid", $this->USER->ID);
                $stmt->bindParam(":ktid", $type);
            }
            else {
                $stmt->bindParam(":kkid", $row["id"]);
            }
            $stmt->execute();
        }
    }

    public function tallennaKonetyyppiKokemukset() {
        $ak = new aikakalu();
        $clean = true;

        foreach ($_POST["strExpPlaneType"] as $type => $timeval) {
            if (!$ak->tarkistaSyotettyAikaMaare($timeval)) {
                $clean = false;
            }
            else {
                $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kokemukset_konetyypit ";
                $sql.= "WHERE kayttaja_id = :kuid AND konetyyppi_id = :ktid";
                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(":kuid", $this->USER->ID);
                $stmt->bindParam(":ktid", $type);
                $stmt->execute();
                $row = $stmt->fetch();

                $minutes = $ak->muunnaAikaMinuuteiksi($timeval);
                $sql = (!$row) ? "INSERT INTO " : "UPDATE ";
                $sql.= "{$this->APP->TABLEPREFIX}kokemukset_konetyypit SET ";
                $sql.= "kokemus_aika_mins = :kamins";
                $sql.= (!$row) ? ", kayttaja_id = :kuid, konetyyppi_id = :ktid" : " WHERE kayttaja_id = :kuid AND konetyyppi_id = :ktid";

                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(":kamins", $minutes);
                $stmt->bindParam(":kuid", $this->USER->ID);
                $stmt->bindParam(":ktid", $type);
                $stmt->execute();
            }
        }

        if (!$clean) {
            $aMsg[0] = "Jossain konetyyppikohtaisessa ajassa virheellisyyksiä! Ko. arvoa ei tallennettu. Tarkista, että syöttämäsi tiedot ovat (hh:mm) -muodossa, ja yritä tallentaa uudelleen.";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
            return $aMsg;
        }
    }

    public function getStudentsSyllabuses($id='') {
        $likes = array();
        $likes[] = $id;
        $likes[] = $id.',%';
        $likes[] = '%,'.$id.',%';
        $likes[] = '%,'.$id;

        $sql = "SELECT kurssi_syllabus_id AS syllabus FROM {$this->APP->TABLEPREFIX}kurssi WHERE ";
        foreach ($likes as $like) $sql.= "kurssi_oppilaat LIKE '" . $like . "' OR ";
        $sql = substr($sql, 0, -3);
        $sql.= "GROUP BY kurssi_syllabus_id";

        $stmt = $this->DB->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Checks whether a task within syllabus is completed succesfully
     */

    public function syllabusTaskAccepted($userid, $taskid) {
        // its_lennot -table, search if $taskid exists in suoritus column and uudelleen -column value is 0.
        $likes = array();
        $likes[] = $taskid;
        $likes[] = $taskid.',%';
        $likes[] = '%,'.$taskid.',%';
        $likes[] = '%,'.$taskid;

        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot WHERE (";
        foreach ($likes as $like) $sql.= "suoritus LIKE '" . $like . "' OR ";
        $sql = substr($sql, 0, -4) . ")";
        $sql.= "AND (peramies_user_id = :uid OR paallikko_user_id = :uid) AND uudelleen = 0";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $userid);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) {
            // Let's check from harjoitukset as well
            $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}lennot_harjoitukset WHERE (";
            foreach ($likes as $like) $sql.= "suoritukset LIKE '" . $like . "' OR ";
            $sql = substr($sql, 0, -4) . ")";
            $sql.= "AND (harjoittelija_user_id = :uid OR harjoittelija_peramies_user_id = :uid) ";
            $sql.= "AND uudelleen = 0";
            
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":uid", $userid);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) {
                return false;
            }
        }

        return true;
    }
} //End Of Class Statement
?>
