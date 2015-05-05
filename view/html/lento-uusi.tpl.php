<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Lento lisättiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.timepicker.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#strStartDate").datepicker({
            maxDate: 0
        });

        /*
         * Syllabuksien harjoitusten noutaminen alasvetovalikkoon
         */

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

        $("#strOffBlockTime").timepicker();
        $("#strTimeOfDeparture").timepicker();
        $("#strTimeOfArrival").timepicker();
        $("#strOnBlockTime").timepicker();

        $("#frmSubmitButton").click(function(){
            $("#frmUusiLento").validate();
            $("#frmUusiLento").submit();
        });
        $('input').keyup(function(event) {
            if (event.keyCode == 13) {
                lomake = $(this).closest('form');
                lomake.validate();
                lomake.submit();
            } 
        });

        /*
         * Lentokenttäkoodien päivittäminen asianmukaiseen kenttään
         */
        var kenttakoodit = [<?php
for ($i = 0; $i < sizeof($lentokentat); $i++) {
    $x = $i + 1;
    print '"'.$lentokentat[$i]["lyhenne"].'"';
    if ($x < sizeof($lentokentat)) print ",";
}
        ?>];
        $( "#strTakeoffPlace" ).autocomplete({
            source: kenttakoodit,
            minLength: 3
	});
        $("#lahtokenttavalinta").change(function() {
            var kentanNimi = $(this).val();
            $("#strTakeoffPlace").val(kentanNimi);
        });
        $( "#strDestPlace" ).autocomplete({
            source: kenttakoodit,
            minLength: 3
	});
        $("#maarakenttavalinta").change(function() {
            var kentanNimi = $(this).val();
            $("#strDestPlace").val(kentanNimi);
        });

        /*
         * Lentoaikojen automaattisen laskennan käskytys ja kentän täyttö helpottamaan lomakkeen täyttöä
         */

        $('#strOffBlockTime').change(function() {
            var aika = $(this).val();
            var newaika = modTime(aika);
            if (newaika != aika) $(this).val(newaika);
            laskeLentoaika();
        });
        $('#strOnBlockTime').change(function() {
            var aika = $(this).val();
            var newaika = modTime(aika);
            if (newaika != aika) $(this).val(newaika);
            laskeLentoaika();
        });
        $('#strTimeOfDeparture').change(function() {
            var aika = $(this).val();
            var newaika = modTime(aika);
            if (newaika != aika) $(this).val(newaika);
            laskeIlmaaika();
        });
        $('#strTimeOfArrival').change(function() {
            var aika = $(this).val();
            var newaika = modTime(aika);
            if (newaika != aika) $(this).val(newaika);
            laskeIlmaaika();
        });

        /*
         * IFR tai Night -lennon aikamäärekenttien automaattinen täydennys
         */

        $('#intIfr').change(function() {
            if ($(this).is(":checked")) {
                $("#strIfrTime").val($("#strFlyingTime").val());
            }
            else {
                $("#strIfrTime").val("00:00");
            }
        });
        $('#intNight').change(function() {
            if ($(this).is(":checked")) {
                $("#strNightTime").val($("#strFlyingTime").val());
            }
            else {
                $("#strNightTime").val("00:00");
            }
        });

        /*
         * Henkilöstökenttien pakollisuuksien määrääminen.
         */

        $("#intTeacherId").change(function() {
            var teacherId = $(this).val();
            $.ajax({
                url: "lennot.php?ID=76&userId="+teacherId,
                success: function(data){
                    if (data == 3) {
                        // Oppilas on päällikkönä, tällöin valvoja on pakollinen.
                        $("#intSupervisorId").addClass("required");
                        $("#intStudentId").removeClass("required");
                    }
                    else {
                        // Opettaja on päällikkönä, tällöin vain oppilas on pakollinen, mikäli lentotyyppi on koulutuslento.
                        $("#intSupervisorId").removeClass("required");
                        var actionId = $("#intActionId").val();
                        if (actionId == 8) {
                            $("#intStudentId").addClass("required");
                        }
                    }
                }
            });
        });
        $("#intActionId").change(function() {
            var actionId = $(this).val();
            var teacherId = $("#intTeacherId").val();
            if (teacherId) {
                $.ajax({
                    url: "lennot.php?ID=76&userId="+teacherId,
                    success: function(data){
                    if (data == 3) {
                            $("#intSupervisorId").addClass("required");
                            $("#intStudentId").removeClass("required");
                        }
                        else {
                            $("#intSupervisorId").removeClass("required");
                            if (actionId == 8) $("#intStudentId").addClass("required");
                        }
                    }
                });
            }
        });

        /*
         * Lentoaikojen laskenta
         */

        function laskeLentoaika() {
            var alku = $("#strOffBlockTime").val();
            var loppu = $("#strOnBlockTime").val();
            if (alku && loppu) {
                var erotus = laskeErotus(alku, loppu);
            }
            else {
                return;
            }
            $("#strFlyingTime").val(erotus);
        }

        function laskeIlmaaika() {
            var alku = $("#strTimeOfDeparture").val();
            var loppu = $("#strTimeOfArrival").val();
            if (alku && loppu) {
                var erotus = laskeErotus(alku, loppu);
            }
            else {
                return;
            }
            $("#strOnAirTime").val(erotus);
        }

        function laskeErotus(alku, loppu) {
            var alkuOsat = alku.split(":");
            var alkuMins = parseInt(alkuOsat[0], 10) * 60 + parseInt(alkuOsat[1], 10);
            var loppuOsat = loppu.split(":");
            var loppuMins = parseInt(loppuOsat[0], 10) * 60 + parseInt(loppuOsat[1], 10);
            var erotus = 0;
            if (alkuMins < loppuMins) {
                erotus = loppuMins - alkuMins;
            } else {
                var vrk = 60 * 24;
                loppuMins = loppuMins + vrk;
                erotus = loppuMins - alkuMins;
            }
            var tunnit = Math.floor(erotus/60);
            var minuutit = erotus % 60;
            if (minuutit < 10) minuutit = '0' + minuutit;
            if (tunnit < 10) tunnit = '0' + tunnit;
            var aika = tunnit+":"+minuutit;

            return aika;
        }

        function modTime(timeval) {
            var myKey = /:/;
            var timelen = timeval.length;
            var myMatch = timeval.search(myKey);
            if(myMatch == -1)
            {
                if (timelen < 3 || timelen > 4) {
                    alert("Syöttämääsi aikamäärettä ei voida käsitellä. Arvo tyhjennetään.");
                    timeval = '';
                }
                else {
                    var hrs = '';
                    var mins = '';
                    for (var i = 0; i < timelen; i++) {
                        if (timelen == 3 && i >= 1) {
                            mins+= timeval[i];
                        }
                        else if (timelen == 4 && i >= 2) {
                            mins+= timeval[i];
                        }
                        else {
                            hrs+= timeval[i];
                        }
                    }
                    if (hrs < 0 || hrs > 23 || mins < 0 || mins > 60) {
                        alert("Syöttämääsi aikamäärettä ei voida käsitellä. Arvo tyhjennetään.");
                        timeval = '';
                    }
                    else {
                        timeval = hrs+':'+mins;
                    }
                }
            }
            return timeval;
        }
    });
