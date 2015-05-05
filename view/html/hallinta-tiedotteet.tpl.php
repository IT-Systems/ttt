<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Tiedote lisätty.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2') {
    $aMsg[0] = "Tiedote poistettu.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '3') {
    $aMsg[0] = "Tiedote muokattu.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>
    
    <h2>Toiminnalliset ohjeet ja tiedotteet</h2>
    
    <div class="tiedot_osio">
        <a href="<?php print $_SERVER['PHP_SELF']?>?ID=39" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Lisää tiedote/ohje</a><br><br>

    <table class="datataulukko">
        <thead>
            <tr>
                <th>Otsikko</th>
                <th>Tyyppi</th>
                <th>Päiväys</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
<?php
if (sizeof($tiedotteet) > 0) {
    foreach ($tiedotteet as $tied) {
        $tyyppi = ($tied["tyyppi"] == 0) ? "Tiedote" : "Toim. ohje";
        list($year, $mo, $day) = explode("-", $tied["pvm"]);
        $paivays = $day.".".$mo.".".$year;
        ?>
            <tr>
                <td><?php print $tied["otsikko"]; ?></td>
                <td><?php print $tyyppi; ?></td>
                <td><?php print $paivays; ?></td>
                <td>
                    <a href="<?php print $_SERVER['PHP_SELF'].'?ID=40&tiedote_id='.stripslashes($tied['id']); ?>">
                        <img src="<?php print $APP->BASEURL;?>/view/images/icon-edit.png" title="Muokkaa" alt="Muokkaa" class="icon"/>
                    </a>
                    <a href="<?php print $_SERVER['PHP_SELF'].'?ID=41&tiedote_id='.stripslashes($tied['id']); ?>" >
                        <img src="<?php print $APP->BASEURL;?>/view/images/icon-cross.png" title="Poista" alt="Poista" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa tämän tiedotteen?');"/>
                    </a>
                </td>
            </tr>
<?php
    }
}
else { ?>
            <tr>
                <td colspan="3">Ei tallennettuja ohjeita / tiedotteita.</td>
            </tr>
<?php
} ?>
        </tbody>
    </table>

    </div>
</div>

<script type="text/javascript">
function validatePropertiesEdit()
{
    $("#frmProperties").validate();
    $("#frmProperties").submit();
}
</script>