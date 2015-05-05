<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Korvausmerkintä poistettu.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<!--



-->

<?php require "tyoaika_napit.tpl.php"; ?>
<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>

    <h2>Palkkaerittely</h2>    

    <div class="tiedot_osio">
        <form class="inline_form" id="frmShiftAdd" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?ID=45'; ?>" >
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
        <a href="<?php print $APP->BASEURL; ?>/tyoaika.php?ID=45&print" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-print"></span>Tulosta</a>
        <a href="<?php print $APP->BASEURL; ?>/tyoaika.php?ID=45&new" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-plus"></span>Uusi korvaus</a>
    </div>

    <div class="tiedot_osio">
        <table class="datataulukko">
            <thead>
                <tr>
                    <th>Päivä</th>
                    <th>Laji</th>
                    <th>Selitys</th>
                    <th>á-Hinta</th>
                    <th>Tunnit</th>
                    <th>Korvaus</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($korvaukset) {
                    $yht_korvaus = 0;
                    foreach ($korvaukset as $rivi) {
                        $korvaus = $rivi["tuntipalkka"] * $rivi["tunnit"];
                        $yht_korvaus+= $korvaus;
                        ?>
                        <tr>
                            <td><?php print $rivi["paivamaara"]; ?></td>
                            <td><?php print $rivi["laji_id"]; ?></td>
                            <td><?php print $rivi["selitys"]; ?></td>
                            <td><?php print $rivi["tuntipalkka"]; ?> &euro;</td>
                            <td><?php print $rivi["tunnit"]; ?></td>
                            <td><?php print number_format($korvaus, 2, ",", ""); ?> &euro;</td>
                            <td>
                                <a href="<?php print $APP->BASEURL . '/tyoaika.php?ID=45&delete&korvaus_id=' . stripslashes($rivi['id']); ?>" ><img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Poista korvaus" alt="Poista korvaus" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa korvauksen?');"/></a>
                            </td>
                        </tr>
                        <?php }
                    ?>
                    <tr>
                        <th colspan="5">Yhteensä</th>
                        <td><?php print number_format($yht_korvaus, 2, ",", ""); ?> &euro;</td>
                        <td></td>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                        <td colspan="7">Ei merkittyjä korvauksia valitulle kuukaudelle.</td>
                    </tr>
                    <?php }
                ?>
            </tbody>
        </table>

    </div>
</div>