<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Uusi kelpuutus lisättiin";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '2') {
    $aMsg[0] = "Kelpuutuksen tiedot päivitettiin";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif ($_GET['PF'] == '3') {
    $aMsg[0] = "Kelpuutus poistettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<?php
?>
<?php require("omattiedot_napit.tpl.php"); ?>

<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
<?php print $aMsg[0] ?>
    </div>

    <h2>Omat kelpuutukset</h2>

    <div class="omattiedot_osio">

<?php
// Lisätään kelpuutusta käyttäjälle tai muokataan tallennettua.
if ($_GET["act"] == 1 || $_GET["act"] == 2) {
    $omakelp = '';
    $kelpuutukset = $ot->haeKelpuutukset();
    $ak = new aikakalu();

    // Muokkaaminen
    if ($_GET["act"] == 2) {
        $omakelp = $ot->haeKayttajanKelpuutukset($_GET["omakelp_id"]);
    }
    ?>
            <script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
            <script type="text/javascript">
                $(function() {
                    $.datepicker.setDefaults($.datepicker.regional["fi"]);
                    $("#dateValidUntil").datepicker();

                    $("#submitOwnAuthorization").click(function(){
                        $("#frmOwnAuthorizations").validate();
                        $("#frmOwnAuthorizations").submit();
                    });
                });
            </script>
            <div class="formSpacing">

                <form id="frmOwnAuthorizations" method="post" action="<?php print $APP->BASEURL; ?>/omattiedot.php?ID=48" >

                    <table id="kelpuutukset_taulukko">
                        <tr>
                            <td colspan="2"><h3><?php echo ($_GET['act'] == 1) ? 'Lisää kelpuutus' : 'Muokkaa kelpuutusta'; ?></h3></td>
                        </tr>
                        <tr>
                            <td><label>Kelpuutus</label></td>
                            <td><select class="textboxNormal required" style="width:350px;height:24px;" name="selAuthorization" id="selAuthorization">
                                    <option value="">-- Valitse kelpuutus --</option>
    <?php
    foreach ($kelpuutukset as $kelp) {
        $xcls = (is_array($omakelp) && $kelp["id"] == $omakelp["kelpuutus_id"]) ? " selected" : "";
        ?>
                                        <option value="<?php print $kelp["id"]; ?>"<?php print $xcls; ?>><?php print $kelp["nimi"]; ?></option>
                                        <?php }
                                    ?>
                                </select></td>
                        </tr>

                        <tr>
                            <td><label>Vanhenemispvm</label></td>
                            <td><input type="text" class="textboxNormal" style="width:350px;" name="dateValidUntil" id="dateValidUntil" value="<?php if (is_array($omakelp)) print $ak->dbDateToFiDate($omakelp["vanhenee"]); ?>"/></td>
                        </tr>

                        <tr>
                            <td colspan="2"><input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1"/>
                                <a href="<?php print $APP->BASEURL; ?>/omattiedot.php?ID=48" class="ui-od-button-with-icon ui-state-default ui-corner-all kelpuutus-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
                                <a href="JavaScript:void(0);" id="submitOwnAuthorization" title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all kelpuutus-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
    <?php if (is_array($omakelp)) { ?>
                                    <input type="hidden" name="ownAuthId" value="<?php print $omakelp["id"]; ?>"/>
                                    <?php }
                                ?>
                            </td>
                        </tr>
                    </table>
                </form>
                <p class="info">Jätä vanhenemispvm -kenttä tyhjäksi, jos kelpoisuus ei vanhene.</p>
            </div>
    <?php
}



/**
 * Jos ei muokata eikä lisätä kelpuutusta
 * l. LISTATAAN KELPUUTUKSET 
 */ else {
    ?>
            <table class="datataulukko">
                <thead>
                    <tr>
                        <th>Kelpuutus</th>
                        <th>Vanhenee</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (!$kayt_kelpt) { ?>
                        <tr>
                            <td colspan="3">Ei kelpuutuksia</td>
                        </tr>
                        <?php
                    } else {
                        $ak = new aikakalu();
                        foreach ($kayt_kelpt as $omakelp) {
                            $kelpuutus = $ot->haeKelpuutukset($omakelp["kelpuutus_id"]);
                            ?>
                            <tr>
                                <td><?php print $kelpuutus["nimi"]; ?></td>
                                <td><?php
                if ($omakelp["vanhenee"] == "0000-00-00") {
                    print "<i>Ei vanhene</i>";
                } else {
                    print $ak->dbDateToFiDate($omakelp["vanhenee"]);
                }
                ?></td>
                                <td>
                                    <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=48&act=2&omakelp_id=' . stripslashes($omakelp['id']); ?>">
                                        <img src="<?php print $APP->BASEURL; ?>/view/images/icon-edit.png" title="Muokkaa" alt="Muokkaa" class="icon"/>
                                    </a>
                                    <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=48&act=3&omakelp_id=' . stripslashes($omakelp['id']); ?>" >
                                        <img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Poista" alt="Poista" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa kelpuutuksesi?');"/>
                                    </a>
                                </td>
                            </tr>                
            <?php
        }
    }
    ?>
                </tbody>
            </table>   
            <a href="<?php print $APP->BASEURL; ?>/omattiedot.php?ID=48&act=1" class="ui-od-button-with-icon ui-state-default ui-corner-all kelpuutus-btn"><span class="ui-icon ui-icon-plus"></span>Lisää kelpuutus</a>
                    <?php 
                    }
                ?>

        

    </div>
</div>