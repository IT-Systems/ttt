<?php
/*------------------------------------------------------------------------------+
 * Opendelight - A PHP based Rapid Web Application Development Framework        |
 * (c)Copyright ADII Research & Applications (P) Limited. All rights reserved.  |
 * Author: Ashwini Kumar Rath                                                   |
 * Website of Opendelight: http://www.adiipl.com/opendelight                    |
 * Licensed under the terms of the GNU General Public License Version 2 or later|
 * (the "GPL"): http://www.gnu.org/licenses/gpl.html                            |
 * NOTE: The copyright notice like this on any of the distributed files         |
 *       (downloaded or obtained as the part of Opendelight) must NOT be        |
 *       removed or modified.                                                   |
 *------------------------------------------------------------------------------+
*/
class hallinta {
    /* CONSTRUCTOR */
    function __construct()
    {
        global $DB,$APP,$USER;
        $this->DB   = $DB;
        $this->APP  = $APP;
        $this->USER = $USER;
        return true;
    }

    /* List users */
    function listPage()
    {
        $sSqlData = "SELECT userid, firstname, lastname, username, email, lastlogin, userstatus, roleid FROM {$this->APP->TABLEPREFIX}od_user WHERE userid <> ''";
        $oStmt    = $this->DB->prepare($sSqlData);
        $oStmt->execute();
        $aRowData = $oStmt->fetchAll();
        return $aRowData;
    }

    /* Get Role name of user */
    function getRoleName($iRoleIDs)
    {
        $aRoleIDs  = explode(',', $iRoleIDs);
        $sRoleName = '';
        foreach($aRoleIDs as $iRoleID) {
            $sSqlData = "SELECT rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid = :roleid";
            $oStmt    = $this->DB->prepare($sSqlData);
            $oStmt->bindParam(':roleid', $iRoleID);
            $oStmt->execute();
            $aRowData = $oStmt->fetchAll();
            foreach($aRowData as $aRow) {
                $sRoleName .= stripslashes($aRow[rolename]).',';
            }
        }
        $sRoleName = rtrim($sRoleName,',');
        return $sRoleName;
    }

    /* Add User */
    function lisaaKayttaja()
    {
        if ($_POST['txtUsername'] != 'admin') {
            $sSqlData = "SELECT username, email FROM {$this->APP->TABLEPREFIX}od_user WHERE username = :username OR email = :email";
            $oStmtData= $this->DB->prepare($sSqlData);
            $oStmtData->bindParam(':username', $_POST['txtUsername']);
            $oStmtData->bindParam(':email', $_POST['txtEmail']);
            $oStmtData->execute();
            $aRowData = $oStmtData->fetchAll();
            if (count($aRowData) == 0) {
                $sLastLogin  = gmdate("Y-m-d H:i:s");
                $sUserToken  = uniqid();
                $iUserStatus = 1;
                $sQry  =  "INSERT INTO {$this->APP->TABLEPREFIX}od_user SET firstname = :firstname, lastname = :lastname, username = :username, email = :email, password = :password, roleid = :roleid, idverifier = :idverifier, userstatus = :userstatus, lastlogin = :lastlogin";
                $oStmt = $this->DB->prepare($sQry);
                $oStmt->bindParam(':firstname', $_POST['txtFirstName']);
                $oStmt->bindParam(':lastname', $_POST['txtLastName']);
                $oStmt->bindParam(':username', $_POST['txtUsername']);
                $oStmt->bindParam(':email', $_POST['txtEmail']);
                $oStmt->bindParam(':password', md5($_POST['txtPassword']));
                $oStmt->bindParam(':roleid', $_POST['selRole']);
                $oStmt->bindParam(':idverifier', $sUserToken);
                $oStmt->bindParam(':userstatus', $iUserStatus);
                $oStmt->bindParam(':lastlogin', $sLastLogin);
                $oStmt->execute();
                $userid = $this->DB->lastInsertID();

                $sPath = $_SERVER['PHP_SELF'].'?ID=17&PF=1&PC='.$_POST['txtUsername'];
                header("Location: $sPath");
                exit();
            }
            else {
                $aMsg[0] = "The username/ email cannot be created in duplicate. Please choose a new username/ email.";
                $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                $aMsg[2] = "ui-icon ui-icon-alert";
            }
        }
        else {
            $aMsg[0] = "The username cannot be chosen as <strong>admin</strong>. Please choose a new username.";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
        }
        return $aMsg;
    }


