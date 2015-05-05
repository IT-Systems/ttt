<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Harjoitus perustettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif (!isset($aMsg)) {
    if ($_GET["harjoitusId"]) {
        $otsikko_h2 = "Muokkaa harjoitusta";
    } else {
        $otsikko_h2 = "Uusi harjoitus";
    }
}
?>
<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.timepicker.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#strDate").datepicker({
            maxDate: 0
        });

        var syllabus = $("#intSyllabusId").val();
        if (syllabus > 0) {
            var harkka = $("#harjoitusId").val();
            $.getJSON("lennot.php?ID=68",{syllabusId: syllabus, harjoitusId: harkka}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '"';
                    if (j[i].optionSelected == 1) options += ' selected'
                    options += '>' + j[i].optionDisplay + '</option>';
                }
                $("#intSuoritusId").html(options);
            });
        }


        $("#intSyllabusId").change(function() {
            var syllabus = ($(this).val());
            $.getJSON("lennot.php?ID=68",{syllabusId: syllabus}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#intSuoritusId").html(options);
            });
        });

        $("#frmSubmitButton").click(function(){
            $("#frmNewPractise").validate();
            $("#frmNewPractise").submit();
        });
        $('input').keyup(function(event) {
            if (event.keyCode == 13) {
                lomake = $(this).closest('form');
                lomake.validate();
                lomake.submit();
            } 
        });
    });
</script>

