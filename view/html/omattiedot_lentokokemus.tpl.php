<script type="text/javascript">
    $(function() {
        $("#frmSubmitButton").click(function(){
            $("#frmExperience").validate();
            $("#frmExperience").submit();
        });
    });
</script>
<?php require("omattiedot_napit.tpl.php"); ?>

<div class="kp_div">
    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>
    <h2>Oma lentokokemus</h2>

    <div class="omattiedot_osio">

        <form action="<?php print $APP->BASEURL; ?>/omattiedot.php?ID=47" method="POST" name="frmExperience" id="frmExperience">
<!--            Pitääkö voida poistaa tehdyt muutokset kokemuksiin (pl. konetyyppikohtainen)?-->
            <table class="uusilento datataulukko" cellspacing="0" cellpadding="0" style="width:650px;">
                <thead>
                    <tr>
                        <th colspan="3" class="ul_toprow">Lentokokemus</th>
                        <td style="background:#FFF !important;"></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Kokonaislentokokemus:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpAll" id="strExpAll" value="<?php print (!isset($_POST["strExpAll"])) ? $kokonaislentokokemus : $_POST["strExpAll"]; ?>"/></td>
                        <td><?php
        if ($muutokset["kokonais"] <> 0) {
            if ($muutokset["kokonais"] < 0)
                print "-";
            print $ak->muunnaMinuutitAjaksi(abs($muutokset["kokonais"]));
        }
        ?></td>
                        <td style="background:#FFF !important;vertical-align:top;" rowspan="20">
                            <p class="info" style="padding-left:10px;">
                            Kokemustiedot lasketaan järjestelmään syötetyistä lennoista.<br/><br/>
                            Mikäli olet ollut lennon päällikkö tai perämies, lasketaan aika joko PIC/COP -sarakkeeseen sekä jokaiseen muuhun lentoa koskevaan sarakkeeseen.
                            Poikkeuksena tästä on vain se, että lennonvalvojana toimimisesta (ylläpito ja opettajat) kokemusta kertyy Valvojana -sarakkeeseen, mutta ei minnekään muualle.<br/><br/>
                            Voit ilmoittaa järjestelmän laskemiin aika-arvoihin mahdolliset muutokset lisäämällä tiettyyn arvoon aikaa.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>IFR:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpIfr" id="strExpIfr" value="<?php print (!isset($_POST["strExpIfr"])) ? $ifrkokemus : $_POST["strExpIfr"]; ?>"/></td>
                        <td><?php
                            if ($muutokset["ifr"] <> 0) {
                                if ($muutokset["ifr"] < 0)
                                    print "-";
                                print $ak->muunnaMinuutitAjaksi(abs($muutokset["ifr"]));
                            }
        ?></td>
                    </tr>
                    <tr>
                        <td>Yö:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpNight" id="strExpNight" value="<?php print (!isset($_POST["strExpNight"])) ? $yokokemus : $_POST["strExpNight"]; ?>"/></td>
                        <td><?php
                            if ($muutokset["yo"] <> 0) {
                                if ($muutokset["yo"] < 0)
                                    print "-";
                                print $ak->muunnaMinuutitAjaksi(abs($muutokset["yo"]));
                            }
        ?></td>
                    </tr>
                    <tr>
                        <td>Simulaattorikokemus:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpSimulator" id="strExpSimulator" value="<?php print (!isset($_POST["strExpSimulator"])) ? $simkokemus : $_POST["strExpSimulator"]; ?>""/></td>
                        <td><?php
                            if ($muutokset["sim"] <> 0) {
                                if ($muutokset["sim"] < 0)
                                    print "-";
                                print $ak->muunnaMinuutitAjaksi(abs($muutokset["sim"]));
                            }
        ?></td>
                    </tr>
                    <tr>
                        <td>Päällikkönä (PIC):</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpPic" id="strExpPic" value="<?php print (!isset($_POST["strExpPic"])) ? $pickokemus : $_POST["strExpPic"]; ?>"/></td>
                        <td><?php
                            if ($muutokset["pic"] <> 0) {
                                if ($muutokset["pic"] < 0)
                                    print "-";
                                print $ak->muunnaMinuutitAjaksi(abs($muutokset["pic"]));
                            }
        ?></td>
                    </tr>
                    <tr>
                        <td>Perämiehenä (COP):</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpCop" id="strExpCop" value="<?php print (!isset($_POST["strExpCop"])) ? $copkokemus : $_POST["strExpCop"]; ?>"/></td>
                        <td><?php
                            if ($muutokset["cop"] <> 0) {
                                if ($muutokset["cop"] < 0)
                                    print "-";
                                print $ak->muunnaMinuutitAjaksi(abs($muutokset["cop"]));
                            }
        ?></td>
                    </tr>
                    <tr>
                        <td>Oppilaana (DUAL):</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpDual" id="strExpDual" value="<?php print (!isset($_POST["strExpDual"])) ? $dualkokemus : $_POST["strExpDual"]; ?>"/></td>
                        <td><?php
                            if ($muutokset["dual"] <> 0) {
                                if ($muutokset["dual"] < 0)
                                    print "-";
                                print $ak->muunnaMinuutitAjaksi(abs($muutokset["dual"]));
                            }
        ?>
                    </tr>
                    <tr>
                        <td>Opettajana:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpTeacher" id="strExpTeacher" value="<?php print (!isset($_POST["strExpTeacher"])) ? $opettajakokemus : $_POST["strExpTeacher"]; ?>"/></td>
                        <td><?php
                            if ($muutokset["teach"] <> 0) {
                                if ($muutokset["teach"] < 0)
                                    print "-";
                                print $ak->muunnaMinuutitAjaksi(abs($muutokset["teach"]));
                            }
        ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Lentoonlähtöjä päivällä:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpTakeoffsDay" id="strExpTakeoffsDay" value="<?php print (!isset($_POST["strExpTakeoffsDay"])) ? $lahtojapaivallakokemus : $_POST["strExpTakeoffsDay"]; ?>"/></td>
                        <td><?php print ($muutokset["toffs_day"] <> 0) ? $muutokset["toffs_day"] : ""; ?></td>
                    </tr>
                    <tr>
                        <td>Lentoonlähtöjä yöllä:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpTakeoffsNight" id="strExpTakeoffsNight" value="<?php print (!isset($_POST["strExpTakeoffsNight"])) ? $lahtojayollakokemus : $_POST["strExpTakeoffsNight"]; ?>"/></td>
                        <td><?php print ($muutokset["toffs_night"] <> 0) ? $muutokset["toffs_night"] : ""; ?></td>
                    </tr>
                    <tr>
                        <td>Laskeutumisia päivällä:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpLandingsDay" id="strExpLandingsDay" value="<?php print (!isset($_POST["strExpLandingsDay"])) ? $laskujapaivallakokemus : $_POST["strExpLandingsDay"]; ?>"/></td>
                        <td><?php print ($muutokset["lands_day"] <> 0) ? $muutokset["lands_day"] : ""; ?></td>
                    </tr>
                    <tr>
                        <td>Laskeutumisia yöllä:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpLandingsNight" id="strExpLandingsNight" value="<?php print (!isset($_POST["strExpLandingsNight"])) ? $laskujayollakokemus : $_POST["strExpLandingsNight"]; ?>"/></td>
                        <td><?php print ($muutokset["lands_night"] <> 0) ? $muutokset["lands_night"] : ""; ?></td>
                    </tr>
