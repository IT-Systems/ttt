<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.timepicker.js"></script>
<!--<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.miniColors.js"></script>-->
<script src="<?php print $APP->BASEURL; ?>/view/html/wdCalendar/src/Plugins/jquery.colorselect.js" type="text/javascript"></script>
<script src="<?php print $APP->BASEURL; ?>/view/html/wdCalendar/src/Plugins/Common.js" type="text/javascript"></script>
<link href="<?php print $APP->BASEURL; ?>/view/html/wdCalendar/css/colorselect.css" rel="stylesheet" />

<script type="text/javascript">
    $(function() {
        $("#haku").autocomplete({
            source:"viestit.php?ID=34",
            change: function() {
                $(this).val("");
            },
            minLength: 2,
            select: function(event, ui) {
                var i = $("#vastaanottajat > *").filter(":hidden").size();
                var htmladd = '<input type="hidden" name="receiver['+i+']" value="'+ui.item.id+'" />'+ui.item.name+'</br>';
                $(htmladd).appendTo('#vastaanottajat');
                $('#vastaanottajat_ovat_oikealla').show();
            }
        });
<?php
// Tungetaan heti yksi vastaanottaja, jos tullaan omista käättäjistä ja klikattu viesti-napiskaa.
if (!empty($_GET["user_id"])) {
    $sUser = $v->haeKayttaja($_GET["user_id"]);
    ?>
            var htmladd = '<input type="hidden" name="receiver[0]" value="<?php print $sUser["userid"]; ?>"/><?php print $sUser["email"]; ?></br>';
            $(htmladd).appendTo('#vastaanottajat');
            $('#vastaanottajat_ovat_oikealla').show();
    <?php }
?>
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $( "#calDate" ).datepicker();

        //        $(".colors").miniColors({
        //            letterCase: 'uppercase'
        //        });
        var cv =$("#colorvalue").val() ;
        if(cv=="")
        {
            cv="-1";
        }
        $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "calColorCode" });

        $("#calStartTime").timepicker();
        $("#calEndTime").timepicker();

        $("#calShowButton").click(function(){
            $("#viestikalenteri").show("slow");
            $("#calShowButton").hide("fast");
            $("#calHideButton").show("fast");
            $("#calOn").val("1");
        });

        $("#calHideButton").click(function(){
            $("#calHideButton").hide("fast");
            $("#viestikalenteri").hide("slow");
            $("#calShowButton").show("slow");
            $("#calOn").val("");
        });

        $("#frmSubmitButton").click(function(){
            $("#frmNote").validate();
            $("#frmNote").submit();
        });
    });
</script>
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "<a href=" . '"' . $APP->BASEURL . '/viestit.php?ID=35&FID=2&note_id=' . $_GET["NID"] . '">' . "Viesti</a> ";
    if ($_GET["CM"] == '1')
        $aMsg[0].= "ja kalenterimerkintä ";
    $aMsg[0].= "lähetetty!";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
$backurl = $APP->BASEURL . "/viestit.php?ID=31";
if (!empty($_GET["FID"]))
    $backurl.= "&FID=" . $_GET["FID"];
?>
<div class="kp_div">

    <div class="<?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
