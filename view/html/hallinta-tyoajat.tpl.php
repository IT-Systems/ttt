<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Työvuoro päivitettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
elseif ($_GET['PF'] == '2') {
    $aMsg[0] = "Työvuoro lisättiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}?>

<?php require_once('view/html/hallinta-napit.tpl.php'); ?>

<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>

    <h2>Työvuorot</h2>

    <div class="tiedot_osio">
<?php
if (empty($_GET["shiftId"]) && empty($_GET["addShift"])) { ?>
        <script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
        <script type="text/javascript">
        $(function() {
            $.datepicker.setDefaults($.datepicker.regional["fi"]);
            $("#strFromDate").datepicker();
            $("#strToDate").datepicker();
        });
        </script>
        <table class="datataulukko" cellspacing="0" style="border:1px solid #000;">
           <form action="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79" method="POST" id="teacherSelectionForm">
               <thead>
                   <tr>
                       <th>Opettaja</th>
                       <th>Alkaen</th>
                       <th>Päättyen</th>
                       <th<?php if (isset($tiitseri)) print ' colspan="2"'; ?>></th>
                   </tr>
               </thead>
               <tbody>
                   <tr>
                       <td>
                            <select name="teacherId" class="textboxNormal required">
                            <option value="">-- Valitse opettaja --</option>
<?php
    foreach ($opettajat as $ope) {
        $xcls = "";
        if ($ope["userid"] == $tiitseri) $xcls = " selected"; ?>
                <option value="<?php print $ope["userid"]; ?>"<?php print $xcls; ?>><?php print $ope["lastname"] . " " . $ope["firstname"] . " (" . $ope["username"] . ")"; ?></option>
<?php
    } ?>
                            </select>
                       </td>
                       <td>
                           <input type="text" name="strFromDate" id="strFromDate" value="<?php print ($start) ? $start : date("d.m.Y",mktime(0,0,0,date("m"),date("d")-14,date("Y"))); ?>" class="textboxNormal required" style="width:100px">
                       </td>
                       <td>
                           <input type="text" name="strToDate" id="strToDate" value="<?php print ($to) ? $to : date("d.m.Y"); ?>" class="textboxNormal required" style="width:100px;">
                       </td>
                       <td>
                           <a href="javascript:void(0);" onclick="javascript:validateTeacherForm();" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Hae</a>
                       </td>
<?php
    if (isset($tiitseri)) { ?>
                       <td>
                            <a href="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79&addShift=1&teacherId=<?php print $tiitseri; ?>&strFromDate=<?php print $start; ?>&strToDate=<?php print $to; ?>" style="font-weight: bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Lisää vuoro</a>
                       </td>
<?php
    } ?>
                   </tr>
                </tbody>
            </form>
        </table>
<?php
    if ($vuorot || $blaah == 1) {
        if ($blaah == 1) {
            print "<br/>Käyttäjällä ei ole tallennettuja vuoroja valitulle ajalle.";
        }

        ?>
        <br/><br/>
<?php
        if ($vuorot) {

        ?>
        <div id="data_table_container">
            <table class="datataulukko" cellspacing="0" style="width:100%;border:1px solid #000;">
                <thead>
                    <tr>
                        <th>Alkoi</th>
                        <th>Päättyi</th>
                        <th>Kesto</th>
                        <th>Lentotunnit</th>
                        <th>Huom</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php
        $ak = new aikakalu();
        $aika48max = 20*60*60;
        $aika24max = 10*60*60;
        $lento48max = 14*60*60;
        $lento24max = 8*60*60;
        $aika24 = 24*60*60;
        $aika48 = 48*60*60;
        $x = 1;
        $kestototal = 0;
        $lentototal = 0;
        $col1="#e2e4ff";
        $col2="#ffffff";

        foreach ($vuorot as $key => $vuoro) {
            list($alkupvm, $alkuaika) = explode(" ", $vuoro["aloitus"]);
            list($loppupvm, $loppuaika) = explode(" ", $vuoro["lopetus"]);

            // Varoitusten päättely, työvuorot saadaan laskettua suoraan tästä arraystä, mutta lentojen suhteen ei ole aivan niin yksinkertaista ....
            $vrk = array(); // Kerätään tähän vuorot joiden loppumisaika on vähemmän kuin 24h tämän vuoron alkamisaika.
            $vrk2 = array(); // sama ja vertauksena 48h
            $j = $key + 1;
            $til = sizeof($vuorot) - $j;
            for ($i = $j; $i < $til; $i++) {
                if ($vuoro["loppuint"] - $vuorot[$i]["loppuint"] < $aika24) $vrk[] = $vuorot[$i];
              //  if ($vuoro["loppuint"] - $vuorot[$i]["loppuint"] < $aika48) $vrk2[] = $vuorot[$i];
            }

            $msg = array(); // Varoitusten kerääminen
            $tot24 = $vuoro["kestoint"];
            $tot48 = $vuoro["kestoint"];

            if (sizeof($vrk) > 0) {
                foreach ($vrk as $lVuoro) {
                    if (($lVuoro["alkuint"]+$aika24) > $vuoro["loppuint"]) {
                        $tot24+= $lVuoro["kestoint"];
                    }
                    else {
                        $lisaaika = $lVuoro["loppuint"] + $aika24 - $vuoro["loppuint"];
                        $tot24+= $lisaaika;
                    }
                }
            }
            if ($tot24 > $aika24max) {
                $yli = $tot24 - $aika24max;
                $mins = $yli / 60;
                $yliH = floor($mins/60);
                $yliM = $mins % 60;
                if (strlen($yliM) == 1) $yliM = "0".$yliM;
                $yliaika = $yliH.":".$yliM;
                $msg[] = "Työajan ylitysvaroitus: {$yliaika} / 24h";
            }
            
            if (sizeof($vrk2) > 0) {
                foreach ($vrk2 as $lVuoro) {
                    if (($lVuoro["alkuint"]+$aika48) > $vuoro["loppuint"]) {
                        $tot48+= $lVuoro["kestoint"];
                    }
                    else {
                        $lisaaika = $lVuoro["loppuint"] + $aika48 - $vuoro["loppuint"];
                        $tot48+= $lisaaika;
                    }
                }
            }
            if ($tot48 > $aika48max) {
                $yli = $tot48 - $aika48max;
                $mins = $yli / 60;
                $yliH = floor($mins/60);
                $yliM = $mins % 60;
                if (strlen($yliM) == 1) $yliM = "0".$yliM;
                $yliaika = $yliH.":".$yliM;
                $msg[] = "Työajan ylitysvaroitus: {$yliaika} / 48h";
            }

            $kestototal+= $vuoro["kestoint"];
            $lentototal+= $vuoro["lentoint"];

            // Värittelyt riveille nätisti
            $staili = ($x==1) ? ' style="background-color:'.$col1.' !important;' : ' style="background-color:'.$col2.' !important;';
            ?>
                    <tr>
                        <td<?php print $staili.'"'; ?>><?php print $ak->dbDateToFiDate($alkupvm) . ' klo ' . substr($alkuaika, 0, -3); ?></td>
                        <td<?php print $staili.'"'; ?>><?php print $ak->dbDateToFiDate($loppupvm) . ' klo ' . substr($loppuaika, 0, -3); ?></td>
                        <td<?php print $staili.'"'; ?>><?php print $vuoro["kesto"]; ?></td>
                        <td<?php print $staili.'"'; ?>><?php if ($vuoro["lentotunnit"] != "0:00") print $vuoro["lentotunnit"]; ?></td>
                        <td<?php print $staili.'color:red;"'; ?>><?php foreach ($msg as $key => $message) {
                            print $message;
                            if (isset($msg[$key+1])) print '<br/>';
                        } ?></td>
                        <td<?php print $staili.'"'; ?>>
                            <a href="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79&shiftId=<?php print $vuoro["id"]; ?>&teacherId=<?php print $tiitseri; ?>&strFromDate=<?php print $start; ?>&strToDate=<?php print $to; ?>">
                                <img src="<?php print $APP->BASEURL;?>/view/images/icon-edit.png" title="Muokkaa" alt="Muokkaa" class="icon"/>
                            </a>
                        </td>
                    </tr>
<?php
            $x=$x*-1;
        }
        $staili = ($x==1) ? ' style="background-color:'.$col1.' !important;' : ' style="background-color:'.$col2.' !important;';
        $staili.= 'border-top:1px solid #000;font-size:14px;"';
        $mins = $lentototal / 60;
        $yliH = floor($mins/60);
        $yliM = $mins % 60;
        if (strlen($yliM) == 1) $yliM = "0".$yliM;
        $lentototal = $yliH.":".$yliM;
        $mins = $kestototal / 60;
        $yliH = floor($mins/60);
        $yliM = $mins % 60;
        if (strlen($yliM) == 1) $yliM = "0".$yliM;
        $kestototal = $yliH.":".$yliM;
        ?>
                    <tr>
                        <th colspan="2">Yhteensä</th>
                        <td<?php print $staili; ?>><?php print $kestototal; ?></td>
                        <td<?php print $staili; ?>><?php print $lentototal; ?></td>
                        <td<?php print $staili; ?> colspan="2"></td>
                    </tr>
                </tbody>

            </table>
        </div>
<?php
        if ($vuorot) { 
            ?>
        <br/>
        <form id="printData" action="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79" method="POST" target="_BLANK">
            <input type="hidden" name="strSize" id="sizeToPrint" value=""/>
            <input type="hidden" name="strFilter" id="filterToPrint" value=""/>
            <input type="hidden" name="intUser" value="<?php print $tiitseri; ?>"/>
            <input type="hidden" name="strFromDate" value="<?php print $start; ?>"/>
            <input type="hidden" name="strToDate" value="<?php print $to; ?>"/>
            <input type="hidden" name="printPage" value="1"/>
            <input type="submit" value="Tulostettava sivu" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"/>
        </form>
<?php
        } ?>

        <link href="<?php print $APP->BASEURL; ?>/view/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
        <link href="<?php print $APP->BASEURL; ?>/view/css/jquery.dataTables_themeroller.css" rel="stylesheet" type="text/css"/>
        <script src="<?php print $APP->BASEURL; ?>/view/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(function () {
                $('#data_table').dataTable({
                    bPaginate: true,
                    aaSorting: [],
                    bJQueryUI: true,
                    aLengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'kaikki']],
                    iDisplayLength: -1,
                    oLanguage: {
                        sSearch: 'Suodata:',
                        sInfo: 'Näytettäviä työvuoroja _TOTAL_',
                        sInfoEmpty: 'Ei näytettäviä työvuoroja',
                        sZeroRecords: 'Ei hakutuloksia',
                        sInfoFiltered: ' (kaikkiaan yhteensä _MAX_ työvuoroa)',
                        sLengthMenu: 'Näytä _MENU_ työvuoroa/työvuorot'
                    },
                    aoColumnDefs: [ { bSortable: false, aTargets: [0, 1, 2, 3] } ]
                });
                $("#data_table_container input").change(function(){
                    $("#filterToPrint").val($(this).val());
                });
                $("#sizeToPrint").val("-1");
                $("#data_table_container select").change(function(){
                    $("#sizeToPrint").val($(this).val());
                });
            });
        </script>
