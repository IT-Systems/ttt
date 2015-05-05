<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Vahvistit osallistumisesi tapahtumaan.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
elseif ($_GET['PF'] == '2') {
    $aMsg[0] = "Et vahvistanut osallistumistasi tapahtumaan.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<script type="text/javascript">
$(function() {
    $("#intUserId").change(function() {
        var userId = $(this).val();
        $(".hlotieto").remove();
        $.ajax({
            url: "index.php?ID=77&userId="+userId,
            success: function(data) {
                $("#hlohaku").append(data);
            }
        });
    });
});
</script>

<div class="kp_div">
    <div class="<?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>
<?php
if ($USER->iRole != 3)
{
    // Työvuorojen kirjaus rajataan pois opiskelijoilta
?>
    <div class="etusivu_osio">
        <h3 class="head"><a id="tyovuoro" href="#data1">Työvuoro</a></h3>
        <div class="etusivu_alaosio">
            <div id="data1">
                <?php
                echo $vuoro_aloitettu_msg;
                echo $aMsg;

                if (count($tyovuoro) == 0) {
                    ?>
                <p>
                <form name="frmAloitavuoro" id="frmAloitavuoro" action="<?php print $_SERVER['PHP_SELF'].'?ID=6';?>" method="POST">
                    <div style="float:left; width:150px; padding-top:3px;">
							Aloitusaika:
                        <select name="aloitusaika" class="textboxNormal">
    <?php
                                foreach($aloitusajat as $aika) {
                                    ?>
                            <option value="<?php echo $aika['aika']; ?>" <?php if ($aika['nyt'] != '') {
                                        echo 'selected';
        }?> ><?php echo $aika['aika']; ?></option>
                                    <?php
                                }
                                ?>
                        </select>
                    </div>
                    <div style="float:left; width:200px;">
                        <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
                        <input type="hidden" name="aloitavuoro"/>
                        <a href="javascript:void(0);" onclick="javascript:aloitaVuoro();"title="Submit" style="float:left;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Aloita työvuoro</a>
                    </div>
                </form>
                    <?php
                }
                else {
                    ?>
                <p>
                <form name="frmLopetavuoro" id="frmLopetavuoro" action="<?php print $_SERVER['PHP_SELF'].'?ID=6';?>" method="POST">
                    <div style="float:left; width:150px; padding-top:3px;">
							Lopetusaika:
                        <select name="aloitusaika" class="textboxNormal">
    <?php
                                foreach($aloitusajat as $aika) {
                                    ?>
                            <option value="<?php echo $aika['aika']; ?>" <?php if ($aika['nyt'] != '') {
                                        echo 'selected';
        }?> ><?php echo $aika['aika']; ?></option>
                                    <?php
                                }
                                ?>
                        </select>
                    </div>
                    <div style="float:left; width:200px;">
                        <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
                        <input type="hidden" name="lopetavuoro"/>
                        <a href="javascript:void(0);" onclick="javascript:lopetaVuoro();"title="Submit" style="float:left;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Lopeta työvuoro</a>
                    </div>
                </form>

                    <?php
                }
                ?>
            </div>
        </div>
        <div id="sivudata">
            <table class="datataulukko" id="hlohaku">
                <tr>
                    <th colspan="2">Yhteystietojen pikahaku</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <select class="textboxNormal" name="intUserId" id="intUserId">
                            <option value="0">--Valitse henkilö--</option>
<?php
    foreach ($henkilot as $hlo) print '<option value="'.$hlo["userid"].'">'.$hlo["lastname"].' '.$hlo["firstname"].'</option>';
?>

                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr/>
<?php
}
else { ?>

    <div class="etusivu_osio">
        <br/>
        <div class="etusivu_alaosio">
            <table class="datataulukko" id="hlohaku">
                <tr>
                    <th colspan="2">Yhteystietojen pikahaku</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <select class="textboxNormal" name="intUserId" id="intUserId">
                            <option value="0">--Valitse henkilö--</option>
<?php
    foreach ($henkilot as $hlo) print '<option value="'.$hlo["userid"].'">'.$hlo["lastname"].' '.$hlo["firstname"].'</option>';
?>

                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr/>

<?php
}
// Ilmoitukset vain asianomaisille (kelpuutukset)
if ($ilmoitukset && $USER->ID == 1) {
    $hal = new hallinta();
    $ak = new aikakalu();
    ?>
    <div class="etusivu_osio">
        <h3>Vanhenevat kelpuutukset</h3>
        <table class="datataulukko">
            <thead>
                <tr>
                    <th>Henkilö</th>
                    <th>Kelpuutus</th>
                    <th>Vanhenee</th>
                </tr>
            </thead>
            <tbody>
<?php
    foreach ($ilmoitukset as $ilm) {
        $hlo = $hal->listEachUser($ilm["user_id"]);
        $kelp = $hal->haeKelpuutukset($ilm["kelpuutus_id"]);
        $vanhenee = $ak->dbDateToFiDate($ilm["vanhenee"]); ?>
                <tr>
                    <td><?php print $hlo[0]["firstname"] . " " . $hlo[0]["lastname"]; ?></td>
                    <td><?php print $kelp["nimi"]; ?></td>
                    <td><?php print $vanhenee; ?></td>
                </tr>
<?php
    } ?>
            </tbody>
        </table>
    </div>
    <hr/>
<?php
}
?>
    <div class="etusivu_osio">
        <h3 class="head"><a id="tiedotteet" href="#data2">Tiedotteet</a></h3>
        <div id="data2">
            <?php
            echo $msgTiedotteet;

            if(count($luetut_tiedotteet) != 0) {
                ?>
            <a id="nayta_luetut" href="#luetut">Näytä/piilota luetut</a>
    <?php
            }
            ?>
            <div id="luetut">
                <table>
<?php
                    foreach ($luetut_tiedotteet as $luettu_tiedote) {
                        ?>
                    <tr>
                        <td>
    <?php echo $luettu_tiedote['pvm']; ?>
                        </td>
                        <td>
                            <a class="ltiedote" href="#ltiedote_<?php echo $luettu_tiedote['tiedote_id']; ?>"><?php echo $luettu_tiedote['otsikko']; ?></a>

                            <div style="display:none">
                                <div id="ltiedote_<?php echo $luettu_tiedote['tiedote_id']; ?>">
    <?php
    echo $luettu_tiedote['otsikko'] . " " . $luettu_tiedote['pvm'] ."<p>";
                                        echo $luettu_tiedote['teksti'];
                                        ?>
                                </div>
                            </div>
                        </td>
                    </tr>
    <?php
}
                    ?>
                </table>
            </div>
            <table>
<?php			
foreach ($tiedotteet as $tiedote) {
                    ?>
                <tr>
                    <td>
                    <?php echo $tiedote['pvm']; ?>
                    </td>
                    <td>
                        <a class="tiedote" href="#tiedote_<?php echo $tiedote['tiedote_id']; ?>"><?php echo $tiedote['otsikko']; ?></a>

                        <div style="display:none">
                            <div id="tiedote_<?php echo $tiedote['tiedote_id']; ?>">
    <?php
    echo $tiedote['otsikko'] . " " . $tiedote['pvm'] ."<p>";
    echo $tiedote['teksti'];
                                    $etusivu->merkitseTiedoteLuetuksi($tiedote['tiedote_id']);
                                    ?>
                            </div>
                        </div>
                    </td>
                </tr>
    <?php
}
?>
            </table>
        </div>
    </div>
    <hr/>
    <div class="etusivu_osio">
        <h3 class="head"><a id="ohjeet" href="#data3">Toiminnalliset ohjeet</a></h3>
        <div id="data3">
<?php
echo $msgOhjeet;

            if(count($luetut_ohjeet) != 0) {
                ?>
            <a id="nayta_luetut_ohjeet" href="#luetut_ohjeet">Näytä/piilota luetut</a>
                <?php
            }
            ?>
            <div id="luetut_ohjeet">
                <table>
            <?php
            foreach ($luetut_ohjeet as $luettu_ohje) {
    ?>
                    <tr>
                        <td>
                        <?php echo $luettu_ohje['pvm']; ?>
                        </td>
                        <td>
                            <a class="lohje" href="#lohje_<?php echo $luettu_ohje['tiedote_id']; ?>"><?php echo $luettu_ohje['otsikko']; ?></a>

                            <div style="display:none">
                                <div id="lohje_<?php echo $luettu_ohje['tiedote_id']; ?>">
    <?php
    echo $luettu_ohje['otsikko'] . " " . $luettu_ohje['pvm'] ."<p>";
    echo $luettu_ohje['teksti'];
    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                                        <?php
}
?>
                </table>
            </div>
            <table>
                    <?php
                    foreach ($ohjeet as $ohje) {
    ?>
                <tr>
                    <td>
                    <?php echo $ohje['pvm']; ?>
                    </td>
                    <td>
                        <a class="ohje" href="#ohje_<?php echo $ohje['tiedote_id']; ?>"><?php echo $ohje['otsikko']; ?></a>

                        <div style="display:none">
                            <div id="ohje_<?php echo $ohje['tiedote_id']; ?>">
    <?php
    echo $ohje['otsikko'] . " " . $ohje['pvm'] ."<p>";
    echo $ohje['teksti'];
    $etusivu->merkitseOhjeLuetuksi($ohje['tiedote_id']);
    ?>
                            </div>
                        </div>
                    </td>
                </tr>
                                    <?php
                                }
?>
            </table>
        </div>
    </div>
    <hr/>
    <div class="etusivu_osio">
        <h3 class="head"><a id="varaukset" href="#data4">Varaukset</a></h3>
        <div id="data4">
            <table class="varaukset">
<?php
foreach($kaikki_varaukset as $varaus_tyyppi => $varaukset) {
    foreach($varaukset as $varaus_olio) {
        $varaus = $varaus_olio->haeTiedot();
        ?>
                <tr>
                    <td style="font-weight:bold;">
                        <?php echo $varaus_tyyppi . ': ' . $varaus['alkuaika']; ?>
                    </td>
                </tr>
                <tr>
                    <td id="varaukset_toinenrivi">
                        Lisätiedot: <?php echo $varaus['lisatieto']; ?>
                    </td>
                </tr>
                                <?php
    }
}
?>		
            </table>
        </div>
    </div>
    <hr/>
<?php
if (sizeof($vahvistukset) > 0) { ?>
    <div class="etusivu_osio">
        <h3>Vahvistukset</h3>
        <table class="datataulukko" cellpadding="0" cellspacing="0">
            <tr>
                <th>Otsikko</th>
                <th>Paikka</th>
                <th>Alkaa</th>
                <th>Loppuu</th>
                <th>Osallistun</th>
            </tr>
<?php
    $h = new hallinta();
    foreach ($vahvistukset as $vah) {
        $tila = $h->haeTilat($vah["tila_id"]);
        ?>
            <tr>
                <td><?php print $vah["Subject"]; ?></td>
                <td><?php print $tila["nimi"]; ?></td>
                <td><?php print $h->dbTimeToFiTime($vah["StartTime"]); ?></td>
                <td><?php print $h->dbTimeToFiTime($vah["EndTime"]); ?></td>
                <td>
                    <form action="<?php print $APP->BASEURL; ?>/index.php?ID=42" method="POST">
                        <input type="checkbox" name="vahvistus"<?php if ($vah["vahvistettu"] == 1) print " checked"; ?>>
                        <input type="hidden" name="jqcal_id" value="<?php print $vah["Id"]; ?>"/>
                        <input type="submit" value="Vahvista" class="ui-state-default ui-corner-all input_button"/>
                    </form>
                </td>
            </tr>
<?php
    } ?>
            <tr>
                <td colspan="5">
                    <p class="info">Voit ilmoittaa itsesi tapahtumaan siihen asti, kunnes tapahtuman alkuun on 24 tuntia aikaa.</p>
                </td>
            </tr>
        </table>
    </div>
    <hr/>
<?php
} ?>
</div>
<script type="text/javascript">
    function aloitaVuoro()
    {
        $("#frmAloitavuoro").validate();
        $("#frmAloitavuoro").submit();
    }

    function lopetaVuoro()
    {
        $("#frmLopetavuoro").validate();
        $("#frmLopetavuoro").submit();
    }

    $("#tyovuoro").click(function () {
        $("#data1").toggle("slow");
    });

    $("#tiedotteet").click(function () {
        $("#data2").toggle("slow");
    });

    $("#ohjeet").click(function () {
        $("#data3").toggle("slow");
    });

    $("#varaukset").click(function () {
        $("#data4").toggle("slow");
    });

    $("#nayta_luetut").click(function () {
        $("#luetut").toggle("slow");
    });

    $("#nayta_luetut_ohjeet").click(function () {
        $("#luetut_ohjeet").toggle("slow");
    });

    $(document).ready(function() {
        $(".tiedote").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });

        $(".ltiedote").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });

        $(".ohje").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });

        $(".lohje").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });
    });

    /*$("#naytatiedote").click(function () {

});    */

</script>