</script>
<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>

    <h2>Lennon lisääminen</h2>

    <form name="frmUusiLento" id="frmUusiLento" action="<?php print $_SERVER['PHP_SELF'] . '?ID=14'; ?>" method="POST">
        <div class="tiedot_osio">
            <table class="datataulukko lentotaulu" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Lennon tiedot</th>
                    </tr>
                </thead>
                <tbody>
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
                                    <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Alkamispäivä:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strStartDate" id="strStartDate" value="<?php if (isset($_POST["strStartDate"])) print $_POST["strStartDate"]; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Lähtöpaikka:</td>
                        <td>
                            <select class="textboxNormal" id="lahtokenttavalinta" name="lahtokentta">
                                <option value="">-- Valitse lentokenttä --</option>
<?php
foreach ($lentokentat as $kentta) {
    $xcls = ($_POST["lahtokentta"] == $kentta["lyhenne"]) ? " selected" : ""; ?>
                                <option value="<?php print $kentta["lyhenne"]; ?>"<?php print $xcls; ?>><?php print $kentta["nimi"] . " (" . $kentta["lyhenne"] . ")"; ?></option>
<?php
} ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strTakeoffPlace" id="strTakeoffPlace" value="<?php if (isset($_POST["strTakeoffPlace"])) print $_POST["strTakeoffPlace"]; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Määräpaikka:</td>
                        <td>
                            <select class="textboxNormal" id="maarakenttavalinta" name="maarakentta">
                                <option value="">-- Valitse lentokenttä --</option>
