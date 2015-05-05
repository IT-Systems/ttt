<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<!-- Mid Body Start -->
<div class="kp_div">

    <h2>Muokkaa konetta</h2>
    
    <div class="tiedot_osio">
        <form id="frmPlaneAdd" method="post" action="<?php print $_SERVER['PHP_SELF'].'?ID=25';?>" >
            <table class="lomake_taulukko">
                <tr>
                    <td><label for="txtPlaneName">Koneen nimi</label></td>
                    <td><input type="text" name="txtPlaneName" id="txtPlaneName" value="<?php print (isset($_POST[txtPlaneName])) ? $_POST[txtPlaneName] : $kone["nimi"];?>" class="textboxNormal required" style="width:350px;"/></td>
                </tr>
                
                <tr>
                    <td><label for="selPlaneType">Konetyyppi</label></td>
                    <td><select name="selPlaneType" id="selPlaneType" class="dropdown required" style="float:left;clear:both;width:150px;">
                        <option value="">Valitse konetyyppi</option>
                            <?php
                            $konetyypit = $hal->haeKonetyypit();
                            $val_type = (isset($_POST["selPlaneType"])) ? $_POST["selPlaneType"] : $kone["konetyyppi"];

                            foreach($konetyypit as $tyyppi) {
                                $optsel = "";
                                if ($tyyppi["id"] == $val_type) $optsel = " selected";
                            ?>
                                <option value="<?php print stripslashes($tyyppi['id']);?>"<?php print $optsel; ?>>
                                    <?php print stripslashes($tyyppi['nimi']);?>
                                </option>
                            <?php
                            } 
                            ?>
                    </select></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="kone_id" value="<?php print (isset($_POST["kone_id"])) ? $_POST["kone_id"] : $kone["id"]; ?>"/>
                        <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
                        
                        <a href="javascript:void(0);" onclick="javascript:validatePlaneAdd();" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $_SERVER['PHP_SELF']?>?ID=27" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->

<script type="text/javascript">
    function validatePlaneAdd()
    {
        $("#frmPlaneAdd").validate();
        $("#frmPlaneAdd").submit();
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