<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Tilat päivitettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} else if ($_GET['PF'] == '2') {
    $aMsg[0] = "Tila poistettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>

    <h2>Tilojen listaus</h2>

    <div class="tiedot_osio">
        <form action="./hallinta.php?ID=30&act=1" method="POST" id="frmProperties" name="frmProperties">

            <table class="datataulukko">
                <thead>
                    <tr>
                        <th>Tila</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="tila[0][nimi]" value="" class="textboxNormal" style="width:350px;"/>
                        </td>
                        <td></td>
                    </tr>
                    <?php if (count($tilat) > 0) { ?>
                        <?php foreach ($tilat as $tila) { ?>
                            <tr>
                                <td>
                                    <input type="text" name="tila[<?php print $tila["id"]; ?>][nimi]" id="tila<?php print $tila["id"]; ?>" value="<?php print $tila["nimi"]; ?>" class="textboxNormal required" style="width:350px;"/>
                                </td>
                                <td>
                                    <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=30&act=2&tila_id=' . stripslashes($tila['id']); ?>" >
                                        <img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Poista tila" alt="Poista tila" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa tilan?');"/>
                                    </a>
                                </td>
                            </tr>
        <?php
    }
} else {
    ?>
                        <tr>
                            <td colspan="2"  style="text-align:center;">Ei tallennettuja tiloja</td>
                        </tr>
    <?php }
?>
                </tbody>
            </table>

            <div class="divSpacing">
                <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
                <a href="javascript:void(0);" onclick="javascript:validatePropertiesEdit();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Tallenna</a>
            </div>
        </form>
        <p class="info">Kaikki tilat tallennetaan tältä sivulta. Uuden tilan luodaksesi kirjoita sen nimi ensimmäiseen kenttään, ja paina tallenna.</p>
    </div>
</div>

<script type="text/javascript">
    function validatePropertiesEdit()
    {
        $("#frmProperties").validate();
        $("#frmProperties").submit();
    }
</script>