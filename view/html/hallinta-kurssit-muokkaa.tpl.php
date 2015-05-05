<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>

    <h2><?php print ($_GET["mode"] == "add") ? "Uusi kurssi" : "Muokkaa kurssin tietoja"; ?></h2>

    <div class="tiedot_osio">

        <form id="frmCourseAdd" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?ID=71&mode=save'; ?>" >

            <table class="datataulukko">
                <tr>
                    <th><label for="strCourseName">Nimi</labe></th>
                    <td><input type="text" name="strCourseName" id="strCourseName" class="textboxNormal required" style="float:left;clear:both;width:200px;" value="<?php
if ($kurssi) print $kurssi["kurssi_nimi"];
                    ?>"></td>
                </tr>
                <tr>
                    <th><label for="strCourseDesc">Kuvaus</label></th>
                    <td><input type="text" name="strCourseDesc" id="strCourseDesc" class="textboxNormal" style="float:left;clear:both;width:200px;" value="<?php
if ($kurssi) print $kurssi["kurssi_kuvaus"];
                    ?>"></td>
                </tr>
                <tr>
                    <th><label for="intSyllabusId">Syllabus</label></th>
                    <td>
                        <select name="intSyllabusId" id="intSyllabusId" class="dropdown required" style="float:left;clear:both;width:207px;">
                            <option value="">-- Valitse syllabus --</option>
<?php
foreach ($syllabukset as $syl) {
    $sel = "";
    if ($kurssi && $kurssi["kurssi_syllabus_id"] == $syl["id"]) $sel = " selected"; ?>
                        <option value="<?php print $syl["id"]; ?>"<?php print $sel; ?>><?php print $syl["nimi"]; ?></option>
<?php
} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Opettajat</th>
                    <td>
                        <select multiple name="strTeacherIdList[]" size="5" class="dropdown required" style="float:left;clear:both;width:207px;">
<?php
$opeArray = array();
if ($kurssi) {
    $opeArray = explode(",", $kurssi["kurssi_opettajat"]);
}
foreach ($opettajat as $ope) {
    $sel = (in_array($ope["userid"], $opeArray)) ? " selected" : "";
    ?>
                            <option value="<?php print $ope["userid"]; ?>"<?php print $sel; ?>><?php print $ope["lastname"] . " " . $ope["firstname"]; ?></option>
<?php
} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                <tr>
                    <th>Oppilaat</th>
                    <td>
                        <select multiple name="strStudentIdList[]" size="10" class="dropdown required" style="float:left;clear:both;width:207px;">
<?php
$oppArray = array();
if ($kurssi) {
    $oppArray = explode(",", $kurssi["kurssi_oppilaat"]);
}
foreach ($oppilaat as $oppilas) {
    $sel = (in_array($oppilas["userid"], $oppArray)) ? " selected" : "";
    ?>
                            <option value="<?php print $oppilas["userid"]; ?>"<?php print $sel; ?>><?php print $oppilas["lastname"] . " " . $oppilas["firstname"]; ?></option>
<?php
} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                        <a href="javascript:void(0);" onclick="javascript:validateCourseForm();" title="Submit"class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=71" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
            </table>

            <input type="hidden" name="kurssiId" value="<?php print (!empty($_GET["kurssiId"])) ? $_GET["kurssiId"] : "0"; ?>"/>
        </form>
    </div>
</div>

<script type="text/javascript">
function validateCourseForm()
{
    $("#frmCourseAdd").validate();
    $("#frmCourseAdd").submit();
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