<?php
if ($USER->iRole == 1 || $USER->iRole == 2) { ?>
                    <tr>
                        <td>Valvojana:</td>
                        <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpSupervisor" id="strExpSupervisor" value="<?php print (!isset($_POST["strExpSupervisor"])) ? $valvontakokemus : $_POST["strExpSupervisor"]; ?>"/></td>
                        <td><?php print ($muutokset["supervisor"] <> 0) ? $muutokset["supervisor"] : ""; ?></td>

                    </tr>
<?php
} ?>
<!--                    <tr>
                        <td colspan="3">
                            <p class="info">Voit halutessasi ilmoittaa kokemuksesi eri konetyypeillä.</p>
                        </td>
                    </tr>
<?php
foreach ($konetyypit as $type) {
    $typekokemus = $ak->muunnaMinuutitAjaksi($type["kokemus_aika_mins"]);
    ?>
                        <tr>
                            <td><?php print $type["nimi"]; ?>:</td>
                            <td><input type="text" class="textboxNormal" style="width:80px;" name="strExpPlaneType[<?php print $type["id"]; ?>]" id="strExpPlaneType<?php print $type["id"]; ?>" value="<?php print (!isset($_POST["strExpPlaneType"][$type["id"]])) ? $typekokemus : $_POST["strExpPlaneType"][$type["id"]]; ?>"/></td>
                            <td></td>
                        </tr>
    <?php }
?>
                    <tr>
                        <td colspan="3">
                            <p class="info">Järjestelmän laskemat konetyyppikokemukset</p>
                        </td>
                    </tr>-->
<?php
foreach ($konetyypit as $type) {
    $laskok = $ak->muunnaMinuutitAjaksi($kokemukset["konetyypit"][$type["id"]] + $muutokset["konetyypit"][$type["id"]]);
    $muutos = $ak->muunnaMinuutitAjaksi(abs($muutokset["konetyypit"][$type["id"]]));
    if ($muutokset["konetyypit"][$type["id"]] < 0) $muutos = '-'.$muutos;
    ?>
                        <tr>
                            <td><?php print $type["nimi"]; ?>:</td>
                            <td><input type="text" name="strExpPlaneType[<?php print $type["id"]; ?>]" class="textboxNormal" style="width:80px;" value="<?php print $laskok; ?>"/></td>
                            <td><?php print ($muutokset["konetyypit"][$type["id"]] <> 0) ? $muutos : ""; ?></td>
                        </tr>
    <?php }
?>
                </tbody>
            </table>

            <div class="divSpacing">
                <input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
                <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
            </div>
        </form>

    </div>

</div>

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