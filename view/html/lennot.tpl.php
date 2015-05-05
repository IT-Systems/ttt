<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Lento poistettu.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>

<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#showDate").datepicker();

        $("#frmSubmitButton").click(function(){
            $("#dateChangeFrm").validate();
            $("#dateChangeFrm").submit();
        });
        $('#showDate').change(function() { 
            $("#dateChangeFrm").validate(); 
            $('#dateChangeFrm').submit(); 
        });
    });
</script>

<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">

    <div class="lennot">
        <table class="tbl_lennot_header">
            <tr>
                <td colspan="5">
                    <div class="ilmoitus <?php print $aMsg[1]; ?>">
                        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
                        <?php print $aMsg[0] ?>
                    </div>
                    <h2>Omat lennot</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <form action="lennot.php?ID=13&s=1" method="POST">
                        <input type="hidden" name="paiva" value="<?php echo $paiva; ?>"/>
                        <input class="no_border" type="image"  src="<?php print $APP->BASEURL; ?>/view/images/icon_previous.png" value="Edellinen" title="Edellinen"/>
                    </form>
                </td>
                <td>
                    <h3 class="tbl_lennot_head">Omat lennot <?php echo $paiva_print; ?></h3>
                </td>
                <td >
                    <form action="lennot.php?ID=13&s=2" method="POST">
                        <input type="hidden" name="paiva" value="<?php echo $paiva; ?>"/>
                        <input class="no_border" type="image" src="<?php print $APP->BASEURL; ?>/view/images/icon_next.png" value="Seuraava" title="Seuraava"/>
                    </form>
                </td>
                <td>
                    <form action="<?php print $APP->BASEURL; ?>/lennot.php?ID=13" id="dateChangeFrm" method="POST">
                        <input type="text" class="textboxNormal required" name="showDate" id="showDate"/>
                    </form>
                    <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Siirry päivään</a>
                </td>
                <td>
                    <?php if (sizeof($lennot) > 0) { ?>
                        <a href="<?php print $APP->BASEURL; ?>/lennot.php?ID=13&print&date=<?php print $paiva; ?>" style="float:left;margin:0px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Tulosta</a>
                    <?php }
                    ?>
                </td>
            </tr>
            </tr>
        </table>
        <div class="tiedot_iso_osio">
            <div class="scrollContent">
                <table class="datataulukko">
                    <tr>
                        <th>Päivä</th>
                        <th>Ilma-alus</th>
                        <th>PIC</th>
                        <th>COP</th>
                        <th>Muu</th>
                        <th>Matkustajia</th>
                        <th>Lähtöpaikka</th>
                        <th>Laskupaikka</th>
                        <th>Offblock</th>
                        <th>Lähtöaika</th>
                        <th>Laskuaika</th>
                        <th>Onblock</th>
                        <th>Ilma-aika</th>
                        <th>Block-aika</th>
                        <th>Laskuja</th>
                        <th>Päivällä</th>
                        <th>Yöllä</th>
                        <th>Laatu</th>
                        <th>Simulaattori</th>
                        <th>Huomautus</th>
                        <th>Toiminnot</th>
                    </tr>
                    <?php
                    if (sizeof($lennot) > 0) {
                        $ak = new aikakalu();
                        foreach ($lennot as $lento) {
                            $kone = $len->haeIlmaAlus($lento["kone_id"]);
                            if (!$kone)
                                $kone["nimi"] = "Poistunut";
                            $laatu = $len->haeLennonToiminnanLaadut($lento["toim_tyyppi_id"]);
                            if (!$laatu)
                                $laatu["nimi"] = "Poistunut";

                            $paallikko = $len->haeHenkilot($lento["paallikko_user_id"]);
                            if (!$paallikko) {
                                $paallikko["nimi"] = "Poistunut";
                            } else {
                                $paallikko["nimi"] = $paallikko["firstname"] . " " . $paallikko["lastname"];
                            }

                            $peramies = $len->haeHenkilot($lento["peramies_user_id"]);
                            if (!$peramies) {
                                $peramies["nimi"] = "Poistunut";
                            } else {
                                $peramies["nimi"] = $peramies["firstname"] . " " . $peramies["lastname"];
                            }

                            $muujasen = $len->haeHenkilot($lento["muu_jasen_user_id"]);
                            if (!$muujasen) {
                                $muujasen["nimi"] = "Poistunut";
                            } else {
                                $muujasen["nimi"] = $muujasen["firstname"] . " " . $muujasen["lastname"];
                            }
                            ?>
                            <tr>
                                <td><?php print $ak->dbDateToFiDate($lento["alkamispaiva"]); ?></td>
                                <td><?php print $kone["nimi"]; ?></td>
                                <td><?php print $paallikko["nimi"]; ?></td>
                                <td><?php print $peramies["nimi"]; ?></td>
                                <td><?php print $muujasen["nimi"]; ?></td>
                                <td><?php print $lento["matkustajia"]; ?></td>
                                <td><?php print $lento["lahtopaikka"]; ?></td>
                                <td><?php print $lento["maarapaikka"]; ?></td>
                                <td><?php print substr($lento["off_block_aika"], 0, -3); ?></td>
                                <td><?php print substr($lento["lahtoaika"], 0, -3); ?></td>
                                <td><?php print substr($lento["saapumisaika"], 0, -3); ?></td>
                                <td><?php print substr($lento["on_block_aika"], 0, -3); ?></td>
                                <td><?php print substr($lento["ilma_aika"], 0, -3); ?></td>
                                <td><?php print substr($lento["lentoaika"], 0, -3); ?></td>
                                <td><?php print $lento["laskeutumisia_paiva"] + $lento["laskeutumisia_yo"]; ?></td>
                                <td><?php print $lento["laskeutumisia_paiva"]; ?></td>
                                <td><?php print $lento["laskeutumisia_yo"]; ?></td>
                                <td><?php print $laatu["nimi"]; ?></td>
                                <td></td>
                                <td><?php print $lento["huomautukset"]; ?></td>
                                <td>
                                    <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=52&lento_id=' . stripslashes($lento['id']); ?>">
                                        <img src="<?php print $APP->BASEURL; ?>/view/images/icon-edit.png" title="Muokkaa" alt="Muokkaa" class="icon"/>
                                    </a>
                                    <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=51&lento_id=' . stripslashes($lento['id']); ?>" style="margin-left: 10px;">
                                        <img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Poista" alt="Poista" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa tämän lennon?');"/>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="21">Ei lentoja</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>$(".scrollContent").scrollTop(3000);</script>
    </div>
</div>
<div id="qwe">

</div>

<script type="text/javascript">
    function naytaViikko()
    {
        $("#frmYhteystiedot").validate();
        $("#frmYhteystiedot").submit();
    }

    /* $("#naytatiedote").click(function () {		
	
});    */

</script>