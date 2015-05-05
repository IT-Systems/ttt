<?php
$ak = new aikakalu();
$len = new lennot();
$user = $len->haeHenkilot($_POST["intUser"]);
$name = $user["firstname"] . " " . $user["lastname"];

//$i = ($_POST["strSize"] == "-1") ? sizeof($vuorot) : $_POST["strSize"];
//$f = $_POST["strFilter"];
$start = $_POST["strFromDate"];
$to = $_POST["strToDate"];
$j = 0;
$tulostettavat = array();
/*
foreach ($vuorot as $vuoro) {
    list($alkupvm, $alkuaika) = explode(" ", $vuoro["aloitus"]);
    list($loppupvm, $loppuaika) = explode(" ", $vuoro["lopetus"]);
    $aprint = $ak->dbDateToFiDate($alkupvm) . ' klo ' . substr($alkuaika, 0, -3);
    $lprint = $ak->dbDateToFiDate($loppupvm) . ' klo ' . substr($loppuaika, 0, -3);

    if (strpos($aprint, $f) || strpos($lprint, $f) || strpos($vuoro["kommentti"], $f) || strpos($vuoro["kesto"], $f) || strpos($vuoro["lentotunnut"], $f) || empty($f)) {
        $tulostettavat[$j] = $vuoro;
        $tulostettavat[$j]["alkuprint"] = $aprint;
        $tulostettavat[$j]["loppuprint"] = $lprint;
        $j++;
    }
    if ($j == $i) break;
}*/
if (sizeof($vuorot) > 0) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
        @media print {
            thead {
                display: table-header-group;
            }
            .noPrint {
                display:none;
            }
        }
        body {
            color: #000;
            background-color: #FFF;
            text-align: left;
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: 14px;
        }
        h3 {
            font-size: 18px;
        }
        table th, table td {
            text-align: left;
            padding-left: 7px;
            height: 26px;
            vertical-align: top;
        }
        table th {
            font-size: 16px;
        }
        .c1, .c2 {
            width: 125px;
        }
        .c3, .c4 {
            width: 50px;
        }
        .c5 {
            width: 250px;
        }
        </style>
    </head>

    <body>
        <h3>Työaikayhteenveto</h3>
        Työntekijä: <?php print $name; ?><br/>
        <?php if ($start || $to) print "Aikaväli " . $start . " - " . $to . "<br/>"; ?>
        <br/>

        <table cellspacing="0">
            <thead>
                <tr>
                    <th class="c1">Vuoro alkoi</th>
                    <th class="c2">Vuoro päättyi</th>
                    <th class="c3">Kesto</th>
                    <th class="c4">Lentoaika</th>
                    <th class="c5">Huomautukset</th>
                </tr>
            </thead>

            <tbody>