<?php
        }
    }
}
elseif(isset($vuoro)) {
    $ak = new aikakalu();
    $teacherId = $vuoro["user_id"];
    list($apvm, $atime) = explode(" ", $vuoro["aloitus"]);
    list($lpvm, $ltime) = explode(" ", $vuoro["lopetus"]);
?>
        <script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
        <script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.timepicker.js"></script>
        <script type="text/javascript">
            $(function() {
                $.datepicker.setDefaults($.datepicker.regional["fi"]);
                $("#strStartDate").datepicker();
                $("#strEndDate").datepicker();
                $("#strStartTime").timepicker();
                $("#strEndTime").timepicker();
            });
        </script>

        <form action="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79" method="POST" id="shiftUpdateForm">
            <table class="datataulukko">
                <tr>
                    <th><label>Aloituspvm</label></th>
                    <td><input type="text" name="strStartDate" id="strStartDate" class="required" style="width:80px;" value="<?php print $ak->dbDateToFiDate($apvm); ?>"/></td>
                </tr>
                <tr>
                    <th><label>Aloitusaika</label></th>
                    <td><input type="text" name="strStartTime" id="strStartTime" class="required" style="width:80px;" value="<?php print substr($atime,0,-3); ?>"/></td>

                </tr>
                <tr>
                    <th><label>Päättymispvm</label></th>
                    <td><input type="text" name="strEndDate" id="strEndDate" class="required" style="width:80px;" value="<?php print $ak->dbDateToFiDate($lpvm); ?>"/></td>

                </tr>
                <tr>
                    <th><label>Päättymisaika</label></th>
                    <td><input type="text" name="strEndTime" id="strEndTime" class="required" style="width:80px;" value="<?php print substr($ltime,0,-3); ?>"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="javascript:void(0);" onclick="javascript:validateShiftUpdate();" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79&teacherId=<?php print $teacherId; ?>&strFromDate=<?php print $start; ?>&strToDate=<?php print $to; ?>" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="updateShift" value="1"/>
            <input type="hidden" name="intShiftId" value="<?php print $_GET["shiftId"]; ?>"/>
            <input type="hidden" name="intTeacherId" value="<?php print $tiitseri; ?>"/>
            <input type="hidden" name="strFromDate" value="<?php print $start; ?>"/>
            <input type="hidden" name="strToDate" value="<?php print $to; ?>"/>
        </form>

        <script type="text/javascript">
            function validateShiftUpdate() {
                $("#shiftUpdateForm").validate();
                $("#shiftUpdateForm").submit();
            }
        </script>


<?php
}
elseif (isset($_GET["addShift"])) { ?>
        <script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
        <script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.timepicker.js"></script>
        <script type="text/javascript">
            $(function() {
                $.datepicker.setDefaults($.datepicker.regional["fi"]);
                $("#strStartDate").datepicker();
                $("#strEndDate").datepicker();
                $("#strStartTime").timepicker();
                $("#strEndTime").timepicker();
            });
        </script>

        <form action="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79" method="POST" id="shiftInsertForm">
            <table class="datataulukko">
                <tr>
                    <th><label>Aloituspvm</label></th>
                    <td><input type="text" name="strStartDate" id="strStartDate" class="required" style="width:80px;" value=""/></td>
                </tr>
                <tr>
                    <th><label>Aloitusaika</label></th>
                    <td><input type="text" name="strStartTime" id="strStartTime" class="required" style="width:80px;" value=""/></td>

                </tr>
                <tr>
                    <th><label>Päättymispvm</label></th>
                    <td><input type="text" name="strEndDate" id="strEndDate" class="required" style="width:80px;" value=""/></td>

                </tr>
                <tr>
                    <th><label>Päättymisaika</label></th>
                    <td><input type="text" name="strEndTime" id="strEndTime" class="required" style="width:80px;" value=""/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="javascript:void(0);" onclick="javascript:validateShiftInsert();" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="<?php print $APP->BASEURL; ?>/hallinta.php?ID=79&teacherId=<?php print $_GET["teacherId"]; ?>&strFromDate=<?php print $start; ?>&strToDate=<?php print $to; ?>" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="insertShift" value="1"/>
            <input type="hidden" name="intTeacherId" value="<?php print $_GET["teacherId"]; ?>"/>
            <input type="hidden" name="strFromDate" value="<?php print $from; ?>"/>
            <input type="hidden" name="strToDate" value="<?php print $to; ?>"/>
        </form>

        <script type="text/javascript">
            function validateShiftInsert() {
                $("#shiftInsertForm").validate();
                $("#shiftInsertForm").submit();
            }
        </script>
<?php
} ?>


    </div>

</div>

<script type="text/javascript">
function validateTeacherForm()
{
    $("#teacherSelectionForm").validate();
    $("#teacherSelectionForm").submit();
}
</script>