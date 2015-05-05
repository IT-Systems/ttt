<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Salasana vaihdettu";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
if ($_GET['PF'] == '2') {
    $aMsg[0] = "Syöttämäsi uudet salasanat eivät olleet samat! Yritä uudestaan.";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
if ($_GET['PF'] == '3') {
    $aMsg[0] = "Salasanaa ei vaihdettu, sillä vanha salasana on sama kuin uusi.";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
if ($_GET['PF'] == '4') {
    $aMsg[0] = "Salasanaa ei vaihdettu, sillä syötit vanhan salasanasi väärin.";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}

?>
<script type="text/javascript">
    $(function() {
        $("#changePassword").click(function(){
            $("#frmPwChange").validate();
            $("#frmPwChange").submit();
        });
    });
</script>
    <?php require("omattiedot_napit.tpl.php"); ?>

<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>

    <h2>Vaihda salasana</h2>

    <div class="omattiedot_osio">

        <div class="formSpacing">
            <form id="frmPwChange" method="post" action="<?php print $_SERVER['PHP_SELF'].'?ID=46';?>" >
                <table class="no_padding">
                    <tr>
                        <td><label for="txtOldPassword">Nykyinen salasana</label></td>
                        <td><input rel="#frmPwChange" type="password" class="textboxNormal required" name="txtOldPassword" id="txtOldPassword" autocomplete="off"/></td>
                    </tr>
                    <tr>
                        <td><label for="txtNewPassword">Uusi salasana</label></td>
                        <td><input rel="#frmPwChange" type="password" class="textboxNormal required" name="txtNewPassword" id="txtNewPassword"/></label></td>
                    </tr>
                    <tr>
                        <td><label for="txtNewPassword2">Uusi salasana</label></td>
                        <td><input rel="#frmPwChange" type="password" class="textboxNormal required" name="txtNewPassword2" id="txtNewPassword2"/></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                           <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                    <a href="javascript:void(0);" id="changePassword" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-key"></span>Vaihda salasana</a> 
                        </td>
                    </tr>
                </table>
            </form>
        </div>

    </div>

</div>

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