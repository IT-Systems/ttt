<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.timepicker.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#strFromDate").datepicker();
        $("#strToDate").datepicker();

        $("#frmSubmitButton").click(function(){
            $("#frmSearch").validate();
            $("#frmSearch").submit();
        });
    });
</script>

<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div">

    <h2>Lentojen etsiminen</h2>


    <div class="tiedot_osio">
        <a href="<?php print $APP->BASEURL; ?>/lennot.php?ID=53&mode=simple" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-zoomout"></span>Yksinkertainen haku</a>
        <br><br>
        <form action="<?php print $APP->BASEURL; ?>/lennot.php?ID=53&mode=extended" method="POST" id="frmSearch" name="frmSearch">
            <table class="datataulukko" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="4" class="ul_toprow">Hakuehdot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aikaväli</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strFromDate" id="strFromDate" value="<?php if (!empty($_POST["strFromDate"])) print $_POST["strFromDate"]; ?>" /></td>
                        <td> - </td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strToDate" id="strToDate" value="<?php if (!empty($_POST["strToDate"])) print $_POST["strToDate"]; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Näkymä</td>
                        <td colspan="3">
                            <input type="radio" name="intView" id="intView" class="required" value="1"<?php if ($_POST["intView"] == 1) print " checked"; ?>/>Yleinen<br/>
                            <input type="radio" name="intView" id="intView" class="required" value="2"<?php if ($_POST["intView"] == 2) print " checked"; ?>/>JAR 1.080 Logbook
                        </td>
                    </tr>
                    <tr>
                        <td>Kone:</td>
                        <td colspan="3">
                            <select name="intPlaneId" id="intPlaneId" style="width:120px;height:24px;" class="textboxNormal">
                                <option value="0">Mikä tahansa</option>
                                <?php foreach ($koneet as $kone) {
                                    $selolpt = ($kone["id"] == $_POST["intPlaneId"]) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $kone["id"]; ?>"<?php print $selopt; ?>><?php print $kone["nimi"]; ?></option>
                                    <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Konetyyppi:</td>
                        <td colspan="3">
                            <select name="intPlaneTypeId" id="intPlaneTypeId" style="width:120px;height:24px;" class="textboxNormal">
                                <option value="0">Mikä tahansa</option>
                                <?php foreach ($konetyypit as $tyyppi) {
                                    $selopt = ($tyyppi["id"] == $_POST["intPlaneTypeId"]) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $tyyppi["id"]; ?>"<?php print $selopt; ?>><?php print $tyyppi["nimi"]; ?></option>
                                    <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Lennon laatu:</td>
                        <td colspan="3">
                            <input type="checkbox" name="intIfrType" id="intIfrType" value="1"<?php if ($_POST["intIfrType"] == 1) print " checked"; ?>/>IFR &nbsp;&nbsp;
                            <input type="checkbox" name="intNightType" id="intNightType" value="1"<?php if ($_POST["intNightType"] == 1) print " checked"; ?>/>Yö &nbsp;&nbsp;
                            <input type="checkbox" name="intTravelType" id="intTravelType" value="1"<?php if ($_POST["intTravelType"] == 1) print " checked"; ?>/>Matka
                        </td>
                    </tr>
                    <tr>
                        <td>Valvoja:</td>
                        <td colspan="3">
                            <select name="intSupervisorId" id="intSupervisorId" style="width:120px;height:24px;" class="textboxNormal">
                                <option value="0">Kuka tahansa</option>
<?php foreach ($valvojat as $valvoja) {
    $selopt = ($valvoja["userid"] == $_POST["intSupervisorId"]) ? " selected" : "";
    ?>
                                    <option value="<?php print $valvoja["userid"]; ?>"<?php print $selopt; ?>><?php print $valvoja["firstname"] . " " . $valvoja["lastname"]; ?></option>
                                    <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Päällikkö:</td>
                        <td colspan="3">
                            <select name="intTeacherId" id="intTeacherId" style="width:120px;height:24px;" class="textboxNormal">
                                <option value="0">Kuka tahansa</option>
<?php foreach ($henkilot as $hlo) {
    $selopt = ($hlo["userid"] == $_POST["intTeacherId"]) ? " selected" : "";
    ?>
                                    <option value="<?php print $hlo["userid"]; ?>"<?php print $selopt; ?>><?php print $hlo["firstname"] . " " . $hlo["lastname"]; ?></option>
                                    <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Perämies:</td>
                        <td colspan="3">
                            <select name="intStudentId" id="intStudentId" style="width:120px;height:24px;" class="textboxNormal">
                                <option value="0">Kuka tahansa</option>
<?php foreach ($henkilot as $hlo) { 
    $selopt = ($hlo["userid"] == $_POST["intStudentId"]) ? " selected" : "";
    ?>                                    <option value="<?php print $hlo["userid"]; ?>"<?php print $selopt; ?>><?php print $hlo["firstname"] . " " . $hlo["lastname"]; ?></option>
                                    <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Kustannuspaikka:</td>
                        <td colspan="3">
                            <select name="intCostPoolId" id="intCostPoolId" style="width:120px;height:24px;" class="textboxNormal">
                                <option value="0">Mikä tahansa</option>
<?php foreach ($kustannuspaikat as $kp) {
    $selopt = ($kp["id"] == $_POST["intCostPoolId"]) ? " selected" : "";
    ?>
                                    <option value="<?php print $kp["id"]; ?>"<?php print $selopt; ?>><?php print $kp["nimi"]; ?></option>
                                    <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="divSpacing">
                <input type="hidden" name="hidExtendedSearch" id="hidExtendedSearch" value="1" />
                <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" style="float:left;margin:0px 0px 0px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span>Etsi</a>
            </div>
        </form>
    </div>
<?php
include 'lento-hakutulokset.tpl.php';
?>
</div>