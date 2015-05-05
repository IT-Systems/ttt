<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<!-- Mid Body Start -->
<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">

    <h2>Tiedotteen/ohjeen lisääminen</h2>

    <div class="tiedot_osio">

        <form id="frmInfoAdd" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?ID=39'; ?>" >
            <table class="lomake_taulukko">
                <tr>
                    <td><label for="txtInfoTitle">Otsikko</label></td>
                    <td><input type="text" name="txtInfoTitle" id="txtInfoTitle" value="<?php if (isset($_POST[txtTitle])) print $_POST[txtTitle]; ?>" class="textboxNormal required" style="width:350px;"/></td>
                </tr>

                <tr>
                    <td><label for="selInfoType">Tyyppi</label></td>
                    <td><select name="selInfoType" id="selInfoType" class="dropdown required" style="float:left;clear:both;width:150px;">
                            <option value="">Valitse tyyppi</option>
                            <?php
                            $tyypit = array("0" => "Tiedote", 2 => "Toiminnallinen ohje");
                            foreach ($tyypit as $id => $tyyppi) {
                                ?>
                                <option value="<?php print $id; ?>"><?php print $tyyppi; ?></option>
                                <?php }
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td><label for="txtInfoText">Teksti</label></td>
                    <td><textarea class="textboxNormal required" style="width:350px;height:70px;" name="txtInfoText"></textarea></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                        <a href="javascript:void(0);" onclick="javascript:validateInfoAdd();" title="Submit"class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=38" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->

<script type="text/javascript">
    function validateInfoAdd()
    {
        $("#frmInfoAdd").validate();
        $("#frmInfoAdd").submit();
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