<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Uusi kone <strong>{$_GET['PC']}</strong> lisättiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2') {
    $aMsg[0] = "Koneen <strong>{$_GET['PC']}</strong> tiedot muokattiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '3') {
    $aMsg[0] = "Kone <strong>{$_GET['PC']}</strong> poistettiin onnistuneesti.";
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
        
    <h2>Koneiden listaus</h2>

    <div class="tiedot_osio">
        <a href="<?php print $_SERVER['PHP_SELF']?>?ID=26" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Lisää kone</a>
        <br><br>
    <table class="datataulukko">
        <thead>
            <tr>
                <th>Kone</th>
                <th>Konetyyppi</th>
                <th>Toiminnot</th>
            </tr>
        </thead>
        <tbody>
<?php
if(count($koneet) > 0) {
    foreach($koneet as $kone) { ?>
            <tr>
                <td><?php print $kone["nimi"]; ?></td>
                <td><?php
        $konetyyppi = $hal->haeKonetyypit($kone["konetyyppi"]);
        print $konetyyppi["nimi"];
?></td>
                <td>
                    <a href="<?php print $_SERVER['PHP_SELF'].'?ID=25&kone_id='.stripslashes($kone['id']); ?>">
                        <img src="<?php print $APP->BASEURL;?>/view/images/icon-edit.png" title="Edit User" alt="Edit User" class="icon"/>
                    </a>
                    <a href="<?php print $_SERVER['PHP_SELF'].'?ID=24&kone_id='.stripslashes($kone['id']); ?>" >
                        <img src="<?php print $APP->BASEURL;?>/view/images/icon-cross.png" title="Delete User" alt="Delete User" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa koneen?');"/>
                    </a>
                </td>
            </tr>
<?php
    }
}
else { ?>
            <tr>
                <td colspan="3"  style="text-align:center;">Ei tallennettuja koneita</td>
            </tr>
<?php
} ?>
        </tbody>
    </table>
    </div>
</div>