<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>
    <h2><?php echo $otsikko_h2; ?></h2>

    <form action="<?php print $APP->BASEURL; ?>/lennot.php?ID=61" method="POST" id="frmNewPractise" name="frmNewPractise">
        <div class="tiedot_osio">
            <table class="datataulukko" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="4" class="ul_toprow">Harjoituksen tiedot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tunnus:</td>
                        <td><select></select> Mitä nää on?</td>
                    </tr>
                    <tr>
                        <td>Päivämäärä:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strDate" id="strDate" value="<?php
        if (isset($_POST["strDate"]))
            print $_POST["strDate"];
        elseif (isset($harjoitus))
            print $ak->dbDateToFiDate($harjoitus["paivamaara"]);
        ?>"/></td>
                    </tr>
                    <tr>
                        <td>Toiminnan laatu:</td>
                        <td colspan="3">
                            <input type="radio" name="intView" id="intView" class="required" value="1"<?php
                                   if (isset($_POST["intView"])) {
                                       if ($_POST["intView"] == 1)
                                           print " checked";
                                   }
                                   elseif (isset($harjoitus) && $harjoitus["toiminnan_laatu"] == 1)
                                       print " checked";
        ?>/>Yksityinen harjoitus<br/>
                            <input type="radio" name="intView" id="intView" class="required" value="2"<?php
                                   if (isset($_POST["intView"])) {
                                       if ($_POST["intView"] == 2)
                                           print " checked";
                                   }
                                   elseif (isset($harjoitus) && $harjoitus["toiminnan_laatu"] == 2)
                                       print " checked";
        ?>/>Ansiotoimintaa
                        </td>
                    </tr>
                    <tr>
                        <td>Harjoituksen kesto:</td>
                        <td><input type="text" name="strDuration" id="strDuration" class="textboxNormal required" value="<?php
                                   if (isset($_POST["strDuration"]))
                                       print $_POST["strDuration"];
                                   elseif (isset($harjoitus))
                                       print substr($harjoitus["kesto"], 0, -3);
        ?>"/><br/>
                            <input type="checkbox" name="intIfr" id="intIfr" value="1"<?php
                                   if (isset($_POST["intIfr"]) && $_POST["intIfr"] == 1)
                                       print " checked";
                                   elseif (isset($harjoitus) && $harjoitus["ifr_on"] == 1)
                                       print " checked";
        ?>/>Mittarilentoaikaa (IFR)<br/>
                            <input type="checkbox" name="intDual" id="intDual" value="1"<?php
                                   if (isset($_POST["intDual"]) && $_POST["intDual"] == 1)
                                       print " checked";
                                   elseif (isset($harjoitus) && $harjoitus["dual_on"] == 1)
                                       print " checked";
        ?>/>Koululentoaikaa (DUAL)
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tiedot_osio">
            <table class="datataulukko" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="4" class="ul_toprow">Harjoitukseen osallistuneet henkilöt</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Harjoittelija:</td>
                        <td>
                            <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intTraineeId" id="intTraineeId">
                                <option value="">-- Valitse Harjoittelija --</option>
                                <?php
                                foreach ($henkilot as $hlo) {
                                    $xcls = ((isset($_POST["intTraineeId"]) && $hlo["userid"] == $_POST["intTraineeId"]) ||
                                            (!isset($_POST["intTraineeId"]) && isset($harjoitus) && $hlo["userid"] == $harjoitus["harjoittelija_user_id"])) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $hlo["userid"]; ?>"<?php print $xcls; ?>><?php print $hlo["lastname"] . " " . $hlo["firstname"]; ?></option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Harjoittelija (perämies):</td>
                        <td>
                            <select class="textboxNormal" style="width:330px;height:24px;margin-top:6px;" name="intTrainee2Id" id="intTrainee2Id">
                                <option value="">-- Valitse Harjoittelija --</option>
                                <?php
                                foreach ($henkilot as $hlo) {
                                    $xcls = ((isset($_POST["intTrainee2Id"]) && $hlo["userid"] == $_POST["intTrainee2Id"]) ||
                                            (!isset($_POST["intTrainee2Id"]) && isset($harjoitus) && $hlo["userid"] == $harjoitus["harjoittelija_peramies_user_id"])) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $hlo["userid"]; ?>"<?php print $xcls; ?>><?php print $hlo["lastname"] . " " . $hlo["firstname"]; ?></option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Opettaja:</td>
                        <td>
                            <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intTeacherId" id="intTeacherId">
                                <option value="">-- Valitse Opettaja --</option>
                                <?php
                                foreach ($opettajat as $hlo) {
                                    $xcls = ((isset($_POST["intTeacherId"]) && $hlo["userid"] == $_POST["intTeacherId"]) ||
                                            (!isset($_POST["intTeacherId"]) && isset($harjoitus) && $hlo["userid"] == $harjoitus["opettaja_id"])) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $hlo["userid"]; ?>"<?php print $xcls; ?>><?php print $hlo["lastname"] . " " . $hlo["firstname"]; ?></option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tiedot_osio">
            <table class="datataulukko" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="4" class="ul_toprow">Huomautukset</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <textarea name="strNotes" id="strNotes" class="textboxNormal" style="width:430px;height:80px;"><?php
                                if (isset($_POST["strNotes"]))
                                    print $_POST["strNotes"];
                                elseif (isset($harjoitus))
                                    print $harjoitus["huomautukset"];
                                ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Kustannuspaikka:</td>
                        <td>
                            <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intCostPoolId" id="intCostPoolId">
                                <option value="">-- Valitse Kustannuspaikka --</option>
                                <?php
                                foreach ($kustannuspaikat as $paikka) {
                                    $xcls = ((isset($_POST["intCostPoolId"]) && $paikka["id"] == $_POST["intCostPoolId"]) ||
                                            !isset($_POST["intCostPoolId"]) && isset($harjoitus) && $paikka["id"] == $harjoitus["kustannuspaikka_id"]) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $paikka["id"]; ?>"<?php print $xcls; ?>><?php print $paikka["nimi"]; ?></option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tiedot_osio">
            <table class="datataulukko" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="4" class="ul_toprow">Suoritus</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Syllabus:</td>
                        <td>
                            <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intSyllabusId" id="intSyllabusId">
                                <option value="">-- Valitse Syllabus --</option>
                                <?php
                                foreach ($syllabukset as $syllabus) {
                                    $xcls = ((isset($_POST["intSyllabusId"]) && $syllabus["id"] == $_POST["intSyllabusId"]) ||
                                            (!isset($_POST["intSyllabusId"]) && isset($harjoitus) && $syllabus["id"] == $harjoitus["syllabus_id"])) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $syllabus["id"]; ?>"<?php print $xcls; ?>><?php print $syllabus["nimi"]; ?></option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Suoritus:</td>
                        <td>
                            <select class="textboxNormal" style="min-width:330px;height:80px;margin-top:6px;" name="intSuoritusId[]" id="intSuoritusId" multiple>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="checkbox" name="intFlyAgain" id="intFlyAgain" value="1"<?php
                                if (isset($_POST["intFlyAgain"]) && $_POST["intFlyAgain"] == 1)
                                    print " checked";
                                elseif (isset($harjoitus) && $harjoitus["uudelleen"] == 1)
                                    print " checked";
                                ?>/>Lennettävä uudelleen</td>
                    </tr>
                </tbody>
            </table>        
        </div>

        <input type="hidden" name="harjoitusId" id="harjoitusId" value="<?php if (isset($harjoitus)) print($harjoitus["id"]); ?>"/>

        <div class="tiedot_osio">
            <input type="hidden" name="hidAddExercise" id="hidAddExercise" value="1" />
            <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
            <a href="lennot.php?ID=69" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
        </div>
    </form>
</div>