    public function haeKayttajat($fieldset, $orderby = array('lastname' => 'ASC')) {
        switch ($fieldset) {
            case 'nimitiedot':
                $fields = 'userid, username, firstname, lastname';
            break;
            default:
                $fields = 'userid, username, email, firstname, lastname, roleid, puhelin, katuosoite, postinumero, kaupunki, oletuskustp, lupakirja, kuva, syllabus, kouluttaja';
            break;
        }
        $sql = "SELECT $fields FROM {$this->APP->TABLEPREFIX}od_user ORDER BY ";
        foreach ($orderby as $sarake => $suunta) {
            $sql .= ":$sarake :$suunta, ";
        }
        $sql = substr($sql, 0, -2);
        $stmt = $this->DB->prepare($sql);
        foreach ($orderby as $sarake => $suunta) {
            $stmt->bindValue(":$sarake", $sarake);
            $stmt->bindValue(":$suunta", $suunta);
        }
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
    
    /* Edit User */
    function muokkaaKayttajaa($iRecID)
    {
        $sSqlFind = "SELECT username, email FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid";
        $oStmtFind= $this->DB->prepare($sSqlFind);
        $oStmtFind->bindParam(':userid', $iRecID);
        $oStmtFind->execute();

        $aExistingUserData = $oStmtFind->fetchAll();
        $sExistingUsername = $aExistingUserData[0][username];
        $sExistingUserEmail= $aExistingUserData[0][email];

        if ($_POST['txtUsername'] != 'admin' || $sExistingUsername == 'admin') {
            $sSqlData = "SELECT username, email FROM {$this->APP->TABLEPREFIX}od_user WHERE userid <> :userid AND (username = :username OR email = :email)";
            $oStmtData= $this->DB->prepare($sSqlData);
            $oStmtData->bindParam(':username', $_POST['txtUsername']);
            $oStmtData->bindParam(':email', $_POST['txtEmail']);
            $oStmtData->bindParam(':userid', $iRecID);
            $oStmtData->execute();
            $aRowData = $oStmtData->fetchAll();
            if (count($aRowData) == 0) {
                if($_POST['selRole'])  $sQryUpdate1 = ", roleid = :roleid";
                else $sQryUpdate1 = "";
                if($_POST['txtPassword'])  $sQryUpdate2 = ", password = :password";
                else $sQryUpdate2 = "";

                $sLastLogin  = gmdate("Y-m-d H:i:s");
                $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_user SET firstname = :firstname, lastname = :lastname, username = :username, email = :email,  lastlogin = :lastlogin{$sQryUpdate1}{$sQryUpdate2} WHERE userid = :userid";
                $oStmt = $this->DB->prepare($sQry);
                //echo $sQry;
                $oStmt->bindParam(':firstname', $_POST['txtFirstName']);
                $oStmt->bindParam(':lastname', $_POST['txtLastName']);
                $oStmt->bindParam(':username', $_POST['txtUsername']);
                $oStmt->bindParam(':email', $_POST['txtEmail']);
                if($_POST['selRole']) $oStmt->bindParam(':roleid', $_POST['selRole']);
                if($_POST['txtPassword']) $oStmt->bindParam(':password', md5($_POST['txtPassword']));
                $oStmt->bindParam(':lastlogin', $sLastLogin);
                $oStmt->bindParam(':userid', $iRecID);
                $oStmt->execute();

                $sPath = $_SERVER['PHP_SELF'].'?ID=17&PF=2&PC='.$_POST['txtUsername'];
                header("Location: $sPath");
                exit();
            }
            else {
                $aMsg[0] = "The username/ email cannot be created in duplicate. Please choose a new username/ email.";
                $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                $aMsg[2] = "ui-icon ui-icon-alert";
            }
        }
        else {
            $aMsg[0] = "The username cannot be chosen as <strong>admin</strong>. Please choose a new username.";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
        }
        return $aMsg;
    }

    /* Get details of User */
    function listEachUser($iRecID)
    {
        $sSqlData = "SELECT u.userid, u.firstname, u.lastname, u.username, u.email, u.lastlogin, u.userstatus, u.roleid, u.password " .
                    "FROM {$this->APP->TABLEPREFIX}od_user u WHERE u.userid = :userid";
        $oStmt    = $this->DB->prepare($sSqlData);
        $oStmt->bindParam(':userid', $iRecID);
        $oStmt->execute();
        $aRowData = $oStmt->fetchAll();
        return $aRowData;
    }

    /* Get all Role details */
    function getRoles()
    {
        $sSqlData = "SELECT roleid, rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid <> '' ORDER BY roleid";
        $oStmt    = $this->DB->prepare($sSqlData);
        $oStmt->execute();
        $aRowData = $oStmt->fetchAll();
        return $aRowData;
    }

    /* Delete User */
    function kayttajaPoista($iRecID)
    {
        if($aEachUsersDetails[0]['username'] != 'admin') {
            $sSqlData = "SELECT username FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid";
            $oStmtData= $this->DB->prepare($sSqlData);
            $oStmtData->bindParam(':userid', $iRecID);
            $oStmtData->execute();
            $aRowData = $oStmtData->fetchAll();
            if (count($aRowData) == 1) {
                $sUsername = $aRowData[0][username];
                $sQry = "DELETE from {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid";
                $oStmt = $this->DB->prepare($sQry);
                $oStmt->bindParam(':userid', $iRecID);
                $oStmt->execute();

                $query = "DELETE FROM {$this->APP->TABLEPREFIX}kayttajatiedot WHERE user_id = :userid";
                $oStmt = $this->DB->prepare($query);
                $oStmt->bindParam(':userid', $iRecID);
                $oStmt->execute();

                $sPath = $_SERVER['PHP_SELF'].'?ID=17&PF=3&PC='.$sUsername;
                header("Location: $sPath");
                exit();
            }
            else {
                $aMsg[0] = "No user has been selected for deletion.";
                $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
                $aMsg[2] = "ui-icon ui-icon-alert";
            }
        }
        else {
            $aMsg[0] = "The user <strong>admin</strong> cannot be deleted.";
            $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
            $aMsg[2] = "ui-icon ui-icon-alert";
        }
        return $aMsg;
    }

    /* Change status of user */
    function changeStatus($iStatus, $iUserId)
                {
        if($iStatus == 1) $iChngStatus = 0;
        elseif($iStatus == 0) $iChngStatus = 1;
        $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_user SET userstatus  = :userstatus  WHERE userid = :userid";
        $oStmt = $this->DB->prepare($sQry);
        $oStmt->bindParam(':userstatus', $iChngStatus);
        $oStmt->bindParam(':userid', $iUserId);
        if($oStmt->execute()) print 1;
        else print 2;
        return true;
    }

    function getEvents()
    {
        $sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}od_event ORDER BY pagevars";
        $oStmt    = $this->DB->prepare($sSqlData);
        $oStmt->execute();
        $aRowData = $oStmt->fetchAll();

        $roolit = array();

        foreach ($aRowData as $ryhma) {
            $role = explode(",", $ryhma['roles']);
            $event = explode("^", $ryhma['pagevars']);

            $yksi = '';
            $kaksi = '';
            $kolme = '';
            $nelja = '';

            if ($role['0'] == '1') {
                $yksi = '1';
            }
            elseif ($role['0'] == '2') {
                $yksi = '2';
            }
            elseif ($role['0'] == '3') {
                $yksi = '3';
            }
            elseif ($role['0'] == '7') {
                $yksi = '7';
            }

            if ($role['1'] == '1') {
                $kaksi = '1';
            }
            elseif ($role['1'] == '2') {
                $kaksi = '2';
            }
            elseif ($role['1'] == '3') {
                $kaksi = '3';
            }
            elseif ($role['1'] == '7') {
                $kaksi = '7';
            }

            if ($role['2'] == '1') {
                $kolme = '1';
            }
            elseif ($role['2'] == '2') {
                $kolme = '2';
            }
            elseif ($role['2'] == '3') {
                $kolme = '3';
            }
            elseif ($role['2'] == '7') {
                $kolme = '7';
            }

            if ($role['3'] == '1') {
                $nelja = '1';
            }
            elseif ($role['3'] == '2') {
                $nelja = '2';
            }
            elseif ($role['3'] == '3') {
                $nelja = '3';
            }
            elseif ($role['3'] == '7') {
                $nelja = '7';
            }

            $rooli=array($ryhma['eventid'] => array('eventid' => $ryhma['eventid'], 'event' => $event['0'], '1' => $yksi, '2' => $kaksi, '3' => $kolme, '4' => $nelja));
            $roolit[] = $rooli;
        }
        return $roolit;
    }

