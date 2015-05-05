<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Kustannuspaikat päivitettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2') {
    $aMsg[0] = "Kustannuspaikka poistettiin onnistuneesti.";
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
    
    <h2>Kustannuspaikkojen listaus</h2>

    <div class="tiedot_osio">
    <form action="./hallinta.php?ID=29&act=1" method="POST" id="frmCostCentres" name="frmCostCentres">

    <table class="datataulukko">
        <thead>
            <tr>
                <th>Kustannuspaikka</th>
                <th>Toiminnot</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input type="text" name="kustannuspaikka[0][nimi]" value="" class="textboxNormal" style="width:350px;"/>
                </td>
                <td></td>
            </tr>
<?php
if(count($kustannuspaikat) > 0) { ?>
<?php
            foreach($kustannuspaikat as $paikka) { ?>
            <tr>
                <td>
                    <input type="text" name="kustannuspaikka[<?php print $paikka["id"]; ?>][nimi]" id="kustannuspaikka<?php print $paikka["id"]; ?>" value="<?php print $paikka["nimi"]; ?>" class="textboxNormal required" style="width:350px;"/>
                </td>
                <td>
                    <a href="<?php print $_SERVER['PHP_SELF'].'?ID=29&act=2&kustannuspaikka_id='.stripslashes($paikka['id']); ?>" >
                        <img src="<?php print $APP->BASEURL;?>/view/images/icon-cross.png" title="Delete User" alt="Delete User" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa kustannuspaikan?');"/>
                    </a>
                </td>
            </tr>
<?php
    }
}
else { ?>
            <tr>
                <td colspan="2"  style="text-align:center;">Ei tallennettuja kustannuspaikkoja</td>
            </tr>
<?php
} ?>
        </tbody>
    </table>

    <div class="divSpacing">
        <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
        <a href="javascript:void(0);" onclick="javascript:validateCostCentresEdit();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
    </div>
    </form>
    <p class="info">Kaikki kustannuspaikat tallennetaan tältä sivulta. Uuden kustannuspaikan luodaksesi kirjoita sen nimi ensimmäiseen kenttään, ja paina tallenna.</p>
    </div>
</div>

<script type="text/javascript">
function validateCostCentresEdit()
{
    $("#frmCostCentres").validate();
    $("#frmCostCentres").submit();
}
</script>
<script type="text/javascript">
$(function() {
   $('input').keyup(function(event) {
      if (event.keyCode == 13) {
          lomake = $(this).closest('form');
          lomake.validate();
          lomake.submit();
      } 
   });
});
</script>