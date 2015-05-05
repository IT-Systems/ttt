<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Viesti";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '2') {
    $aMsg[0] = "Virhe";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<?php require_once('view/html/hallinta-napit.tpl.php'); ?>

<div class="<?php print $aMsg[1]; ?>">
    <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
    <?php print $aMsg[0] ?>
</div>


<div class="kp_div">
    <div class="tiedot_osio">
        <p class="info">Hallitse eri osioita.</p>
    </div>
</div>