<?php
foreach ($lentokentat as $kentta) {
    $xcls = ($_POST["maarakentta"] == $kentta["lyhenne"]) ? " selected" : ""; ?>
                                <option value="<?php print $kentta["lyhenne"]; ?>"<?php print $xcls; ?>><?php print $kentta["nimi"] . " (" . $kentta["lyhenne"] . ")"; ?></option>
<?php
} ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strDestPlace" id="strDestPlace" value="<?php if (isset($_POST["strDestPlace"])) print $_POST["strDestPlace"]; ?>"/></td>
                    </tr>
                    <tr>
                        <?php
                        $xcls1 = '';
                        $xcls2 = '';
                        if (isset($_POST["intActionType"])) {
                            if ($_POST["intActionType"] == 1)
                                $xcls1 = " checked";
                            if ($_POST["intActionType"] == 2)
                                $xcls2 = " checked";
                        }
                        ?>
                        <td>Toiminnan laatu:</td>
                        <td>
                            <input type="radio" name="intActionType" value="1" class="required"<?php print $xcls1; ?>/> Yksityislento &nbsp;&nbsp;
                            <input type="radio" name="intActionType" value="2" class="required"<?php print $xcls2; ?>/> Ansiolento<br/>
                            <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intActionId" id="intActionId">
                                <option value="">-- Valitse toiminnan laatu --</option>
                                <?php
                                foreach ($toim_laadut as $laatu) {
                                    $xcls = ($laatu["id"] == $_POST["intActionId"]) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $laatu["id"]; ?>"<?php print $xcls; ?>><?php print $laatu["lyhenne"] . " - " . $laatu["nimi"]; ?></option>
    <?php }
?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Matkustajien lkm:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="intNumOfPassengers" id="intNumOfPassengers" value="<?php print (isset($_POST["intNumOfPassengers"])) ? $_POST["intNumOfPassengers"] : 0; ?>"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="checkbox" value="1" name="intFlightplanDecided"<?php if ($_POST["intFlightplanDecided"] == 1) print " checked"; ?>> Lentosuunnitelma on päätetty</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tiedot_osio">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Miehistö</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Päällikkö / Opettaja:</td>
                        <td>
                            <select class="textboxNormal required" style="width:330px;height:24px;margin-top:6px;" name="intTeacherId" id="intTeacherId">
                                <option value="">-- Valitse Päällikkö / Opettaja --</option>
                                <?php
                                foreach ($henkilot as $hlo) {
                                    $xcls = ($hlo["userid"] == $_POST["intTeacherId"]) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $hlo["userid"]; ?>"<?php print $xcls; ?>><?php print $hlo["lastname"] . " " . $hlo["firstname"]; ?></option>
    <?php }
?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Perämies / Oppilas:</td>
                        <td>
                            <select class="textboxNormal" style="width:330px;height:24px;margin-top:6px;" name="intStudentId" id="intStudentId">
                                <option value="">-- Valitse Perämies / Oppilas --</option>
                                <?php
                                foreach ($henkilot as $hlo) {
                                    $xcls = ($hlo["userid"] == $_POST["intStudentId"]) ? " selected" : "";
                                    ?>
                                    <option value="<?php print $hlo["userid"]; ?>"<?php print $xcls; ?>><?php print $hlo["lastname"] . " " . $hlo["firstname"]; ?></option>
    <?php }
