<?php
if ($_GET["PF"] == 1) {
    $aMsg[0] = "Peruutettu lento / lennot tallennettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#strDate").datepicker();

        $("#frmSubmitButton").click(function(){
            $("#frmPeruutettuLento").validate();
            $("#frmPeruutettuLento").submit();
        });
    });
</script>

<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div">
    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>

    <h2>Peruutetun lennon tallennus</h2>
    
    <form name="frmPeruutettuLento" id="frmPeruutettuLento" action="<?php print $_SERVER['PHP_SELF'].'?ID=62';?>" method="POST">
        <div class="tiedot_osio">
        <table class="datataulukko" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th colspan="2" class="ul_toprow">Tiedot</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Päivämäärä:</td>
                    <td><input type="text" class="textboxNormal required" style="width:159px;" name="strDate" id="strDate" value="<?php if (!empty($_POST["strDate"])) print $_POST["strDate"]; ?>"/></td>

                </tr>
                <tr>
                    <td>Ilma-alus:</td>
                    <td>
                        <select class="textboxNormal required" style="width:165px;height:24px;" name="intKoneId" id="intKoneId">
                            <option value="">-- Valitse ilma-alus --</option>
<?php
foreach ($koneet as $kone) {
    $xcls = ($kone["id"] == $_POST["intKoneId"]) ? " selected" : "";
    ?>
                            <option value="<?php print $kone["id"]; ?>"<?php print $xcls; ?>><?php print $kone["nimi"]; ?></option>
<?php
} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Lähtöpaikka:</td>
                    <td><input type="text" class="textboxNormal required" style="width:159px;" name="strTakeoffPlace" id="strTakeoffPlace" value="<?php if (!empty($_POST["strTakeoffPlace"])) print $_POST["strTakeoffPlace"]; ?>"/></td>
                </tr>
                <tr>
                    <td>Määräpaikka:</td>
                    <td><input type="text" class="textboxNormal required" style="width:159px;" name="strDestPlace" id="strDestPlace" value="<?php if (!empty($_POST["strDestPlace"])) print $_POST["strDestPlace"]; ?>"/></td>
                </tr>
                <tr>
                    <td>Päällikkö:</td>
                    <td>
                        <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intTeacherId" id="intTeacherId">
                            <option value="">-- Valitse Päällikkö  --</option>
<?php
foreach ($henkilot as $hlo) {
    $xcls = ($hlo["userid"] == $_POST["intTeacherId"]) ? " selected" : "";
?>
                            <option value="<?php print $hlo["userid"]; ?>"<?php print $xcls; ?>><?php print $hlo["lastname"] . " " . $hlo["firstname"]; ?></option>
<?php
} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Toiminnan laatu:</td>
                    <td>
                        <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intActionId" id="intActionId">
                            <option value="">-- Valitse toiminnan laatu --</option>
<?php
foreach ($toim_laadut as $laatu) {
    $xcls = ($laatu["id"] == $_POST["intActionId"]) ? " selected" : "";
?>
                            <option value="<?php print $laatu["id"]; ?>"<?php print $xcls; ?>><?php print $laatu["lyhenne"]." - ".$laatu["nimi"]; ?></option>
<?php
} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Syy:</td>
                    <td>
<?php
foreach ($syyt as $syy) {
    $xcls = ($syy["id"] == $_POST["intReasonId"]) ? " checked" : "";
?>
                        <input type="radio" name="intReasonId" class="required" value="<?php print $syy["id"]; ?>"<?php print $xcls; ?>/><?php print $syy["syy"]; ?><br/>
<?php
} ?>
                        <p class="info">Anna tarvittaessa lisäselvitys huomautussarakkeessa</p>
                    </td>
                </tr>
        </table>
        </div>

       
        <div class="tiedot_osio">
        <table class="datataulukko" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th colspan="2" class="ul_toprow">Huomautukset</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <textarea name="strNotes" id="strNotes" class="textboxNormal" style="height:80px;width:433px;"><?php if (!empty($_POST["strNotes"])) print $_POST["strNotes"]; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Kustannuspaikka:</td>
                    <td>
                        <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intCostPoolId" id="intCostPoolId">
                            <option value="">-- Valitse kustannuspaikka --</option>
<?php
foreach ($kustannuspaikat as $kustp) {
    $xcls = ($_POST["intCostPoolId"] == $kustp["id"]) ? " selected" : "";
?>
                            <option value="<?php print $kustp["id"]; ?>"<?php print $xcls; ?>><?php print $kustp["nimi"]; ?></option>
<?php
} ?>
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
                    <th colspan="2" class="ul_toprow">Peruutuksen toistaminen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <p class="info">Mikäli yllä olevan kaltaisia lentoja on peruutettu useita,<br/>
                        voit valita alta peruutetuiksi ilmoitettavien lentojen määrän.<br/>
                        Kaikki lennot ilmoitetaan peruutetuiksi samoilla tiedoilla.</p>
                    </td>
                </tr>
                <tr>
                    <td>Lentojen määrä:</td>
                    <td>
                        <select class="textboxNormal" style="width:220px;height:24px;margin-top:6px;" name="intFlightCount" id="intFlightCount">
                            <option value="">-- Valitse määrä --</option>
<?php
for ($i = 1; $i <= 10; $i++) {
    $xcls = ($_POST["intFlightCount"] == $i) ? " selected" : "";
    ?>
                            <option value="<?php print $i; ?>"<?php print $xcls; ?>><?php print $i; ?></option>
<?php
} ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>

        <div class="tiedot_osio">
            <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
            <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
            <a href="<?php print $APP->BASEURL; ?>/lennot.php?ID=13" title="Submit" style="float:left;margin:8px 0px 4px 4px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>

        </div>

    </form>
</div>