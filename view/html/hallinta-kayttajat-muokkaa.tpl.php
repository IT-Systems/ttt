<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<!-- Mid Body Start -->
<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">

    <h2>Käyttäjän tietojen muokkaus</h2>


    <div class="tiedot_osio">
        <form name="frmUserEdit" id="frmUserEdit" action="<?php print $_SERVER['PHP_SELF'] . '?ID=19&RecID=' . stripslashes($aEachUsersDetails[0]['userid']); ?>" method="post">

            <table class="lomake_taulukko">
                <tr>
                    <td><label for="txtFirstName">Etunimi</label></td>
                    <td><input type="text" name="txtFirstName" id="txtFirstName" value="<?php
if ($_POST['txtFirstName']) {
    print $_POST['txtFirstName'];
} else {
    print stripslashes($aEachUsersDetails[0]['firstname']);
}
?>" class="textboxNormal required" style="width:350px;"/></td>
                    <td></td>
                </tr>

                <tr>  
                    <td><label for="txtLastName">Sukunimi</label></td>
                    <td><input type="text" name="txtLastName" id="txtLastName" value="<?php
                               if ($_POST['txtLastName']) {
                                   print $_POST['txtLastName'];
                               } else {
                                   print stripslashes($aEachUsersDetails[0]['lastname']);
                               }
?>" class="textboxNormal required" style="width:350px;"/></td>
                    <td></td>
                </tr>

                <tr>
                    <td><label for="txtUsername">Käyttäjätunnus</label></td>
                    <td><input type="text" name="txtUsername" id="txtUsername" value="<?php
                               if ($_POST['txtUsername']) {
                                   print $_POST['txtUsername'];
                               } else {
                                   print stripslashes($aEachUsersDetails[0]['username']);
                               }
?>" class="textboxNormal required" style="width:350px;" <?php
                               if ($aEachUsersDetails[0]['username'] == 'admin') {
                                   print 'readonly="readonly"';
                               }
?>/></td>
                    <td><em>(A-Z a-z 0-9, ei erikoismerkkejä, oltava uniikki)</em></td>
                </tr>

                <tr>
                    <td><label for="txtEmail">Sähköpostiosoite</label></td>
                    <td><input type="text" name="txtEmail" id="txtEmail" value="<?php
                               if ($_POST['txtEmail']) {
                                   print $_POST['txtEmail'];
                               } else {
                                   print stripslashes($aEachUsersDetails[0]['email']);
                               }
?>" class="textboxNormal required email" style="width:350px;"/></td>
                    <td><em>(Oltava uniikki)</em></td>
                </tr>

                <?php if ($aEachUsersDetails[0]['username'] != 'admin') { ?>
                    <tr>
                        <td><label for="txtPassword">Salasana</label></td>
                        <td><input type="text" name="txtPassword" id="txtPassword" value="" class="textboxNormal" style="width:150px;"/></td>
                        <td><em>(Täytä vain, jos haluat vaihtaa salasanasi &mdash; muuten jätä kenttä tyhjäksi)</em></td>
                    </tr>

                    <tr>

                        <td><label class="makeBold" for="selRole">Rooli</label><br/></td>
                        <td> <select name="selRole" id="selRole" class="dropdown required" style="float:left;clear:both;width:150px;">
                                <option value="">Valitse rooli</option>
                                <?php
                                $aRoles = $hal->getRoles();
                                foreach ($aRoles as $aRow) {
                                    ?>
                                    <option <?php if ($_POST['selRole'] == $aRow['roleid'] || stripslashes($aEachUsersDetails[0]['roleid']) == stripslashes($aRow['roleid'])) print 'selected="selected"'; ?> value="<?php print stripslashes($aRow['roleid']); ?>">
                                        <?php print stripslashes($aRow['rolename']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                <?php } ?>

                <tr>
                    <td colspan="3">
                        <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
                        <a href="javascript:void(0);" onclick="javascript:editUser();"title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=17" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
    function editUser()
    {
        $("#frmUserEdit").validate();
        $("#frmUserEdit").submit();
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