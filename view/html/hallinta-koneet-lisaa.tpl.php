<?php require_once('view/html/hallinta-napit.tpl.php'); ?>

<!-- Mid Body Start -->
<div class="kp_div">
    <h2>Lisää uusi kone</h2>

    <div class="tiedot_osio">
        <form id="frmPlaneAdd" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?ID=26'; ?>" >
            <table class="lomake_taulukko">
                <tr>
                    <td><label for="txtPlaneName">Koneen nimi</label></td>
                    <td><input type="text" name="txtPlaneName" id="txtPlaneName" value="<?php if (isset($_POST[txtPlaneName])) print $_POST[txtPlaneName]; ?>" class="textboxNormal required" style="width:350px;"/></td>
                </tr>

                <tr>
                    <td><label for="selPlaneType">Konetyyppi</label></td>
                    <td><select name="selPlaneType" id="selPlaneType" class="dropdown required" style="float:left;clear:both;width:150px;">
                            <option value="">Valitse konetyyppi</option>
                            <?php
                            $konetyypit = $hal->haeKonetyypit();
                            foreach ($konetyypit as $tyyppi) {
                                ?>
                                <option value="<?php print stripslashes($tyyppi['id']); ?>">
                                <?php print stripslashes($tyyppi['nimi']); ?>
                                </option>
                                <?php }
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                        <a href="javascript:void(0);" onclick="javascript:validatePlaneAdd();" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=27" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                        
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