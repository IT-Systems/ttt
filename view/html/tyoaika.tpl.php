<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Työvuoro tallennettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
elseif (!isset($aMsg)) {
    $aMsg[0] = "Uusi työvuoro";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery.ui.timepicker.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#txtStartDate").datepicker();
        $("#txtEndDate").datepicker();

        $("#txtStartTime").timepicker();
        $("#txtEndTime").timepicker();

        $("#submitShift").click(function(){
            $("#frmShiftAdd").validate();
            $("#frmShiftAdd").submit();
        });

    });
</script>

<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">

    <div class="<?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>

    <?php require "tyoaika_napit.tpl.php"; ?>

    <div class="formSpacing">
        <form id="frmShiftAdd" method="post" action="<?php print $_SERVER['PHP_SELF'].'?ID=43';?>" >
            <div class="divSpacing">
                <label>Aloituspäivä</label>
                <input type="text" name="txtStartDate" id="txtStartDate" value="<?php print $_POST["txtStartDate"]; ?>" class="textboxNormal required" style="width:350px;"/>
            </div>

            <div class="divSpacing">
                <label>Aloitusaika</label>
                <input type="text" name="txtStartTime" id="txtStartTime" value="<?php print $_POST["txtStartTime"]; ?>" class="textboxNormal required" style="width:350px;"/>
            </div>

            <div class="divSpacing">
                <label>Lopetuspäivä</label>
                <input type="text" name="txtEndDate" id="txtEndDate" value="<?php print $_POST["txtEndDate"]; ?>" class="textboxNormal required" style="width:350px;"/>
            </div>

            <div class="divSpacing">
                <label>Lopetusaika</label>
                <input type="text" name="txtEndTime" id="txtEndTime" value="<?php print $_POST["txtEndTime"]; ?>" class="textboxNormal required" style="width:350px;"/>
            </div>

            <div class="divSpacing">
                <label>Aikavyöhyke</label>
                <select name="txtTimezone" id="txtTimezone" class="textboxNormal required" style="width:350px;height:24px;">
<?php
$valittuzone = (isset($_POST["txtTimezone"])) ? $_POST["txtTimezone"] : 56;
foreach ($timezones as $key => $value) {
    $xcls = ($valittuzone == $value["id"]) ? " selected" : "";

    ?>
                    <option value="<?php print $value["id"]; ?>"<?php print $xcls; ?>><?php print $value["name"]; ?></option>
<?php
} ?>
                </select>
            </div>

            <div class="divSpacing">
                <label>Kommentti</label>
                <textarea class="textboxNormal" style="width:350px;height:60px;" name="txtComment"><?php print $_POST["txtComment"]; ?></textarea>
            </div>

            <div class="divSpacing">
                <label>On jatkoa katkaistuun vuoroon</label>
                <input type="checkbox" name="chkContinue" id="chkContinue" value="" class="textboxNormal"<?php if (isset($_POST["chkContinue"])) print " checked"; ?>/>
            </div>

            <div class="divSpacing">
                <label>On kahden ohjaajan toimintaa</label>
                <input type="checkbox" name="chkTwoPilots" id="chkTwoPilots" value="" class="textboxNormal"<?php if (isset($_POST["chkTwoPilots"])) print " checked"; ?>/>
            </div>

            <div class="divSpacing">
                <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                <a href="javascript:void(0);" id="submitShift" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Lisää työvuoro</a>
            </div>

        </form>
    </div>

</div>