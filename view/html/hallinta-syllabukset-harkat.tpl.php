<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Harjoitukset päivitettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} else if ($_GET['PF'] == '2') {
    $aMsg[0] = "Harjoitus poistettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} else if ($_GET['PF'] == '3') {
    $aMsg[0] = "Harjoituksen paikka vaihdettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<?php require_once('view/html/hallinta-napit.tpl.php'); ?>

<div class="kp_div" >
    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
<?php print $aMsg[0] ?>
    </div>
    <h2><?php echo $APP->PAGEVARS["HEADERTEXT"]; ?></h2>



    <div class="tiedot_osio">

        <form action="./hallinta.php?ID=67&syllabus_id=<?php print $_GET["syllabus_id"]; ?>" method="POST" id="frmExercises" name="frmExercises">

            <table class="datataulukko">
                <thead>
                    <tr>
                        <th>Nimi</th>
                        <th>Otsikko</th>
                        <th>Sisältö</th>
                        <th colspan="3">Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="harjoitus[0][nimi]" value="" class="textboxNormal" style="width:200px;"/>
                        </td>
                        <td>
                            <input type="text" name="harjoitus[0][otsikko]" value="" class="textboxNormal" style="width:375px;"/>
                        </td>
                        <td>
                            <input type="text" name="harjoitus[0][sisalto]" value="" class="textboxNormal" style="width:375px;"/>
                        </td>
                        <td colspan="3"></td>
                    </tr>
<?php
$x = sizeof($harjoitukset);
if ($x > 0) {
    $i = 1;
    foreach ($harjoitukset as $harkka) {
        ?>
                            <tr>
                                <td>
                                    <input type="text" name="harjoitus[<?php print $harkka["id"]; ?>][nimi]" id="harjoitusn<?php print $harkka["id"]; ?>" value="<?php print $harkka["nimi"]; ?>" class="textboxNormal required" style="width:200px;"/>
                                </td>
                                <td>
                                    <input type="text" name="harjoitus[<?php print $harkka["id"]; ?>][otsikko]" id="harjoituso<?php print $harkka["id"]; ?>" value="<?php print $harkka["otsikko"]; ?>" class="textboxNormal" style="width:375px;"/>
                                </td>
                                <td>
                                    <input type="text" name="harjoitus[<?php print $harkka["id"]; ?>][sisalto]" id="harjoituss<?php print $harkka["id"]; ?>" value="<?php print $harkka["sisalto"]; ?>" class="textboxNormal" style="width:375px;"/>
                                </td>
                                <td>
                                    <?php if ($i != $x) { ?>
                                        <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=67&act=movedown&syllabus_id=' . $_GET["syllabus_id"] . '&harjoitus_id=' . stripslashes($harkka['id']); ?>" >
                                            <img src="<?php print $APP->BASEURL; ?>/view/images/icons/arrow_down.png"/>
                                        </a>
            <?php }
        ?>
                                </td>
                                <td>
        <?php if ($i != 1) {
              ?>
                                        <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=67&act=moveup&syllabus_id=' . $_GET["syllabus_id"] . '&harjoitus_id=' . stripslashes($harkka['id']); ?>" >
                                            <img src="<?php print $APP->BASEURL; ?>/view/images/icons/arrow_up.png"/>
                                        </a>
            <?php }
        ?>
                                </td>
                                <td>
                                    <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=67&act=1&syllabus_id=' . $_GET["syllabus_id"] . '&harjoitus_id=' . stripslashes($harkka['id']); ?>" >
                                        <img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Poista tila" alt="Poista tila" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa harjoituksen?');"/>
                                    </a>
                                </td>
                            </tr>
        <?php
        $i++;
    }
} else {
    ?>
                        <tr>
                            <td colspan="4"  style="text-align:center;">Ei tallennettuja tiloja</td>
                        </tr>
    <?php }
?>
                </tbody>
            </table>

            <div class="divSpacing">
                <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
                <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=66" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Takaisin (syllabukset)</a>
                <a href="javascript:void(0);" onclick="javascript:validateExercisesEdit();" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>

            </div>
        </form>

    </div>
    <script type="text/javascript">
        function validateExercisesEdit()
        {
            $("#frmExercises").validate();
            $("#frmExercises").submit();
        }
    </script>

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