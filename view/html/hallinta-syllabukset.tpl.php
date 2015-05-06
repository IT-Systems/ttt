<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Syllabukset päivitettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<?php require_once('view/html/hallinta-napit.tpl.php'); ?>

<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>
        
    <h2>Syllabuksien listaus</h2>
    <div class="tiedot_osio">
        <a href="#" id="addSyllabus" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all">
            <span class="ui-icon ui-icon-plus"></span>Lisää syllabus
        </a>
        <br/>
        <br/>
        <form action="" method="POST" id="addSyllabuses">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th>Syllabus</th>
                        <th>Selite</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (sizeof($syllabukset) > 0) {
                        foreach ($syllabukset as $syllabus) {
                            ?>
                            <tr>
                                <td><?php print $syllabus["nimi"]; ?></td>
                                <td><?php print $syllabus["selite"]; ?></td>
                                <td>
                                    <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=67&syllabus_id=<?php print $syllabus["id"]; ?>" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-pencil"></span>Harjoitukset</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="2">Ei tallennettuja syllabuksia.</td>
                        </tr>
                        <?php }
                    ?>
                </tbody>
            </table>
            <br/>
            <a href="javascript:void(0);" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all" id="saveSyllabuses">
                <span class="ui-icon ui-icon-disk"></span>Tallenna
            </a>
        </form>
    </div>
</div>

<script type="text/javascript">
var counter = 0;
$(document).ready(function(){
    $("#saveSyllabuses").hide();
    $("#addSyllabus").click(function(e){
        $(".datataulukko tbody").append('<tr><td><input type="text" name="syllabus['+counter+'][name]"></td><td><input type="text" name="syllabus['+counter+'][desc]"></td><td></td></tr>');
        $("#saveSyllabuses").show('slow');
        counter++;
    });
    $("#saveSyllabuses").click(function(e){
        $("#addSyllabuses").submit();
    })
});
</script>