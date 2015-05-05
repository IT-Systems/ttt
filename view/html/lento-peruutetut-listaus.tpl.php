<?php
if ($_GET["PF"] == 2) {
    $aMsg[0] = "Peruutettu lento poistettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET["PF"] == 3) {
    $aMsg[0] = "Peruutetun lennon / lentojen tiedot päivitettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#strDate").datepicker();

        $("#frmSubmitButton").click(function(){
            $("#frmPeruutettuLento").validate();
            $("#frmPeruutettuLento").submit();
        });
    });
</script>


<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div">
    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>

    <h2>Peruutetut lennot</h2>
    <div class="tiedot_osio">
        <table class="datataulukko">
            <thead>
                <tr>
                    <th>Päivämäärä</th>
                    <th>Kone</th>
                    <th>Lähtöpaikka</th>
                    <th>Määräpaikka</th>
                    <th>Päällikkö</th>
                    <th>Toiminnan laatu</th>
                    <th>Peruutuksen syy</th>
                    <th>Selvennys</th>
                    <th>Kustannuspaikka</th>
                    <th>Lentojen lkm</th>
                    <th>Toiminnot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (sizeof($lennot) > 0) {
                    foreach ($lennot as $lento) {
                        $kone = $len->haeIlmaAlus($lento["kone_id"]);
                        if (!$kone)
                            $kone["nimi"] = "Poistunut";

                        $paallikko = $len->haeHenkilot($lento["paallikko_user_id"]);
                        if (!$paallikko)
                            $paallikko["nimi"] = "Poistunut";
                        else {
                            $paallikko["nimi"] = $paallikko["firstname"] . " " . $paallikko["lastname"];
                        }

                        $toim_laatu = $len->haeLennonToiminnanLaadut($lento["toiminnan_laatu_id"]);
                        if (!$toim_laatu)
                            $toim_laatu["nimi"] = "Poistunut";

                        $syy = $len->haeSyyt($lento["syy_id"]);
                        if (!$syy)
                            $syy["syy"] = "Poistunut";

                        $kustannuspaikka = $len->haeKustannuspaikat($lento["kustannuspaikka_id"]);
                        if (!$kustannuspaikka)
                            $kustannuspaikka["nimi"] = "Poistunut";
                        ?>
                        <tr>
                            <td><?php print $ak->dbDateToFiDate($lento["paivamaara"]); ?></td>
                            <td><?php print $kone["nimi"]; ?></td>
                            <td><?php print $lento["lahtopaikka"]; ?></td>
                            <td><?php print $lento["maarapaikka"]; ?></td>
                            <td><?php print $paallikko["nimi"]; ?></td>
                            <td><?php print $toim_laatu["nimi"]; ?></td>
                            <td><?php print $syy["syy"]; ?></td>
                            <td><?php print $lento["selvennys"]; ?></td>
                            <td><?php print $kustannuspaikka["nimi"]; ?></td>
                            <td><?php print $lento["lentojen_lkm"]; ?></td>
                            <td>
                                <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=64&plento_id=' . stripslashes($lento['id']); ?>">
                                    <img src="<?php print $APP->BASEURL; ?>/view/images/icon-edit.png" title="Muokkaa" alt="Muokkaa" class="icon"/>
                                </a>
                                <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=65&plento_id=' . stripslashes($lento['id']); ?>" style="margin-left: 10px;">
                                    <img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Poista" alt="Poista" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa tämän peruutetun lennon?');"/>
                                </a>
                            </td>
                        </tr>
        <?php
    }
}
else {
    ?>
                    <tr>
                        <td colspan="11">Ei peruutettuja lentoja</td>
                    </tr>
    <?php }
?>
            </tbody>
        </table>
    </div>

</div>