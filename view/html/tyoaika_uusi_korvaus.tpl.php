<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Korvaus tallennettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
if ($_GET['PF'] == '2') {
    $aMsg[0] = "Et voi merkitä korvausta tulevaisuuteen!";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#txtDate").datepicker();

        $("#submitSalary").click(function(){
            $("#frmSalaryAdd").validate();
            $("#frmSalaryAdd").submit();
        });

    });
</script>
<!--

<a href="<?php print $APP->BASEURL; ?>/tyoaika.php?ID=45" style="font-weight:bold;margin-left:30px;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Peruuta</a>

-->
    <?php require "tyoaika_napit.tpl.php"; ?>



<div class="kp_div">

    <div class="<?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>

    <h2>Uuden korvauksen syöttö</h2>
    
    <div class="tiedot_osio">
        <form id="frmSalaryAdd" method="post" action="<?php print $_SERVER['PHP_SELF'].'?ID=45&new';?>" >
            <table class="lomake_taulukko">
                <tr>
            <td><label>Laji</label></td>
                <td><select class="textboxNormal required" style="width:350px;height:24px;" name="intType" id="intType">
                    <option value="">-- Valitse laji</option>
<?php
foreach ($lajit as $laji) {
    $xcls = ($_POST["txtType"] == $laji["id"]) ? " selected" : "";
    ?>
                    <option value="<?php print $laji["id"]; ?>"<?php print $xcls; ?>><?php print $laji["nimi"]; ?></option>
<?php
} ?>
                </select></td>
                </tr>
                <tr>
                <td><label>Päivä</label></td>
                <td><input type="text" class="textboxNormal required" style="width:350px;" name="txtDate" id="txtDate" value="<?php print $_POST["txtDate"]; ?>"/></td>
                </tr>
                
                <tr>
                <td><label>Selitys</label></td>
                <td><textarea class="textboxNormal required" style="width:350px;height:70px;" name="txtDesc" id="txtDesc"><?php print $_POST["txtDesc"]; ?></textarea></td>
                </tr>
                
                <tr>
                <td><label>á-hinta (tuntipalkka)</label></td>
                <td><input type="text" class="textboxNormal required" style="width:350px;" name="txtWage" id="txtWage" value="<?php print $_POST["txtWage"]; ?>"/></td>
                </tr>
                
                <tr>
                <td><label>Tehdyt tunnit</label></td>
                <td><input type="text" class="textboxNormal required" style="width:350px;" name="txtHours" id="txtHours" value="<?php print $_POST["txtHours"]; ?>"/></td>
                </tr>
                
                <tr>
                <td colspan="2"><input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                <a href="javascript:void(0);" id="submitSalary" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Lisää korvaus</a>
                <a href="<?php print $APP->BASEURL; ?>/tyoaika.php?ID=45" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a></td>
                </tr>
            </table>
        </form>

        <p class="info">Voit merkitä vain tehdyt vuorot.</p>
    </div>

</div>