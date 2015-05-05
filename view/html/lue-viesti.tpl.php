<div class="kp_div">
<?php
$backurl = $APP->BASEURL . "/viestit.php?ID=31";
if (!empty($_GET["FID"])) $backurl.= "&FID=" . $_GET["FID"];
?>
    <h2>Viestin tiedot</h2>

    <div class="tiedot_osio">
    <table id="tbl_viestiread" class="datataulukko">
        <tr>
            <th>Lähettäjä</th>
            <td><?php print $lahettaja["firstname"] . ' ' . $lahettaja["lastname"] . '<br>' . $lahettaja["email"]; ?></td>
        </tr>
        <tr>
            <th>Aika</th>
            <td><?php print $h->dbTimeToFiTime($viesti["lahetetty_aika"]); ?></td>
        </tr>
        <tr>
            <th>Aihe</th>
            <td><?php print $viesti["aihe"]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><?php print $viesti["teksti"]; ?></td>
        </tr>
    </table>
        
    </div>
    <div class="tiedot_osio">
        <a class="ui-od-button-with-icon ui-state-default ui-corner-all" style="font-weight:bold;" href="<?php print $backurl; ?>">
            <span class="ui-icon ui-icon-arrowreturnthick-1-w"></span>Takaisin viesteihin
        </a>
    </div>

</div>