    function saveRoles()
    {
        //Onko check-niminen array postattu:
        if ($_POST['check']) {
            //Haetaan its_od_event-taulusta kaikki eventit:
            $sSqlData = "SELECT eventid FROM {$this->APP->TABLEPREFIX}od_event ORDER BY eventid";
            $oStmt    = $this->DB->prepare($sSqlData);
            $oStmt->execute();
            $aRowData = $oStmt->fetchAll();

            //Luupataan kaikki:
            foreach ($aRowData as $events) {
                //Jos on check-arrayn sisällä on array, joka toisessa sarakkeessa on eventin id:
                if ($_POST['check'][$events['eventid']]) {
                    $ryhmat = $_POST['check'][$events['eventid']]; //Otetaan array talteen $ryhmat-muuttujaan.
                    $roles = implode(',', $ryhmat);	//Otetaan arraysta arvot ja yhdistetään implodella (erottimena pilkku).

                    //Päivitetään taulu:
                    $sSqlData = "UPDATE {$this->APP->TABLEPREFIX}od_event SET roles = '{$roles}' WHERE eventid = '{$events['eventid']}'";
                    $oStmt    = $this->DB->prepare($sSqlData);
                    $oStmt->execute();
                    $aRowData = $oStmt->fetchAll();

                }
            }
        }
        $sPath = $_SERVER['PHP_SELF'].'?ID=73&PF=2';
        header("Location: $sPath");
    }

