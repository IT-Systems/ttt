<?php
/**
 * Erinäisiä funktioita eri kontrollereille.
 */
class helper {

    function __construct() {
        global $DB,$APP,$USER;
        $this->DB   = $DB;
        $this->APP  = $APP;
        $this->USER = $USER;
        return true;
    }

    function vahvistaOsallistuminen()
    {
        $vahv = ($_POST["vahvistus"] == "on") ? 1 : 0;
        $sql = "UPDATE {$this->APP->TABLEPREFIX}jqcalendar_requests ";
        $sql.= "SET vahvistettu = :vah ";
        $sql.= "WHERE kutsu_user_id = :kuid AND jqcalendar_id = :jqid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":vah", $vahv);
        $stmt->bindParam(":kuid", $this->USER->ID);
        $stmt->bindParam(":jqid", $_POST["jqcal_id"]);
        $stmt->execute();

        $sPath = $_SERVER['PHP_SELF'] . '?ID=6&PF=';
        $sPath.= ($vahv == 1) ? '1' : '2';
	header("Location: $sPath");
    }

    /*
     * Inform user if he/she has worked too much returning an array of messages for the view to present.
     * LIMITS:
     * Flying time: 8 hours within last 24 hours, 14 hours within last 48 hours
     * Working time: 10 hours within last 24 hours, 20 hours within last 48 hours
     */
    public function checkWorkLimits() {
        $messages = array();
        $userid = $this->USER->ID;
        $date24hr = date("Y-m-d H:i:s", mktime (date("H"), date("i"), date("s"), date("n"), date("d")-1, date("Y")));
        $date48hr = date("Y-m-d H:i:s", mktime (date("H"), date("i"), date("s"), date("n"), date("d")-2, date("Y")));

        // Shifts
        // Look for shifts that are valid within last 24 hours.
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tyovuoro WHERE ";
        $sql.= "(lopetus > :24h OR lopetus = :eilop) ";
        $sql.= "AND user_id = :uid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":24h", $date24hr);
        $stmt->bindValue(":eilop", "0000-00-00 00:00:00");
        $stmt->bindParam(":uid", $userid);
        $stmt->execute();
        $vuorot = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $time24hr = 0;
        foreach ($vuorot as $vuoro) {
            if ($vuoro["lopetus"] == "0000-00-00 00:00:00") $vuoro["lopetus"] = date("Y-m-d H:i:s");
            $time24hr += $this->calculateTotalTime($vuoro["aloitus"], $vuoro["lopetus"], $date24hr, '');
        }

        $vrt = 10 * 60 * 60; // 10 hours limit
        if ($time24hr > $vrt) {
            $timeshow = round($time24hr / 60 / 60, 2);
            $messages[] = "Olet ollut töissä viimeisen 24 tunnin aikana yli kymmenen tunnin sallitun rajan ($timeshow tuntia)!";
        }

        // Look for shifts that are valid within last 48 hours.
        $sql = "SELECT * FROM {$this->APP->TABLEPREFIX}tyovuoro WHERE ";
        $sql.= "(lopetus > :48h OR lopetus = :eilop) ";
        $sql.= "AND user_id = :uid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":48h", $date48hr);
        $stmt->bindValue(":eilop", "0000-00-00 00:00:00");
        $stmt->bindParam(":uid", $userid);
        $stmt->execute();
        $vuorot = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $time48hr = 0;
        foreach ($vuorot as $vuoro) {
            if ($vuoro["lopetus"] == "0000-00-00 00:00:00") $vuoro["lopetus"] = date("Y-m-d H:i:s");
            $time48hr += $this->calculateTotalTime($vuoro["aloitus"], $vuoro["lopetus"], $date48hr, '');
        }

        $vrt = 20 * 60 * 60; // 20 hours limit
        if ($time48hr > $vrt) {
            $timeshow = round($time48hr / 60 / 60, 2);
            $messages[] = "Olet ollut töissä viimeisen 48 tunnin aikana yli kahdenkymmenen tunnin sallitun rajan ($timeshow tuntia)!";
        }

        // Flights
        // Look for lentoaika in its_lennot -taulu, select based on alkamispaiva and off_block_aika
        list($pvm24, $aika24) = explode(" ", $date24hr);
        $sql = "SELECT alkamispaiva, lentoaika, off_block_aika, on_block_aika ";
        $sql.= "FROM {$this->APP->TABLEPREFIX}lennot WHERE ";
        $sql.= "(alkamispaiva = :day24hr AND on_block_aika > :time24hr) OR ";
        $sql.= "(alkamispaiva > :day24hr) ";
        $sql.= "AND kayttaja_id = :uid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":day24hr", $pvm24);
        $stmt->bindParam(":time24hr", $aika24);
        $stmt->bindParam(":uid", $userid);
        $stmt->execute();
        $vuorot = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate flying hours
        $fly24hr = 0;
        foreach ($vuorot as $vuoro) {
            list($flyhr, $flymin, $flysec) = explode(":", $vuoro["lentoaika"]);
            if (($vuoro["off_block_aika"] > $aika24 && $vuoro["alkamispaiva"] == $pvm24) || $vuoro["alkamispaiva"] > $pvm24) {
                $fly24hr+= $flyhr * 60 * 60 + $flymin * 60 + $flysec;
            }
            else {
                // Lentovuoro on alkanut aikaisemmin kuin tämä hetki - 24h, mutta loppunut myöhemmin.
                $flytmp = $flyhr * 60 * 60 + $flymin * 60 + $flysec;
                $reduction = strtotime($aika24) - strtotime($vuoro["off_block_aika"]);
                $fly24hr+= $flytmp - $reduction;
            }
        }

        $vrt = 8 * 60 * 60; // 8 hours limit
        if ($fly24hr > $vrt) {
            $timeshow = round($fly24hr / 60 / 60, 2);
            $messages[] = "Olet lennellyt viimeisen 24 tunnin aikana yli kahdeksan tunnin sallitun rajan ($timeshow tuntia)!";
        }

        list($pvm48, $aika48) = explode(" ", $date48hr);
        $sql = "SELECT alkamispaiva, lentoaika, off_block_aika, on_block_aika ";
        $sql.= "FROM {$this->APP->TABLEPREFIX}lennot WHERE ";
        $sql.= "(alkamispaiva = :day48hr AND on_block_aika > :time48hr) OR ";
        $sql.= "(alkamispaiva > :day48hr) ";
        $sql.= "AND kayttaja_id = :uid";

        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(":day48hr", $pvm48);
        $stmt->bindParam(":time48hr", $aika48);
        $stmt->bindParam(":uid", $userid);
        $stmt->execute();
        $vuorot = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $fly48hr = 0;
        foreach ($vuorot as $vuoro) {
            list($flyhr, $flymin, $flysec) = explode(":", $vuoro["lentoaika"]);
            // Vuoro on alkanut 48h tunnin sisällä tästä hetkestä taaksepäin.
            if (($vuoro["off_block_aika"] > $aika48 && $vuoro["alkamispaiva"] == $pvm48) || $vuoro["alkamispaiva"] > $pvm48) {
                $fly48hr+= $flyhr * 60 * 60 + $flymin * 60 + $flysec;
            }
            else {
                // Lentovuoro on alkanut aikaisemmin kuin tämä hetki - 48h, mutta loppunut myöhemmin.
                $flytmp = $flyhr * 60 * 60 + $flymin * 60 + $flysec;
                $reduction = strtotime($aika48) - strtotime($vuoro["off_block_aika"]);
                $fly48hr+= $flytmp - $reduction;
            }
        }

        $vrt = 14 * 60 * 60; // 14 hours limit
        if ($fly48hr > $vrt) {
            $timeshow = round($fly48hr / 60 / 60, 2);
            $messages[] = "Olet lennellyt viimeisen 48 tunnin aikana yli neljäntoista tunnin sallitun rajan ($timeshow tuntia)!";
        }

        return $messages;
    }

    /*
     * Calculates time in seconds between $start and $end limiting by $starting and $ending
     * $start, $end, $starting, $ending are in MySQL datetime -format.
     */
    public function calculateTotalTime($start, $end, $starting='', $ending='') {
        // datetime to unixtime
        $start = strtotime($start);
        $end = strtotime($end);
        $starting = strtotime($starting);
        $ending = strtotime($ending);

        $startpoint = 0;
        $endpoint = 0;
        $totaltime = 0;
        // Check where to start counting time
        if ($starting) {
            $startpoint = ($starting > $start) ? $starting : $start;
        }
        else {
            $startpoint = $start;
        }
        // Check where to end counting time
        if ($ending) {
            $endpoint = ($ending < $end) ? $ending : $end;
        }
        else {
            $endpoint = $end;
        }
        // Calculate time between and return that value
        $totaltime = $endpoint - $startpoint;

        return $totaltime;
    }

} //End Of Class Statement
?>
