<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php print $APP->BASEURL;?>/view/css/index.php" rel="stylesheet" type="text/css" />
    </head>
    <body>
<?php
list($year, $month) = explode("-", $_POST["kk"]);
$subject = $ak->haeKkFiNimi($month) . ' ' . $year;
?>
        <a href="javascript:void(0);" onclick="javascript:history.go(-1);" style="float:left;margin:0px 4px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all noprint"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Palaa</a>
        <a href="javascript:void(0);" onclick="window.print();"style="float:left;margin:0px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all noprint"><span class="ui-icon ui-icon-plus"></span>Tulosta</a>

        <div style="clear:both;"/>

        <h3><?php print $subject; ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Päivä</th>
                    <th>Aloitusaika</th>
                    <th>Lopetusaika</th>
                    <th>UTC+/-</th>
                    <th>Pituus</th>
                    <th>Lepoaika</th>
                    <th>Lentotunnit</th>
                    <th>Kommentti</th>
                </tr>
            </thead>
            <tbody>
<?php
if ($vuorot) {
    $yhtkesto = 0;
    $lentotunnityht = 0;
    foreach ($vuorot as $paiva => $shifts) {
        if (strlen($paiva) == 1) $paiva = (string)"0".$paiva;
        if (empty($_POST["kk"])) {
            $paivaview = $paiva . "." . date("m.");
        }
        else {
            list($year, $month) = explode("-", $_POST["kk"]);
            if (strlen($month) == 1) $month = (string)"0".$month;
            $paivaview = $paiva . "." . $month . ".";
        }
        foreach ($shifts as $shift) {
            list($ssDate, $ssTime) = explode(" ", $shift["aloitus"]);
            list($seDate, $seTime) = explode(" ", $shift["lopetus"]);
            $aloitus = $ak->dbDateToFiDate($ssDate) . " klo " . $ssTime;
            $lopetus = $ak->dbDateToFiDate($seDate) . " klo " . $seTime;
            list($kHours, $kMins) = explode(":", $shift["kesto"]);
            $yhtkesto+= $kMins + $kHours * 60;

            list($lHours, $lMins) = explode(":", $shift["lentotunnit"]);
            $lentotunnityht+= $lMins + $lHours * 60;
            ?>
            <tr>
                <td><?php print $paivaview; ?></td>
                <td><?php print $aloitus; ?></td>
                <td><?php print $lopetus; ?></td>
                <td><?php if (isset($shift["vyohyke"])) print $shift["vyohyke"]; ?></td>
                <td><?php print $shift["kesto"]; ?></td>
                <td></td>
                <td><?php if ($lHours > 0 || $lMins > 0) print $shift["lentotunnit"]; ?></td>
                <td><?php print $shift["kommentti"]; ?></td>
            </tr>
<?php
        }
    }
    $yhthours = floor($yhtkesto / 60);
    $yhtmins = $yhtkesto % 60;
    if (strlen($yhtmins) == 1) $yhtmins = (string)"0".$yhtmins;
    $kestoview = (string)$yhthours . ":" . $yhtmins;

    $yhthours = floor($lentotunnityht / 60);
    $yhtmins = $lentotunnityht % 60;
    if (strlen($yhtmins) == 1) $yhtmins = (string)"0".$yhtmins;
    $lentoview = (string)$yhthours . ":" . $yhtmins;
    ?>
            <tr>
                <td colspan="4">Yhteensä</td>
                <td><?php print $kestoview; ?></td>
                <td></td>
                <td><?php if ($yhtmins > 0 || $yhthours > 0) print $lentoview; ?></td>
                <td></td>
            </tr>
<?php
}
else { ?>
            <tr>
                <td colspan="8">Ei tallennettuja työvuoroja tälle kuukaudelle</td>
            </tr>
<?php
} ?>
        </tbody>
    </table>
    </body>
</html>