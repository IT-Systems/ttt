<?php

if ($_GET['PF'] == '1') {
    $aMsg[0] = "Viesti asetettiin tärkeäksi.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '2') {
    $aMsg[0] = "Viesti asetettiin normaaliksi.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '3') {
    $aMsg[0] = "Viesti siirrettiin roskakoriin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '4') {
    $aMsg[0] = "Uusi kansio luotiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '4') {
    $aMsg[0] = "Uusi kansio luotiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '5') {
    $aMsg[0] = "{$_GET["AM"]} viesti(ä) siirrettiin valittuun kansioon.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif (!isset($aMsg)) {
    $aMsg[0] = "";
    $aMsg[1] = "";
    $aMsg[2] = "";
}

?>

<div id="ylanapit" class="valikko alavalikko">
    <ul class="topnav">
        <li><a href="<?php print $_SERVER['PHP_SELF']; ?>?ID=32&FID=<?php print $kansio_id; ?>">Uusi viesti</a></li>
<?php if ($USER->iRole != 3) {
      ?>
        <li><a href="<?php print $_SERVER['PHP_SELF']; ?>?ID=36&FID=<?php print $kansio_id; ?>">Uusi tekstiviesti</a></li>
    <?php }
?>
        <li><a href="<?php print $_SERVER['PHP_SELF']; ?>?ID=33&FID=<?php print $kansio_id; ?>">Uusi kansio</a></li>
    </ul>
</div>

<div class="kp_div">
<?php if (!empty($aMsg[1])) { ?>
<div class="ilmoitus <?php print $aMsg[1]; ?>">
    <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
<?php print $aMsg[0] ?>
</div>
<?php } ?>
    <h2>Viestit</h2>
    <div class="tiedot_iso_osio">
        <div id="note-view">
<?php
include 'notes.tpl.php';
?>
        </div>
    </div>
</div>