    function dbTimeToFiTime($dbtime)
    {
        $fitime = '';

        list($date, $time) = explode(" ", $dbtime);
        list($year, $month, $day) = explode("-", $date);
        list($hour, $minute, $second) = explode(":", $time);

        $fitime = (int)$day . '.' . (int)$month . "." . $year . " klo " . $hour . ":" . $minute . "." . $second;
        return $fitime;
    }

    function haeKoneet($id='', $konetyypit = false)
    {
        $sql = "SELECT k.nimi AS nimi, k.id AS id, k.konetyyppi AS konetyyppi";
        if ($konetyypit) $sql.= ", kt.nimi AS konetyyppi_nimi";
        $sql.= " FROM {$this->APP->TABLEPREFIX}koneet k";
        if ($konetyypit) {
            $sql .= " LEFT JOIN {$this->APP->TABLEPREFIX}konetyypit kt ON k.id = kt.id ";
        }
        if ($id > 0) $sql.= " WHERE k.id = :koneid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':koneid', $id);
        $stmt->execute();

        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $koneet;
    }

    function lisaaKone($id='')
    {
        if ($id == '')
        {
            $sql = "INSERT INTO {$this->APP->TABLEPREFIX}koneet " .
                   "SET nimi = :nimi, konetyyppi = :tyyppi";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(':nimi', $_POST["txtPlaneName"]);
            $stmt->bindParam(':tyyppi', $_POST["selPlaneType"]);
            $stmt->execute();

            $sPath = $_SERVER['PHP_SELF'].'?ID=27&PF=1&PC='.$_POST["txtPlaneName"];
            header("Location: $sPath");
        }
        else
        {
            $sql = "UPDATE {$this->APP->TABLEPREFIX}koneet " .
                    "SET nimi = :nimi, konetyyppi = :tyyppi " .
                    "WHERE id = :koneid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(':nimi', $_POST["txtPlaneName"]);
            $stmt->bindParam(':tyyppi', $_POST["selPlaneType"]);
            $stmt->bindParam(':koneid', $_POST["kone_id"]);
            $stmt->execute();
            
            $sPath = $_SERVER['PHP_SELF'].'?ID=27&PF=2&PC='.$_POST["txtPlaneName"];
            header("Location: $sPath");
        }

    }

    function poistaKone()
    {
        $stmt = $this->DB->prepare("SELECT nimi FROM {$this->APP->TABLEPREFIX}koneet WHERE id = :koneid");
        $stmt->bindParam(':koneid', $_GET["kone_id"]);
        $stmt->execute();
        $kone = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->DB->prepare("DELETE FROM {$this->APP->TABLEPREFIX}koneet WHERE id = :koneid");
        $stmt->bindParam(':koneid', $_GET["kone_id"]);
        $stmt->execute();
        $sPath = $_SERVER['PHP_SELF'].'?ID=27&PF=3&PC='.$kone["nimi"];
        header("Location: $sPath");
    }

