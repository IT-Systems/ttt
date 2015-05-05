<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<!-- Mid Body Start -->
<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">

    <h2>Käyttäjän lisääminen</h2>


    <div class="tiedot_osio">

        <form id="frmUserAdd" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?ID=18'; ?>" >

            <table class="lomake_taulukko">

                <tr>
                    <td><label for="txtFirstName">Etunimi</label></td>
                    <td><input type="text" name="txtFirstName" id="txtFirstName" value="<?php if (isset($_POST[txtFirstName])) print $_POST[txtFirstName]; ?>" class="textboxNormal required" style="width:350px;"/></td>
                    <td></td>
                </tr>

                <tr>
                    <td><label for="txtLastName">Sukunimi</label></td>
                    <td><input type="text" name="txtLastName" id="txtLastName" value="<?php if (isset($_POST[txtLastName])) print $_POST[txtLastName]; ?>" class="textboxNormal required" style="width:350px;"/></td>
                    <td></td>
                </tr>

                <tr>
                    <td><label for="txtUsername">Käyttäjätunnus</label></td>
                    <td><input type="text" name="txtUsername" id="txtUsername" value="<?php if (isset($_POST[txtUsername])) print $_POST[txtUsername]; ?>" class="textboxNormal required" style="width:350px;"/></td>
                    <td><em>(A-Z a-z 0-9, ei erikoismerkkejä, oltava uniikki)</em></td>
                </tr>

                <tr>
                    <td><label for="txtEmail">Sähköposti</label></td>
                    <td><input type="text" name="txtEmail" id="txtEmail" value="<?php if (isset($_POST[txtEmail])) print $_POST[txtEmail]; ?>" class="textboxNormal required email" style="width:350px;"/></td>
                    <td><em>(oltava uniikki)</em></td>
                </tr>

                <tr>
                    <td><label for="txtPassword">Salasana</label></td>
                    <td><input type="password" name="txtPassword" id="txtPassword" value="<?php if (isset($_POST[txtPassword])) print $_POST[txtPassword]; ?>" class="textboxNormal required" style="width:150px;"/></td>
                    <td></td>
                </tr>

                <tr>
                    <td><label for="selRole">Käyttäjän rooli</label></td>
                    <td><select name="selRole" id="selRole" class="dropdown required" style="float:left;clear:both;width:150px;">
                            <option value="">Valitse rooli</option>
                            <?php
                            $aRoles = $hal->getRoles();
                            foreach ($aRoles as $aRow) {
                                ?>
                                <option value="<?php print stripslashes($aRow['roleid']); ?>">
                                    <?php print stripslashes($aRow['rolename']); ?>
                                </option>
                            <?php } ?>
                        </select></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="3">
                        <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                        <a href="javascript:void(0);" onclick="javascript:validateUserAdd();" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=17"  class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->

<script type="text/javascript">
    function validateUserAdd()
    {
        $("#frmUserAdd").validate();
        $("#frmUserAdd").submit();
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