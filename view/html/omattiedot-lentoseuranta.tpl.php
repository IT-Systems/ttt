<?php require("omattiedot_napit.tpl.php"); ?>

<div class="kp_div">

    <h2>Lentojen seuranta</h2>
    <div class="omattiedot_osio">
<?php
$eiOpp = array(1, 2);
if (in_array($USER->iRole, $eiOpp)) {
    if (sizeof($oppilaat) > 0) {
    ?>
        <script type="text/javascript">
        $(function() {
            $("#getStudent").click(function(){
                $("#frmStudentSelect").validate();
                $("#frmStudentSelect").submit();
            });
        });
        </script>

        <form name="frmStudentSelect" id="frmStudentSelect" method="POST" action="<?php print $APP->BASEURL; ?>/omattiedot.php?ID=72">
            <select name="intOppilasId" id="intOppilasId" class="dropdown required" style="float:left;clear:both;width:207px;">
                <option value="">-- Valitse oppilas --</option>
<?php
        foreach ($oppilaat as $oppilas) {
            $sel = ($_POST["intOppilasId"] == $oppilas["userid"]) ? " selected" : "";
            print '<option value="'.$oppilas["userid"].'"'.$sel.'>'.$oppilas["lastname"] . " " . $oppilas["firstname"] .'</option>'."\n";
        }
?>
            </select>
            <a href="javascript:void(0);" id="getStudent" title="Submit" style="float:left;margin:0px 0px 0px 8px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all">
                <span class="ui-icon ui-icon-key"></span>Tarkastele
            </a>
        </form>
        <div style="clear:both;"></div>
        <p class="info">Valitse alasvetovalikosta oppilas, jonka suorituksia haluat tarkastella, ja paina tarkastele.</p>
<?php
    }
    else { ?>
     Ei oppilaita, ei lennon seurantaa...
<?php
    }
}

if ($USER->iRole == 3 OR !empty($_POST["intOppilasId"])) {
    $userId = (!empty($_POST["intOppilasId"])) ? $_POST["intOppilasId"] : $USER->ID;

    // Lets fetch users syllabuses
    $syllabuses = $ot->getStudentsSyllabuses($userId);
    if (sizeof($syllabuses) > 0) {
        // Loop each syllabus
        foreach ($syllabuses as $syll) {
            $syllabus = $len->haeSyllabukset($syll["syllabus"]);
            $_GET["syllabusId"] = $syll["syllabus"]; // Function uses get -parameter
            $tasks = $len->haeSyllabuksenHarjoitukset();  // Fetch each task for this syllabus
            ?>
         <table class="datataulukko">
             <thead>
                 <tr>
                     <th colspan="3"><h4><?php print $syllabus["nimi"] . " (" . $syllabus["selite"] . ")"; ?></h4></th>
                 </tr>
                 <tr>
                     <th>Nimi</th><th>Selite</th><th>Suoritettu</th>
                 </tr>
             </thead>
             <tbody>
<?php
            foreach ($tasks as $task) {
                // Check if task is completed succesfully
                $completed = $ot->syllabusTaskAccepted($userId, $task["id"]);
                $xcls = ($completed) ? ' class="completed"' : "";
?>
                 <tr<?php print $xcls; ?>>
                     <td><?php print $task["nimi"]; ?></td>
                     <td><?php print $task["otsikko"]; ?></td>
                     <td><?php if ($completed) { ?><img src="<?php print $APP->BASEURL; ?>/view/images/icons/accept_green.png"><?php } ; ?></td>
                 </tr>
<?php
            } ?>
             </tbody>
         </table>
<?php
        }
    }
    else {
        // User doesn't belong to any course or there's no syllabus set for course(s) (not really possible).
        if (empty($_POST["intOppilasId"])) {
            $viesti = "Et kuulu millekään kurssille, eikä täten syllabuksia ja niihin kohdistuneita suorituksia voida listata.";
        }
        else {
            $viesti = "Käyttäjä ei kuulu millekään kurssille, eikä täten syllabuksia ja niihin kohdistuneita suorituksia voida listata.";
        }
        print $viesti;
    }

}
?>
    </div>
</div>

<script type="text/javascript">
$(function() {
   $('#intOppilasId').change(function(event) {
       $('#frmStudentSelect').submit();
   });
});
</script>