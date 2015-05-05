<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Uusi kurssi lisätty.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2') {
    $aMsg[0] = "Kurssin tietoja muokattiin.";
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

    <h2>Kurssit</h2>

    <div class="tiedot_osio">
        <a href="<?php print $_SERVER['PHP_SELF']?>?ID=71&mode=add" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Lisää kurssi</a>
        <br><br>
        <table class="datataulukko">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nimi</th>
                    <th>Kuvaus</th>
                    <th>Syllabus</th>
                    <th colspan="2">Toiminnot</th>
                </tr>
            </thead>

            <tbody>
<?php
if ($kurssit) {
    foreach ($kurssit as $kurssi) {
        $syllabus = $hal->haeSyllabukset($kurssi["kurssi_syllabus_id"]); ?>
                <tr>
                    <td><?php print $kurssi["kurssi_id"]; ?></td>
                    <td><?php print $kurssi["kurssi_nimi"]; ?></td>
                    <td><?php print $kurssi["kurssi_kuvaus"]; ?></td>
                    <td><?php print $syllabus["nimi"]; ?></td>
                    <td>
                        <a href="<?php print $_SERVER['PHP_SELF'].'?ID=71&mode=modify&kurssiId='.stripslashes($kurssi['kurssi_id']); ?>">
                            <img src="<?php print $APP->BASEURL;?>/view/images/icon-edit.png" title="Muokkaa" alt="Muokkaa" class="icon"/>
                        </a>
                    </td>
                    <td>
                        <a href="<?php print $_SERVER['PHP_SELF'].'?ID=71&mode=delete&kurssiId='.stripslashes($kurssi['kurssi_id']); ?>" >
                            <img src="<?php print $APP->BASEURL;?>/view/images/icon-cross.png" title="Poista" alt="Poista" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa tämän kurssin?');"/>
                       </a>
                    </td>
                </tr>
<?php
    }
}
else { ?>
                <tr>
                    <td colspan="5">Ei tallennettuja kursseja</td>
                </tr>
<?php
} ?>
            </tbody>
        </table>

    </div>

</div>