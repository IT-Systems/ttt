<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Toiminnan laadut päivitettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<?php require_once('view/html/hallinta-napit.tpl.php'); ?>

<div class="kp_div">
    
    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>

    <h2>Toiminnan laadut</h2>

    <div class="tiedot_osio">
        <a href="#" id="addActionQlty" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all">
            <span class="ui-icon ui-icon-plus"></span>Lisää toiminnan laatu
        </a>
        <br/>
        <br/>
        <form action="" method="POST" id="addActionQualities">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th>Lyhenne</th>
                        <th>Nimi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (sizeof($laadut) > 0) {
                        foreach ($laadut as $q) {
                            ?>
                            <tr>
                                <td><?php print $q["lyhenne"]; ?></td>
                                <td><?php print $q["nimi"]; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="2">Ei tallennettuja toiminnan laatuja</td>
                        </tr>
                        <?php }
                    ?>
                </tbody>
            </table>
            <br/>
            <a href="javascript:void(0);" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all" id="saveActionQualities">
                <span class="ui-icon ui-icon-disk"></span>Tallenna
            </a>
        </form>
    </div>
</div>

<script type="text/javascript">
var counter = 0;
$(document).ready(function(){
    $("#saveActionQualities").hide();
    $("#addActionQlty").click(function(e){
        $(".datataulukko tbody").append('<tr><td><input type="text" name="qualities['+counter+'][short]"></td><td><input type="text" name="qualities['+counter+'][name]"></td><td></td></tr>');
        $("#saveActionQualities").show('slow');
        counter++;
    });
    $("#saveActionQualities").click(function(e){
        $("#addActionQualities").submit();
    })
});
</script>