    function haeKonetyypit($id='')
    {
        // Haetaan konetyyppilistaus tai yksittäinen kone ($id)
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}konetyypit";
        if ($id > 0) $sql.= " WHERE id = :ktid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':ktid', $id);
        $stmt->execute();

        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    /**
     * Hakee tietyn konetyypin koneet TAI kaikkien konetyyppien koneet
     * 
     * Jos haetaan tietyn konetyypin koneet, palautetaan koneen id ja nimi (koneet-taulusta)
     * 
     * Jos haetaan kaikkien konetyyppien koneet, palautetaan koneen id, nimi ja konetyypin id (koneet-taulusta) ja konetyypin nimi (AS tyyppinimi; konetyypit-taulusta)
     * 
     * @param int $id (optional) Konetyypin id
     * @return array Koneet assosiatiivisena taulukkona.
     */
    public function haeKonetyypinKoneet($id='')
    {
        if (!$id) 
        {
            $sql = "SELECT k.id AS id, k.nimi AS nimi, k.konetyyppi AS konetyyppi, t.nimi AS tyyppinimi FROM its_koneet k INNER JOIN its_konetyypit t ON k.konetyyppi = t.id ORDER BY tyyppiNimi ASC, nimi ASC";
            $stmt = $this->DB->prepare($sql);
        }
        else 
        {
            $sql = "SELECT id, nimi FROM {$this->APP->TABLEPREFIX}koneet WHERE konetyyppi = :konetyyppi ORDER BY nimi ASC";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindValue(':konetyyppi', $id);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function haeMaatyokohteet($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}maatyokohteet";
        if ($id > 0) $sql.= " WHERE id = :mtid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':mtid', $id);
        $stmt->execute();
        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function haeHuoltonimikkeet($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}huoltonimikkeet";
        if ($id > 0) $sql.= " WHERE id = :hid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':hid', $id);
        $stmt->execute();
        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    
    function tallennaKonetyypit()
    {
        foreach ($_POST["konetyyppi"] as $id => $val)
        {
            if (!empty($val["nimi"]))
            {
                $sql = ($id == 0) ? "INSERT INTO " : "UPDATE ";
                $sql.= "{$this->APP->TABLEPREFIX}konetyypit SET nimi = :nimi";
                if ($id > 0) $sql.= " WHERE id = :ktid";

                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(':nimi', $val["nimi"]);
                if ($id > 0) $stmt->bindParam(':ktid', $id);
                $stmt->execute();
            }
        }

        $sPath = $_SERVER['PHP_SELF'].'?ID=28&PF=1';
        header("Location: $sPath");
    }

    function poistaKonetyyppi()
    {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}konetyypit WHERE id = :ktid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':ktid', $_GET["konetyyppi_id"]);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'].'?ID=28&PF=2';
        header("Location: $sPath");
    }