<?php print $aMsg[0] ?>
    </div>

    <div class="etusivu_osio">

        <h3><?php print $APP->PAGEVARS["HEADERTEXT"]; ?></h3>

        <form action="" method="POST" name="frmNote" id="frmNote">
            <table class="lomake_taulukko" style="float:left;">
                <tr>
                    <td class="labeled">Vastaanottaja(t):</td>
                    <td id="vastaanottajat_input"><input type="text" id="haku" name="haku" value="" class="textboxNormal"/> <span id="vastaanottajat_ovat_oikealla" class="ui-icon_dib ui-icon-circle-arrow-e"></span></td>
                    <td id="vastaanottajat">
                        <?php
                        foreach ($_POST["receiver"] as $vo) {
                            $kayttaja = $v->haeKayttaja($vo);
                            print '<input type="hidden" name="receiver[' . $i . ']" value="' . $vo . '">' . $kayttaja["email"] . '<br/>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="labeled">Otsikko:</td>
                    <td><input type="text" name="txtSubject" id="subject" value="<?php print $_POST["txtSubject"]; ?>" class="textboxNormal required"/></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <textarea name="txtNote" id="note"><?php print $_POST["txtNote"]; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="hidden" name="hidSendNote" id="hidSendNote" value="1" />
                        <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-mail-closed"></span>Lähetä</a>
                        <a href="<?php print $backurl; ?>" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p class="info">Syötä vastaanottaja(t) -kenttään osa käyttäjän sähköpostiosoitteesta, niin ohjelma ehdottaa käyttäjiä.</p></td>
                </tr>
            </table>
            <?php
            /*
             * Kun saavutaan perusmeiningilää sivulle kalenterimerkintä on piilotettu, MUTTA jos syötteissä on ollut vikaa, pitää
             * silloin tehdä napin painaminen automaattisesti, ja asettaa postiarvot tällekin lomakkeelle, silloin JOS $_POST["calOn"] == 1.
             */
            $showCalNow = ($_POST["calOn"] == 1) ? true : false;
            $setCalDate = ($showCalNow && !empty($_POST["calTopic"])) ? $_POST["calTopic"] : "";
            $setCalDate = ($showCalNow && !empty($_POST["calDate"])) ? $_POST["calDate"] : "";
            $setCalStartTime = ($showCalNow && !empty($_POST["calStartTime"])) ? $_POST["calStartTime"] : "";
            $setCalEndTime = ($showCalNow && !empty($_POST["calEndTime"])) ? $_POST["calEndTime"] : "";
            $setCalPlace = ($showCalNow && !empty($_POST["calPlace"])) ? $_POST["calPlace"] : "";
            $setCalColorCode = ($showCalNow && !empty($_POST["calColorCode"])) ? $_POST["calColorCode"] : "";
            $setCalMorePreciseInfo = ($showCalNow && !empty($_POST["calMorePreciseInfo"])) ? $_POST["calMorePreciseInfo"] : "";
            $setCalConfirmParticipation = ($showCalNow && !empty($_POST["calConfirmParticipation"])) ? " checked" : "";
            $setCalOn = ($showCalNow) ? 1 : "";

// Tilojen valinta
            $tilat = $v->haeTilat();
            $tilaoptions = '<option value="">-- Valitse tila --</option>';
            if (sizeof($tilat) > 0) {
                foreach ($tilat as $tila) {
                    $tilaoptions.= '<option value="' . $tila["id"] . '"';
                    if ($setCalPlace == $tila["id"])
                        $tilaoptions.= ' selected';
                    $tilaoptions.= '>' . $tila["nimi"] . '</option>';
                }
            }
            ?>
            <div id="kalenterimerkinta">

                <a href="javascript:void(0);" id="calShowButton" title="Submit" style="font-weight:bold;<?php if ($showCalNow) print 'display:none;'; ?>" class="ui-od-button-with-icon ui-state-default ui-corner-all">
                    <span class="ui-icon ui-icon-plusthick"></span>Liitä kalenterimerkintä
                </a>
                <a href="javascript:void(0);" id="calHideButton" title="Submit" style="font-weight:bold;<?php if (!$showCalNow) print 'display:none;'; ?>" class="ui-od-button-with-icon ui-state-default ui-corner-all">
                    <span class="ui-icon ui-icon-minusthick"></span>Peruuta kalenterimerkintä
                </a>

                <table class="lomake_taulukko datataulukko" id="viestikalenteri"<?php if (!$showCalNow) print ' style="display:none;"'; ?>>
                    <tr>
                        <td>Otsikko:</td>
                        <td><input type="text" name="calTopic" id="calTopic" value="<?php print $setCalTopic; ?>"></td>
                    </tr>
                    <tr>
                        <td>Päivämäärä:</td>
                        <td><input type="text" name="calDate" id="calDate" value="<?php print $setCalDate; ?>"></td>
                    </tr>
                    <tr>
                        <td>Alkuaika:</td>
                        <td><input type="text" name="calStartTime" id="calStartTime" value="<?php print $setCalStartTime; ?>"></td>
                    </tr>
                    <tr>
                        <td>Loppuaika:</td>
                        <td><input type="text" name="calEndTime" id="calEndTime" value="<?php print $setCalEndTime; ?>"></td>
                    </tr>
                    <tr>
                        <td>Paikka:</td>
                        <td><select name="calPlace" id="calPlace"><?php print $tilaoptions; ?></select></td>
                    </tr>
                    <tr>
                        <td>Värikoodi:</td>
                        <td>
                            <div id="calendarcolor"></div>
                            <input id="calColorCode" name="calColorCode" type="hidden" value="<?php echo isset($event) ? $event->Color : "" ?>" />

                        </td>
                    </tr>
                    <tr>
                        <td>Tarkempia tietoja:</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea name="calMorePreciseInfo" id="calMorePreciseInfo" rows="3" cols="38"><?php print $setCalMorePreciseInfo; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Vahvista osallistuminen?</td>
                        <td><input type="checkbox" name="calConfirmParticipation" id="calConfirmParticipation" value="1"<?php print $setCalConfirmParticipation; ?>></td>
                    </tr>
                </table>
            </div>
            <input type="hidden" name="calOn" id="calOn" value="<?php print $setCalOn; ?>"/>
        </form>


    </div>

</div>