?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Muu:</td>
                        <td>
                            <select class="textboxNormal" style="width:330px;height:24px;margin-top:6px;" name="intVisitorId" id="intVisitorId">
                                <option value="">-- Valitse muu henkilö --</option>
                                <?php
                                foreach ($henkilot as $hlo) {
                                    $xcls = ($hlo["userid"] == $_POST["intVisitorId"]) ? " selected" : "";
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
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Lentoon lähtöjä</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Päivällä:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="intTakeoffsDayTime" id="intTakeoffsDayTime" value="<?php print (isset($_POST["intTakeoffsDayTime"])) ? $_POST["intTakeoffsDayTime"] : 0; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Yöllä:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="intTakeoffsNightTime" id="intTakeoffsNightTime" value="<?php print (isset($_POST["intTakeoffsNightTime"])) ? $_POST["intTakeoffsNightTime"] : 0; ?>"/></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tiedot_osio">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Laskeutumisia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Päivällä:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="intLandingsDayTime" id="intLandingsDayTime" value="<?php print (isset($_POST["intLandingsDayTime"])) ? $_POST["intLandingsDayTime"] : 0; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Yöllä:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="intLandingsNightTime" id="intLandingsNightTime" value="<?php print (isset($_POST["intLandingsNightTime"])) ? $_POST["intLandingsNightTime"] : 0; ?>"/></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tiedot_osio">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Ajat (UTC)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Off-block:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strOffBlockTime" id="strOffBlockTime" value="<?php if (isset($_POST["strOffBlockTime"])) print $_POST["strOffBlockTime"]; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Lähtöaika:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strTimeOfDeparture" id="strTimeOfDeparture" value="<?php if (isset($_POST["strTimeOfDeparture"])) print $_POST["strTimeOfDeparture"]; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Saapumisaika:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strTimeOfArrival" id="strTimeOfArrival" value="<?php if (isset($_POST["strTimeOfArrival"])) print $_POST["strTimeOfArrival"]; ?>"/></td>
                    </tr>
                    <tr>
                        <td>On-block:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strOnBlockTime" id="strOnBlockTime" value="<?php if (isset($_POST["strOnBlockTime"])) print $_POST["strOnBlockTime"]; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Ilma-aika:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strOnAirTime" id="strOnAirTime" value="<?php if (isset($_POST["strOnAirTime"])) print $_POST["strOnAirTime"]; ?>"/> (hh:mm)</td>
                    </tr>
                    <tr>
                        <td>Lentoaika:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strFlyingTime" id="strFlyingTime" value="<?php if (isset($_POST["strFlyingTime"])) print $_POST["strFlyingTime"]; ?>"/> (hh:mm)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tiedot_osio">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="3" class="ul_toprow">Toiminnallinen aika</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td>
                            <input type="checkbox" name="intIfr" id="intIfr" value="1"<?php if ($_POST["intIfr"] == 1) print " checked"; ?>/> IFR &nbsp;&nbsp;
                            <input type="checkbox" name="intNight" id="intNight" value="1"<?php if ($_POST["intNight"] == 1) print " checked"; ?>/> Yö
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>IFR:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strIfrTime" id="strIfrTime" value="<?php print (isset($_POST["strIfrTime"])) ? $_POST["strIfrTime"] : "00:00"; ?>"/> (hh:mm)</td>
                        <td></td>
    <!--                    <td><input type="checkbox"/> Perusmittariaikaa</td>-->
                    </tr>
                    <tr>
                        <td>Yö:</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strNightTime" id="strNightTime" value="<?php print (isset($_POST["strNightTime"])) ? $_POST["strNightTime"] : "00:00"; ?>"/> (hh:mm)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="checkbox" name="intTravelFlight" value="1"<?php if ($_POST["intTravelFlight"] == 1) print " checked"; ?>/>Matkalento</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tiedot_osio">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Huomautukset</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <textarea name="strNotes" id="strNotes" class="textboxNormal" style="height:80px;width:433px;"><?php if (isset($_POST["strNotes"])) print $_POST["strNotes"]; ?></textarea>
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
    <?php }
?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Valvoja:</td>
                        <td>
                            <select class="textboxNormal" style="width:330px;height:24px;margin-top:6px;" name="intSupervisorId" id="intSupervisorId">
                                <option value="">Ei valvojaa</option>
                                <?php
                                foreach ($opettajat as $hlo) {
                                    $xcls = ($_POST["intSupervisorId"] == $hlo["userid"]) ? " selected" : "";
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
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Syllabus</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Syllabus:</td>
                        <td>
                            <select class="textboxNormal" style="width:330px;height:24px;margin-top:6px;" name="intSyllabusId" id="intSyllabusId">
                                <option value="">-- Valitse syllabus --</option>
                                <?php
                                foreach ($syllabukset as $syllabus) {
                                    $xcls = ($_POST["intSyllabusId"] == $syllabus["id"]) ? " selected" : "";
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
                        <td><p class="info">Voit valita useamman suorituksen pitämällä +ctrl -näppäimen pohjassa.</p></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="checkbox" name="intFlyAgain" value="1"<?php if ($_POST["intFlyAgain"] == 1) print " checked"; ?>> Lennettävä uudelleen</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tiedot_osio">
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th colspan="2" class="ul_toprow">Muuta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ohita työvuoron tarkistus:</td>
                        <td><input type="checkbox" name="intDontCheckShift" value="1"></td>
                    </tr>
                    <tr>
                        <td>Ohita työvuoron tarkistus:</td>
                        <td><input type="checkbox" name="intDontCheckParty" value="1"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tiedot_osio">
            <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
            <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
            <a href="<?php print $APP->BASEURL; ?>/lennot.php?ID=13" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>

        </div>

    </form>
</div>