<?php
    $aika48max = 20*60*60;
    $aika24max = 10*60*60;
    $lento48max = 14*60*60;
    $lento24max = 8*60*60;
    $aika24 = 24*60*60;
    $aika48 = 48*60*60;
    $kestototal = 0;
    $lentototal = 0;

    foreach ($vuorot as $key => $vuoro) {
            list($alkupvm, $alkuaika) = explode(" ", $vuoro["aloitus"]);
            list($loppupvm, $loppuaika) = explode(" ", $vuoro["lopetus"]);

             // Varoitusten päättely, työvuorot saadaan laskettua suoraan tästä arraystä, mutta lentojen suhteen ei ole aivan niin yksinkertaista ....
            $vrk = array(); // Kerätään tähän vuorot joiden loppumisaika on vähemmän kuin 24h tämän vuoron alkamisaika.
            $vrk2 = array(); // sama ja vertauksena 48h
            $j = $key + 1;
            $til = sizeof($vuorot) - $j;
            for ($i = $j; $i < $til; $i++) {
                if ($vuoro["loppuint"] - $vuorot[$i]["loppuint"] < $aika24) $vrk[] = $vuorot[$i];
              //  if ($vuoro["loppuint"] - $vuorot[$i]["loppuint"] < $aika48) $vrk2[] = $vuorot[$i];
            }

            $msg = array(); // Varoitusten kerääminen
            $tot24 = $vuoro["kestoint"];
            $tot48 = $vuoro["kestoint"];



            if (sizeof($vrk) > 0) {
                foreach ($vrk as $lVuoro) {
                    if (($lVuoro["alkuint"]+$aika24) > $vuoro["loppuint"]) {
                        $tot24+= $lVuoro["kestoint"];
                    }
                    else {
                        $lisaaika = $lVuoro["loppuint"] + $aika24 - $vuoro["loppuint"];
                        $tot24+= $lisaaika;
                    }
                }
            }
            if ($tot24 > $aika24max) {
                $yli = $tot24 - $aika24max;
                $mins = $yli / 60;
                $yliH = floor($mins/60);
                $yliM = $mins % 60;
                if (strlen($yliM) == 1) $yliM = "0".$yliM;
                $yliaika = $yliH.":".$yliM;
                $msg[] = "Työajan ylitysvaroitus: {$yliaika} / 24h";
            }

            if (sizeof($vrk2) > 0) {
                foreach ($vrk2 as $lVuoro) {
                    if (($lVuoro["alkuint"]+$aika48) > $vuoro["loppuint"]) {
                        $tot48+= $lVuoro["kestoint"];
                    }
                    else {
                        $lisaaika = $lVuoro["loppuint"] + $aika48 - $vuoro["loppuint"];
                        $tot48+= $lisaaika;
                    }
                }
            }
            if ($tot48 > $aika48max) {
                $yli = $tot48 - $aika48max;
                $mins = $yli / 60;
                $yliH = floor($mins/60);
                $yliM = $mins % 60;
                if (strlen($yliM) == 1) $yliM = "0".$yliM;
                $yliaika = $yliH.":".$yliM;
                $msg[] = "Työajan ylitysvaroitus: {$yliaika} / 48h";
            }

            $kestototal+= $vuoro["kestoint"];
            $lentototal+= $vuoro["lentoint"];
        ?>
                <tr>
                    <td class="c1"><?php print $ak->dbDateToFiDate($alkupvm) . ' klo ' . substr($alkuaika, 0, -3); ?></td>
                    <td class="c2"><?php print $ak->dbDateToFiDate($loppupvm) . ' klo ' . substr($loppuaika, 0, -3); ?></td>
                    <td class="c3"><?php print $vuoro["kesto"]; ?></td>
                    <td class="c4"><?php print ($vuoro["lentotunnit"] != "0:00") ? $vuoro["lentotunnit"] : ""; ?></td>
                    <td style="color:red;"><?php
            foreach ($msg as $key => $message) {
                print $message;
                if (isset($msg[$key+1])) print '<br/>';
            }
            ?></td>
                </tr>
<?php
    } 
    $mins = $lentototal / 60;
    $yliH = floor($mins/60);
    $yliM = $mins % 60;
    if (strlen($yliM) == 1) $yliM = "0".$yliM;
    $lentototal = $yliH.":".$yliM;
    $mins = $kestototal / 60;
    $yliH = floor($mins/60);
    $yliM = $mins % 60;
    if (strlen($yliM) == 1) $yliM = "0".$yliM;
    $kestototal = $yliH.":".$yliM;        
    ?>
                <tr>
                    <th colspan="2" style="border-top:1px dotted #000;">Yhteensä</th>
                    <td style="border-top:1px dotted #000;"><?php print $kestototal; ?></td>
                    <td style="border-top:1px dotted #000;"><?php print $lentototal; ?></td>
                    <td style="border-top:1px dotted #000;"></td>
                </tr>
            </tbody>
        </table>
    </body>
    <br/>
    Allekirjoitus
    <div style="width:300px;height:20px;border-top:1px dotted #000;padding-top:5px;margin-top:35px;text-align:right;font-style:italic;"><?php print $name; ?></div>


    <div class="noPrint">
        <br/>
        <a href="JavaScript:window.print();">Tulosta</a>
    </div>
</html>

<?php
} ?>