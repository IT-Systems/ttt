<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Yhteystiedot päivitetty.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '2') {
    $aMsg[0] = "Tiedot päivitetty.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '4') {
    $aMsg[0] = "Syllabus päivitetty.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '5') {
    $aMsg[0] = "Kuva päivitetty.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '6') {
    $aMsg[0] = "Kuvan tallentamisessa ongelma (vain .jpg ja .png tiedostot hyväksytään)!";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '7') {
    $aMsg[0] = "Kuva poistettu.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '8') {
    $aMsg[0] = "Ei mitään muutettavaa!";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<?php require_once("omattiedot_napit.tpl.php"); ?>
<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
<?php print $aMsg[0] ?>
    </div>



    <div class="omattiedot_osio">
        <h3 class="head"><a id="yhteystiedot" href="#data1">Yhteystiedot</a></h3>
        <div class="omattiedot_alaosio">
            <div id="data1">
                <form name="frmYhteystiedot" id="frmYhteystiedot" action="<?php print $_SERVER['PHP_SELF'] . '?ID=10'; ?>" method="POST">
                    <div style="float:left; width:100%; padding-top:3px;">
                        <p class="info">Tähän syöttämäsi tiedot näkyvät kaikille järjestelmän käyttäjille. Voit jättää haluamasi kohdat tyhjiksi.</p>
                        <table>
                            <tr>
                                <td>Sähköposti</td>
                                <td>
                                    <input class="textboxNormal" type="text" name="sahkoposti" size="50" value="<?php echo $omat_tiedot['email']; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Puhelinnumero</td>
                                <td>
                                    <input class="textboxNormal" type="text" name="puhelin" size="50" value="<?php echo $omat_tiedot['puhelin']; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Katuosoite</td>
                                <td>
                                    <input class="textboxNormal" type="text" name="katuosoite" size="50" value="<?php echo $omat_tiedot['katuosoite']; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Postinumero</td>
                                <td>
                                    <input class="textboxNormal" type="text" name="postinumero" size="50" value="<?php echo $omat_tiedot['postinumero']; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Kaupunki</td>
                                <td>
                                    <input class="textboxNormal" type="text" name="kaupunki" size="50" value="<?php echo $omat_tiedot['kaupunki']; ?>"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="float:left; width:200px;">
                        <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
                        <a href="javascript:void(0);" onclick="javascript:tallYhttiedot();"title="Submit" style="float:left;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span>Tallenna yhteystiedot</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <hr>
    <div class="omattiedot_osio">
        <h3 class="head"><a id="muuttiedot" href="#data2">Muut tiedot</a></h3>
        <div id="data2">
            <form name="frmMuuttiedot" id="frmMuuttiedot" action="<?php print $_SERVER['PHP_SELF'] . '?ID=10'; ?>" method="POST">
                <div style="float:left; width:100%; padding-top:3px;">
                    <table>
                        <tr>
                            <td>Oletuskustannuspaikka</td>
                            <td>
                                <select class="dropdown" name="kustannuspaikka">
                                    <option value="">--Valitse--</option>
<?php
foreach ($kustannuspaikat as $kustannuspaikka) {
    ?>
                                        <option value="<?php echo $kustannuspaikka['id']; ?>" <?php
    if ($omat_tiedot['oletuskustp'] == $kustannuspaikka['id']) {
        echo 'selected';
    }
    ?> ><?php echo $kustannuspaikka['nimi']; ?></option>
                                        <?php
                                    }
                                    ?>										
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Lupakirjan numero</td>
                            <td>
                                <input class="textboxNormal" type="text" name="lupakirja" value="<?php echo $omat_tiedot['lupakirja']; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <input class="checkbox" type="checkbox" name="kouluttajana" <?php
                                    if ($omat_tiedot['kouluttaja'] == '1') {
                                        echo 'checked="yes" ';
                                    }
                                    ?> /> Suostun toimimaan organisaatiossa kouluttajana
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="float:left; width:200px;">
                    <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="2" />
                    <a href="javascript:void(0);" onclick="javascript:tallMuuttiedot();"title="Submit" style="float:left;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span>Tallenna tiedot</a>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="omattiedot_osio">
        <h3 class="head"><a id="kuva" href="#data3">Kuva</a></h3>
        <div id="data3">
            <form name="frmKuva" id="frmKuva" action="<?php print $_SERVER['PHP_SELF'] . '?ID=10'; ?>" method="POST" enctype="multipart/form-data">
                <div style="float:left; width:100%; padding-top:3px;">
                    <table>
<?php if ($omat_tiedot['kuva'] != '' && file_exists("files/userimages/" . $omat_tiedot['kuva'])) { ?>
                            <tr>
                                <td>Kuva</td>
                                <td><img src="files/userimages/<?php print $omat_tiedot['kuva']; ?>"></td>
                            </tr>
                            <tr>
                                <td>Poista</td>
                                <td><input type="checkbox" name="poistakuva" value="1"/></td>
                            </tr>
    <?php }
?>
                        <tr>
                            <td>Kuvatiedosto</td>
                            <td><input class="textboxNormal" type="file" name="kuva" id="kuva"/></td>
                        </tr>
                    </table>
                </div>
                <div style="float:left; width:200px;">
                    <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="3" />
                    <a href="javascript:void(0);" onclick="javascript:tallKuva();"title="Submit" style="float:left;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span>Tallenna kuvatiedot</a>
                </div>
            </form>
        </div>
    </div>
    <!--
        <hr>
        <div class="omattiedot_osio">
            <h3 class="head"><a id="lentosyllabus" href="#data4">Lentosyllabus</a></h3>
            <div id="data4">
                <form name="frmSyllabus" id="frmSyllabus" action="<?php print $_SERVER['PHP_SELF'] . '?ID=10'; ?>" method="POST">
                    <div style="float:left; width:100%; padding-top:3px;">
                        <table>
                            <tr>
                                <td>Seuraamasi lentosyllabus</td>
                                <td>
                                    <select class="dropdown" name="syllabus">
                                        <option value="">Ei syllabusta</option>
<?php
foreach ($syllabukset as $syllabus) {
    ?>
                                            <option value="<?php echo $syllabus['id']; ?>" <?php
    if ($omat_tiedot['syllabus'] == $syllabus['id']) {
        echo 'selected';
    }
    ?> ><?php echo $syllabus['nimi']; ?></option>
        
        <?php
    }
    ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="float:left; width:200px;">
                        <input type="hidden" name="hidEditStatus" id="hidEditStatus" value="4" />
                        <a href="javascript:void(0);" onclick="javascript:tallSyllabus();"title="Submit" style="float:left;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span>Tallenna syllabus</a>
                    </div>
                </form>
    
            </div>
        </div>
    -->
</div>	
<script type="text/javascript">

    function tallYhttiedot()
    {
        $("#frmYhteystiedot").validate();
        $("#frmYhteystiedot").submit();
    }

    function tallMuuttiedot()
    {
        $("#frmMuuttiedot").validate();
        $("#frmMuuttiedot").submit();
    }

    function tallKuva()
    {
        $("#frmKuva").validate();
        $("#frmKuva").submit();
    }

    function tallSyllabus()
    {
        $("#frmSyllabus").validate();
        $("#frmSyllabus").submit();
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
        $("#tiedote").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });

        $("#ltiedote").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });

        $("#ohje").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });

        $("#lohje").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });
        $('input').keyup(function(event) {
            if (event.keyCode == 13) {
                $(this).closest('form').submit();
            } 
        });
    });
    
    /* $("#naytatiedote").click(function () {

});    */

</script>