<?php
class viestit {

    function __construct() {
        global $DB,$APP,$USER;
        $this->DB   = $DB;
        $this->APP  = $APP;
        $this->USER = $USER;
        $this->TABLE_NOTE = $this->APP->TABLEPREFIX.'viestit';
        $this->TABLE_SMS = $this->TABLE_NOTE.'_sms';
        $this->TABLE_FOLDER = $this->APP->TABLEPREFIX.'viestikansiot';
        $this->TABLE_FOLDERNOTES = $this->APP->TABLEPREFIX.'viestikansio_sis';
        return true;
    }

    function haeKansio ($folder_id)
    {
        $sql = "SELECT * FROM {$this->TABLE_FOLDER} ";
        $sql.= "WHERE id = :fid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":fid", $folder_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function haeViesti ($viesti_id)
    {
        $sql = "SELECT * FROM {$this->TABLE_NOTE} ";
        $sql.= "WHERE id = :nid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":nid", $viesti_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function haeKayttajanKansiot ()
    {
        $sql = "SELECT * FROM {$this->TABLE_FOLDER} " .
               "WHERE user_id = 0 OR user_id = :uid AND esilla = 1 " .
               "ORDER BY id";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function haeKansionViestit ($id)
    {
        $sql = "SELECT fn.tarkea, fn.roskakori, n.* FROM {$this->TABLE_FOLDERNOTES} fn ";
        $sql.= "LEFT JOIN {$this->TABLE_NOTE} n ON fn.viesti_id = n.id ";
        $sql.= "WHERE fn.user_id = :uid AND fn.kansio_id = :fid AND fn.roskakori = :rval ";
        $sql.= "ORDER BY n.lahetetty_aika DESC";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->bindParam(":fid", $id);
        $stmt->bindValue(":rval", 0);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function haeKayttajat()
    {
        $param = $_GET["term"];
        
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}od_user ";
        $sql.= "WHERE email LIKE '%{$param}%'";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function haeKayttajatSms()
    {
        $param = $_GET["term"];

        $sql = "SELECT *, CONCAT(firstname,' ',lastname) AS nimi FROM {$this->APP->TABLEPREFIX}od_user ";
        $sql.= "WHERE CONCAT(firstname,' ',lastname) LIKE '%{$param}%'";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function haeKayttaja($id)
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}od_user ";
        $sql.= "WHERE userid = :uid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function lahetaViesti()
    {
        if (sizeof($_POST["receiver"]) > 0)
        {
            if (!empty($_POST["txtNote"]))
            {
                $calSet = false;
                // Tarkistetaan vielä onko mahdollisissa kalenterimerkinnöissä puutteita?
                if ($_POST["calOn"] == 1)
                {
                    $calSet = true;
                    $calOk = true;
                    // Tehdään simppelit tarkastukset tässä, sillä inputeissa on kuitenkin pickerit.
                    if (empty($_POST["calTopic"])) {
                        $aMsg[0] = "Kalenterimerkinnälle pitää antaa otsikko!";
                        $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                        $aMsg[2] = "ui-icon ui-icon-info";
                        $calOk = false;
                    }
                    if (empty($_POST["calDate"])) {
                        $aMsg[0] = "Tarkista kalenterimerkinnän päivämäärä!";
                        $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                        $aMsg[2] = "ui-icon ui-icon-info";
                        $calOk = false;
                    }
                    if (empty($_POST["calStartTime"]) || empty($_POST["calEndTime"])) {
                        $aMsg[0] = "Tarkista kalenterimerkinnän aloitus- ja lopetusaika!";
                        $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                        $aMsg[2] = "ui-icon ui-icon-info";
                        $calOk = false;
                    }
                    if (empty($_POST["calPlace"])) {
                        $aMsg[0] = "Tarkista kalenterimerkinnän paikka!";
                        $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                        $aMsg[2] = "ui-icon ui-icon-info";
                        $calOk = false;
                    }
                }

                if (!$calSet || ($calSet && $calOk)) {
                    // Tallennetaan viestin perustiedot (otsikko ja teksti)
                    $receivers = implode(",", $_POST["receiver"]);
                    $stamp = date("Y-m-d H:i:s");
                    $subject = $_POST["txtSubject"];
                    $note = $_POST["txtNote"];

                    $sql = "INSERT INTO {$this->TABLE_NOTE} SET ";
                    $sql.= "user_id = :uid, aihe = :subject, teksti = :note, ";
                    $sql.= "vastaanottajat = :receivers, lahetetty_aika = :stamp";

                    $stmt = $this->DB->prepare($sql);
                    $stmt->bindParam(":uid", $this->USER->ID);
                    $stmt->bindParam(":subject", $subject);
                    $stmt->bindParam(":note", $note);
                    $stmt->bindParam(":receivers", $receivers);
                    $stmt->bindParam(":stamp", $stamp);
                    $stmt->execute();
                    $row_id = $this->DB->lastInsertId();

                    // Sitten tehdään merkinnät lähettäjän lähetettyihin ja vastaanottajien saapuneisiin
                    $sql = "INSERT INTO {$this->TABLE_FOLDERNOTES} SET ";
                    $sql.= "user_id = :uid, kansio_id = :fid, viesti_id = :nid";
                    $stmt = $this->DB->prepare($sql);
                    $stmt->bindParam(":uid", $this->USER->ID);
                    $stmt->bindValue(":fid", 2);
                    $stmt->bindValue(":nid", $row_id);
                    $stmt->execute();

                    foreach ($_POST["receiver"] as $user_id) {
                        $sql = "INSERT INTO {$this->TABLE_FOLDERNOTES} SET ";
                        $sql.= "user_id = :uid, kansio_id = :fid, viesti_id = :nid";
                        $stmt = $this->DB->prepare($sql);
                        $stmt->bindParam(":uid", $user_id);
                        $stmt->bindValue(":fid", 1);
                        $stmt->bindValue(":nid", $row_id);
                        $stmt->execute();
                    }
                    $sPath = $_SERVER['PHP_SELF'].'?ID=32&PF=1&NID=' . $row_id;

                    if ($calSet)
                    {
                        // Tallennetaan kalenterimerkintä
                        
                        
                        /** Pitääkö merkintä vahvistaa */
                        $vahvistus = ($_POST["calConfirmParticipation"] == 1) ? 1 : 0;
                        list($paiva, $kuu, $vuosi) = explode(".", $_POST["calDate"]);
                        $aaika = $vuosi."-".$kuu."-".$paiva." ".$_POST["calStartTime"];
                        $laika = $vuosi."-".$kuu."-".$paiva." ".$_POST["calEndTime"];

                        $sql = "INSERT INTO {$this->APP->TABLEPREFIX}jqcalendar SET ";
                        $sql.= "user_id = :uid, Subject = :topic, tila_id = :tid, ";
                        $sql.= "Description = :ttieto, StartTime = :aaika, EndTime = :laika, ";
                        $sql.= "Color = :vkoodi, vahvistus = :vahv";

                        $stmt = $this->DB->prepare($sql);
                        $stmt->bindParam(":uid", $this->USER->ID);
                        $stmt->bindParam(":topic", htmlentities($_POST["calTopic"],ENT_QUOTES, "UTF-8"));
                        $stmt->bindParam(":tid", $_POST["calPlace"]);
                        $stmt->bindParam(":ttieto", htmlentities($_POST["calMorePreciseInfo"],ENT_QUOTES, "UTF-8"));
                        $stmt->bindParam(":aaika", $aaika);
                        $stmt->bindParam(":laika", $laika);
                        $stmt->bindParam(":vkoodi", $_POST["calColorCode"]);
                        $stmt->bindParam(":vahv", $vahvistus);
                        $stmt->execute();
                        $row_id = $this->DB->lastInsertId();
                        
                        // Lisätään kalenterimerkintäkutsu jokaiselle vastaanottajalle
                        foreach ($_POST["receiver"] as $user_id) {
                            $sql = "INSERT INTO {$this->APP->TABLEPREFIX}jqcalendar_requests SET ";
                            $sql.= "jqcalendar_id = :jqcid, kutsu_user_id = :kuid, vahvistettu = :vahv";
                            $stmt = $this->DB->prepare($sql);
                            $stmt->bindParam(":jqcid", $row_id);
                            $stmt->bindParam(":kuid", $user_id);
                            $stmt->bindValue(":vahv", 0);
                            $stmt->execute();
                        }
                        $sPath.= '&CM=1';
                    }

                    header("Location: $sPath");
                }
            }
            else
            {
                $aMsg[0] = "Et voi lähettää tyhjää viestiä!";
                $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                $aMsg[2] = "ui-icon ui-icon-info";
            }
        }
        else
        {
            $aMsg[0] = "Viestillä tulee olla ainakin yksi vastaanottaja!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
        }
        
        return $aMsg;
    }

    /*
     *  Viestin asettaminen tärkeäksi
     *  $_GET -params: ID & FID & note_id & set
     */
    function asetaTarkeaksi($mode=1)
    {
        $sql = "UPDATE {$this->TABLE_FOLDERNOTES} SET ";
        $sql.= "tarkea = :tval ";
        $sql.= "WHERE viesti_id = :vid AND user_id = :uid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":tval", $mode);
        $stmt->bindParam(":vid", $_GET["note_id"]);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'] . "?ID=" . $_GET["ID"] . "&FID=" . $_GET["FID"] . "&PF=";
        $sPath.= ($mode == 1) ? "1" : "2";
        header("location: " . $sPath);
    }

    function siirraRoskakoriin()
    {
        $sql = "UPDATE {$this->TABLE_FOLDERNOTES} SET ";
        $sql.= "roskakori = :rval ";
        $sql.= "WHERE viesti_id = :vid AND user_id = :uid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindValue(":rval", 1);
        $stmt->bindParam(":vid", $_GET["note_id"]);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'] . "?ID=" . $_GET["ID"] . "&FID=" . $_GET["FID"] . "&PF=3";
        header("location: " . $sPath);
    }

    /*
     * Luodaan käyttäjäkohtainen viestikansio
     */
    function luoKansio()
    {
        // Tarkastetaan ensin, ettei tällaista kansiota löydy
        $sql = "SELECT * FROM {$this->TABLE_FOLDER} ";
        $sql.= "WHERE (nimi = :name AND user_id = :uid) ";
        $sql.= "OR (nimi = :name AND user_id = :nulluid)";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":name", $_POST["txtFolderName"]);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->bindValue(":nulluid", 0);

        $stmt->execute();
        $fRows = $stmt->fetchAll();

        if (!$fRows)
        {
            // Ei löydy - kansio voidaan luoda
            $sql = "INSERT INTO {$this->TABLE_FOLDER} SET ";
            $sql.= "nimi = :name, user_id = :uid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":name", $_POST["txtFolderName"]);
            $stmt->bindParam(":uid", $this->USER->ID);
            $stmt->execute();

            $row_id = $this->DB->lastInsertId();
            
            $sPath = $_SERVER['PHP_SELF'] . "?ID=31&FID=" . $row_id . "&PF=4";
            header("location: " . $sPath);
        }
        else
        {
            $aMsg[0] = "Olet jo luonut samannimisen kansion!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";

            return $aMsg;
        }
    }

    /*
     * Haetaan kansiot, jotka eivät ole valittuna sivulle.
     */
    function haeKansiotValintaListaan($ruleout)
    {
        $sql = "SELECT * FROM {$this->TABLE_FOLDER} ";
        $sql.= "WHERE id != :ruleout AND (user_id = :uid OR user_id = :nuid)";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":ruleout", $ruleout);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->bindValue(":nuid", 0);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Viestien siirto toiseen kansioon
     */
    function siirraViestit()
    {
        if (sizeof($_POST["viesti_id"]) > 0)
        {
            $moved = 0;
            foreach ($_POST["viesti_id"] as $viesti_id => $val)
            {
                if ($val == "1")
                {
                    $sql = "UPDATE {$this->TABLE_FOLDERNOTES} SET ";
                    $sql.= "kansio_id = :fid WHERE ";
                    $sql.= "viesti_id = :nid AND user_id = :uid";

                    $stmt = $this->DB->prepare($sql);
                    $stmt->bindParam(":fid", $_POST["tgtFolderId"]);
                    $stmt->bindParam(":nid", $viesti_id);
                    $stmt->bindParam(":uid", $this->USER->ID);
                    $stmt->execute();

                    $moved++;
                }
            }
            $sPath = $_SERVER['PHP_SELF'] . "?ID=31&FID=" . $_POST["tgtFolderId"] . "&AM=" . $moved . "&PF=5";
            header("location: " . $sPath);
        }
        else
        {
            $aMsg[0] = "Vähintään yksi viesti tulee valita, jotta siirto voidaan tehdä!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
        }
        
        return $aMsg;
    }

    /*
     * Tekstiviestin lähetyksessä kutsuttava funktio.
     * Tarvittavat POST -muuttujat:
     * receiver (array; vastaanottajien id:t)
     * smsTxt (string; lähetettävä viesti)
     */

    function sendSms()
    {
        if (is_array($_POST["receiver"]))
        {
            $receivers = array_unique($_POST["receiver"]);
            $rec_list = implode(",", $receivers);
            $smstxt = $_POST["smsTxt"];

            $sql = "INSERT INTO {$this->TABLE_SMS} SET ";
            $sql.= "user_id = :uid, receiver_ids = :rids, ";
            $sql.= "sent_ids = '', message = :msg, sent_time = :stamp";

            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":uid", $this->USER->ID);
            $stmt->bindParam(":rids", $rec_list);
            $stmt->bindParam(":msg", $smstxt);
            $stmt->bindValue(":stamp", date("Y-m-d H:i:s"));
            $stmt->execute();

            $row_id = $this->DB->lastInsertId();
            $labResponse = $this->putSms($row_id); // Palauttaa array:n, jos vähintään yksi onnistunut lähetys.

            $sms_count_sent = 0;
            if (is_array($labResponse))
            {
                // Montako viestiä onnistuttiin lähettämään ja _kenelle_. Päivitetään kantaan vastaanottajat.
                $sms_count_sent = $labResponse["success_count"];
                $onnistuneet = array();
                $i = 0;
                foreach ($labResponse as $number => $sent) {
                    if ($number != "success_count") {
                        if ($sent) {

                            $sql = "SELECT userid FROM {$this->APP->TABLEPREFIX}od_user WHERE puhelin = '{$number}'";
                            $stmt = $this->DB->prepare($sql);
                            $stmt->execute();
                            $uRow = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($uRow) {
                                $onnistuneet[$i] = $uRow["userid"];
                                $i++;
                            }
                        }
                    }
                }

                $olista = implode(",", $onnistuneet);
                $sql = "UPDATE {$this->TABLE_SMS} SET sent_ids = '{$olista}' WHERE id = {$row_id}";
                $stmt = $this->DB->prepare($sql);
                $stmt->execute();
            }

            $sPath = $this->APP->BASEURL."/viestit.php?ID=36&PF=1&SCT=".$sms_count_sent;
            header("location: " . $sPath);
        }
        else
        {
            $aMsg[0] = "Vähintään yksi vastaanottaja on valittava!";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-info";
        }

        return $aMsg;
    }

    /*
     * Funkkarilla laitetaan sms -viesti palveluntarjoajan palvelimelle päin
     * $dbRow (int; tietokantataulun rivin id)
     */
    function putSms($dbRow)
    {
        $numbers = array();
        $message = "";

        // Haetaan ensin tietokantataulusta vastaanottajat ja sitten niiden numerot
        $sql = "SELECT receiver_ids, message FROM {$this->TABLE_SMS} ";
        $sql.= "WHERE id = :smsid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":smsid", $dbRow);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT puhelin FROM {$this->APP->TABLEPREFIX}od_user ";
        $sql.= "WHERE userid IN ({$row[receiver_ids]})";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        foreach ($rows as $prow) {
            if (trim($prow["puhelin"])!="") $numbers[] = $prow["puhelin"];
        }
        $message = $row["message"]; // Kirjoitettu viesti
        $sentsmss = 0; // Onnistuneiden lähetysten määrä
        
        if (sizeof($numbers) > 0)
        {
            require "./lib/sms-gw.php";
            $sentsmss = sms_send($numbers, $message);
        }

        return $sentsmss;
    }

    /*
     * Haetaan 5 viimeistä lähetettyä tekstiviestiä
     */
    function haeViimeisimmatSmst()
    {
        $sql = "SELECT * FROM {$this->TABLE_SMS} ";
        $sql.= "WHERE user_id = :uid ";
        $sql.= "ORDER BY sent_time DESC";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $this->USER->ID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Haetaan tilat; tarvitaan kalenterimerkintää varten
     */
    function haeTilat()
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tilat";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} //End Of Class Statement
?>
