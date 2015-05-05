<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<?php require "tyoaika_napit.tpl.php"; ?>
<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">


    <h2>Yhteenveto</h2>

    <div class="tiedot_osio">
        <form class="inline_form" id="frmShiftAdd" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?ID=44'; ?>" >
            <label class="no_form">Kuukausi</label>
            <select name="kk" class="textboxNormal" style="width:130px;height:24px;" onChange="javascript:this.form.submit();">
                <?php
                foreach ($pvmval as $month) {
                    $xcls = ($_POST["kk"] == $month["value"]) ? " selected" : ""
                    ?>
                    <option value="<?php print $month["value"]; ?>"<?php print $xcls; ?>><?php print $month["view"]; ?></option>
                    <?php }
                ?>
            </select>
        </form>
    </div>

    <div class="tiedot_osio">
        <table class="datataulukko">
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
                        if (strlen($paiva) == 1)
                            $paiva = (string) "0" . $paiva;
                        if (empty($_POST["kk"])) {
                            $paivaview = $paiva . "." . date("m.");
                        } else {
                            list($year, $month) = explode("-", $_POST["kk"]);
                            if (strlen($month) == 1)
                                $month = (string) "0" . $month;
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
                    if (strlen($yhtmins) == 1)
                        $yhtmins = (string) "0" . $yhtmins;
                    $kestoview = (string) $yhthours . ":" . $yhtmins;

                    $yhthours = floor($lentotunnityht / 60);
                    $yhtmins = $lentotunnityht % 60;
                    if (strlen($yhtmins) == 1)
                        $yhtmins = (string) "0" . $yhtmins;
                    $lentoview = (string) $yhthours . ":" . $yhtmins;
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
else {
    ?>
                    <tr>
                        <td colspan="8">Ei tallennettuja työvuoroja tälle kuukaudelle</td>
                    </tr>
    <?php }
?>
            </tbody>
        </table>
    </div>
</div>