    function haeKustannuspaikat($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kustannuspaikat";
        if ($id > 0) $sql.= " WHERE id = :kpid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':kpid', $id);
        $stmt->execute();

        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function tallennaKustannuspaikat()
    {

        foreach ($_POST["kustannuspaikka"] as $id => $val) {
            if (!empty($val["nimi"])) {

                $sql = ($id == 0) ? "INSERT INTO " : "UPDATE ";
                $sql.= "{$this->APP->TABLEPREFIX}kustannuspaikat SET nimi = :nimi";
                if ($id > 0) $sql.= " WHERE id = :kpid";
                
                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(':nimi', $val["nimi"]);
                if ($id > 0) $stmt->bindParam(':kpid', $id);
                $stmt->execute();
            }
        }
        
        $sPath = $_SERVER['PHP_SELF'].'?ID=29&PF=1';
        header("Location: $sPath");
    }

    function poistaKustannuspaikka()
    {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}kustannuspaikat WHERE id = :kpid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':kpid', $_GET["kustannuspaikka_id"]);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'].'?ID=29&PF=2';
        header("Location: $sPath");
    }

    function haeTilat($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tilat";
        if ($id > 0) $sql.= " WHERE id = :tid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':tid', $id);
        $stmt->execute();

        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function tallennaTilat()
    {

        foreach ($_POST["tila"] as $id => $val) {
            if (!empty($val["nimi"])) {

                $sql = ($id == 0) ? "INSERT INTO " : "UPDATE ";
                $sql.= "{$this->APP->TABLEPREFIX}tilat SET nimi = :nimi";
                if ($id > 0) $sql.= " WHERE id = :tid";
                
                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(':nimi', $val["nimi"]);
                if ($id > 0) $stmt->bindParam(':tid', $id);
                $stmt->execute();
            }
        }
        
        $sPath = $_SERVER['PHP_SELF'].'?ID=30&PF=1';
        header("Location: $sPath");
    }

    function poistaKelpuutus()
    {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}kelpuutukset WHERE id = :tid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':tid', $_GET["kelpuutus_id"]);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'].'?ID=50&PF=2';
        header("Location: $sPath");
    }

    function haeKelpuutukset($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kelpuutukset";
        if ($id > 0) $sql.= " WHERE id = :tid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':tid', $id);
        $stmt->execute();

        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function tallennaKelpuutukset()
    {

        foreach ($_POST["kelpuutus"] as $id => $val) {
            if (!empty($val["nimi"])) {

                $sql = ($id == 0) ? "INSERT INTO " : "UPDATE ";
                $sql.= "{$this->APP->TABLEPREFIX}kelpuutukset SET nimi = :nimi";
                if ($id > 0) $sql.= " WHERE id = :tid";

                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(':nimi', $val["nimi"]);
                if ($id > 0) $stmt->bindParam(':tid', $id);
                $stmt->execute();
            }
        }

        $sPath = $_SERVER['PHP_SELF'].'?ID=50&PF=1';
        header("Location: $sPath");
    }

    function poistaTila()
    {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}tilat WHERE id = :tid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':tid', $_GET["tila_id"]);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'].'?ID=30&PF=2';
        header("Location: $sPath");
    }


    function haeTiedotteet()
    {
        if (empty($_GET["tiedote_id"])) {
            $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet";
            $stmt = $this->DB->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tiedotteet ";
            $sql.= "WHERE id = :tid";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(":tid", $_GET["tiedote_id"]);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    function lisaaTiedote()
    {
        $sql = "INSERT INTO {$this->APP->TABLEPREFIX}tiedotteet SET ";
        $sql.= "otsikko = :ots, teksti = :txt, tyyppi = :type, pvm = :pvm";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":ots", $_POST["txtInfoTitle"]);
        $stmt->bindParam(":txt", $_POST["txtInfoText"]);
        $stmt->bindParam(":type", $_POST["selInfoType"]);
        $stmt->bindValue(":pvm", date("Y-m-d"));
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'].'?ID=38&PF=1';
        header("Location: $sPath");
    }
    
    function tallennaTiedote()
    {
        $sql = "UPDATE {$this->APP->TABLEPREFIX}tiedotteet SET ";
        $sql.= "otsikko = :ots, teksti = :txt, tyyppi = :type ";
        $sql.= "WHERE id = :tid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":ots", $_POST["txtInfoTitle"]);
        $stmt->bindParam(":txt", $_POST["txtInfoText"]);
        $stmt->bindParam(":type", $_POST["selInfoType"]);
        $stmt->bindParam(":tid", $_POST["tiedote_id"]);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'].'?ID=38&PF=3';
        header("Location: $sPath");
    }

    function poistaTiedote()
    {
        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}tiedotteet WHERE id = :tid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":tid", $_GET["tiedote_id"]);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'].'?ID=38&PF=2';
        header("Location: $sPath");
    }

    public function haeSyllabukset($id='')
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabukset";
        if ($id > 0) $sql.= " WHERE id = :syid";
        $stmt = $this->DB->prepare($sql);
        if ($id > 0) $stmt->bindParam(':syid', $id);
        $stmt->execute();

        return ($id > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function haeSyllabuksenHarjoitukset($syllabus_id)
    {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabus_harjoitukset ";
        $sql.= "WHERE syllabus_id = :syid ORDER BY jarjestys";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":syid", $syllabus_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tallennaHarjoitukset()
    {
        // Määritetään syllabuksen harjoitusten määrä, jotta uudelle saadaan mahdollisesti järjestys kuntoon.
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabus_harjoitukset WHERE syllabus_id = :syid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":syid", $_GET["syllabus_id"]);
        $stmt->execute();
        $harkat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $jarjestys = sizeof($harkat) + 1;

        foreach ($_POST["harjoitus"] as $id => $val) {
            if (!empty($val["nimi"])) {

                $sql = ($id == 0) ? "INSERT INTO " : "UPDATE ";
                $sql.= "{$this->APP->TABLEPREFIX}syllabus_harjoitukset SET nimi = :nimi, ";
                $sql.= "otsikko = :otsikko, sisalto = :sisalto";
                $sql.= ($id > 0) ? " WHERE id = :tid" : ", syllabus_id = :syid, jarjestys = :jarj";
                
                $stmt = $this->DB->prepare($sql);
                $stmt->bindParam(':nimi', $val["nimi"]);
                $stmt->bindParam(':otsikko', $val["otsikko"]);
                $stmt->bindParam(':sisalto', $val["sisalto"]);
                if ($id > 0) $stmt->bindParam(':shid', $id);
                else {
                    $stmt->bindParam(':syid', $_GET["syllabus_id"]);
                    $stmt->bindParam(":jarj", $jarjestys);
                }
                $stmt->execute();
            }
        }

        $paluu = $this->APP->BASEURL . "/hallinta.php?ID=67&syllabus_id=".$_GET["syllabus_id"]."&PF=1";
        header("Location: " . $paluu);
    }

    public function poistaHarjoitus()
    {
        $sql = "SELECT jarjestys FROM {$this->APP->TABLEPREFIX}syllabus_harjoitukset WHERE id = :shid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":shid", $_GET["harjoitus_id"]);
        $stmt->execute();
        $harkka = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE {$this->APP->TABLEPREFIX}syllabus_harjoitukset SET jarjestys = jarjestys - 1 WHERE jarjestys > :jarj";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":jarj", $harkka["jarjestys"]);
        $stmt->execute();

        $sql = "DELETE FROM {$this->APP->TABLEPREFIX}syllabus_harjoitukset ";
        $sql.= "WHERE id = :shid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":shid", $_GET["harjoitus_id"]);
        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/hallinta.php?ID=67&syllabus_id=".$_GET["syllabus_id"]."&PF=2";
        header("Location: " . $paluu);
    }

    public function muutaHarjoitustenJarjestys($syllabus_id, $harjoitus_id, $mode)
    {
        // Haetaan siirrettävän harjoituksen järjestysnumero
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}syllabus_harjoitukset ";
        $sql.= "WHERE id = :hid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":hid", $harjoitus_id);
        $stmt->execute();
        $harkka = $stmt->fetch(PDO::FETCH_ASSOC);

        // Määritetään sen uusi paikka
        if ($mode == "moveup") $newplace = $harkka["jarjestys"] - 1;
        elseif ($mode == "movedown") $newplace = $harkka["jarjestys"] + 1;
        else {
            die("Jotain meni rikki.");
        }

        // Päivitetään paikan entinen harkka siirrettävän harjoituksen paikalle
        $sql = "UPDATE {$this->APP->TABLEPREFIX}syllabus_harjoitukset SET ";
        $sql.= ($mode == "moveup") ? "jarjestys = jarjestys + 1 " : "jarjestys = jarjestys - 1 ";
        $sql.= "WHERE jarjestys = :place";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":place", $newplace);
        $stmt->execute();

        // Ja itse harkka uudelle paikalle
        $sql = "UPDATE {$this->APP->TABLEPREFIX}syllabus_harjoitukset SET ";
        $sql.= ($mode == "moveup") ? "jarjestys = jarjestys - 1 " : "jarjestys = jarjestys + 1 ";
        $sql.= "WHERE id = :hid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":hid", $harjoitus_id);
        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/hallinta.php?ID=67&syllabus_id=".$_GET["syllabus_id"]."&PF=3";
        header("Location: " . $paluu);
    }

    /*
     * haeKurssit
     * Toiminta: - noudetaan kaikki kurssit tai kurssi id:n perusteella
     * @params $id
     */
    public function haeKurssit($id='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kurssi";
        if ($id != '') $sql.= " WHERE kurssi_id = :kid";
        $stmt = $this->DB->prepare($sql);
        if ($id != '') $stmt->bindParam(":kid", $id);
        $stmt->execute();

        return ($id != '') ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tallennaKurssi() {

        $opelist = implode(",", $_POST["strTeacherIdList"]);
        $oppilaslist = implode(",", $_POST["strStudentIdList"]);

        $sql = ($_POST["kurssiId"] == 0) ? "INSERT INTO " : "UPDATE ";
        $sql.= "{$this->APP->TABLEPREFIX}kurssi SET ";
        $sql.= "kurssi_syllabus_id = :syid, kurssi_nimi = :knimi, ";
        $sql.= "kurssi_kuvaus = :kkuv, kurssi_opettajat = :kopet, kurssi_oppilaat = :kops";
        $sql.= ($_POST["kurssiId"] == 0) ? ", kurssi_luotu = :kluotu" : " WHERE kurssi_id = :kid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":syid", $_POST["intSyllabusId"]);
        $stmt->bindParam(":knimi", $_POST["strCourseName"]);
        $stmt->bindParam(":kkuv", $_POST["strCourseDesc"]);
        $stmt->bindParam(":kopet", $opelist);
        $stmt->bindParam(":kops", $oppilaslist);
        if ($_POST["kurssiId"] == 0) {
            $stmt->bindValue(":kluotu", date("Y-m-d H:i:s"));
            $paluu = $this->APP->BASEURL . "/hallinta.php?ID=71&PF=1";
        }
        else {
            $stmt->bindParam(":kid", $_POST["kurssiId"]);
            $paluu = $this->APP->BASEURL . "/hallinta.php?ID=71&PF=2";
        }

        $stmt->execute();
        header("Location: " . $paluu);
    }

    public function haeHenkilonKurssit($userid) {
        $likes = array();
        $likes[] = $userid;
        $likes[] = $userid.",%";
        $likes[] = "%,".$userid.",%";
        $likes[] = "%,".$userid;

        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}kurssi WHERE ";
        foreach ($likes as $like) $sql.= "kurssi_oppilaat LIKE '{$like}' OR ";
        foreach ($likes as $like) $sql.= "kurssi_opettajat LIKE '{$like}' OR ";
        $sql = substr($sql, 0, -3);
        $sql.= "GROUP BY kurssi_id";

        $stmt = $this->DB->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function haeTuovuorot($id='', $userid='') {
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tyovuoro ";
        if (!empty($id) || !empty($userid)) $sql.= "WHERE ";
        $wheres = array();
        if (!empty($id)) $wheres[] = "id = :tvid";
        if (!empty($userid)) $wheres[] = "user_id = :uid";
        $sql.= implode(" AND ", $wheres) . " ";
        $sql.= "ORDER BY aloitus DESC";

        $stmt = $this->DB->prepare($sql);
        if (!empty($id)) $stmt->bindParam(":tvid", $id);
        if (!empty($userid)) $stmt->bindParam(":uid", $userid);
        $stmt->execute();

        return (!empty($id)) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paivitaVuoro() {
        $ak = new aikakalu();
        $start = $ak->fiDateToDbDate($_POST["strStartDate"]) . ' ' . $_POST["strStartTime"].":00";
        $end = $ak->fiDateToDbDate($_POST["strEndDate"]) . ' ' . $_POST["strEndTime"].":00";
        $vuoroid = $_POST["intShiftId"];

        $sql = "UPDATE {$this->APP->TABLEPREFIX}tyovuoro SET ";
        $sql.= "aloitus = :alku, lopetus = :loppu WHERE id = :tvid";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":alku", $start);
        $stmt->bindParam(":loppu", $end);
        $stmt->bindParam(":tvid", $vuoroid);
        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/hallinta.php?ID=79&PF=1&teacherId=" . $_POST["intTeacherId"];
        if (!empty($_POST["strFromDate"])) $paluu.= "&strFromDate=".$_POST["strFromDate"];
        if (!empty($_POST["strToDate"])) $paluu.= "&strToDate=".$_POST["strToDate"];
        header("Location: " . $paluu);
    }

    public function lisaaVuoro() {
        $ak = new aikakalu();
        $start = $ak->fiDateToDbDate($_POST["strStartDate"]) . ' ' . $_POST["strStartTime"].":00";
        $end = $ak->fiDateToDbDate($_POST["strEndDate"]) . ' ' . $_POST["strEndTime"].":00";

        $sql = "INSERT INTO {$this->APP->TABLEPREFIX}tyovuoro SET ";
        $sql.= "user_id = :uid, aloitus = :alku, lopetus = :loppu";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":uid", $_POST["intTeacherId"]);
        $stmt->bindParam(":alku", $start);
        $stmt->bindParam(":loppu", $end);
        $stmt->execute();

        $paluu = $this->APP->BASEURL . "/hallinta.php?ID=79&PF=2&teacherId=" . $_POST["intTeacherId"];
        if (!empty($_POST["strFromDate"])) $paluu.= "&strFromDate=".$_POST["strFromDate"];
        if (!empty($_POST["strToDate"])) $paluu.= "&strToDate=".$_POST["strToDate"];
        header("Location: " . $paluu);
    }

} //END OF CLASS
?>