<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Tekstiviesti lähetettiin {$_GET["SCT"]} käyttäjälle.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} elseif (!isset($aMsg)) {
    $sMsg[0] = "";
    $aMsg[1] = "";
    $aMsg[2] = "";
}
?>

<script type="text/javascript">
    function textCounter(field,cntfield,maxlimit)
    {
        if (field.value.length > maxlimit)
            field.value = field.value.substring(0, maxlimit);
        else
            cntfield.value = maxlimit - field.value.length;
    }

    $(function() {
        $("#haku").autocomplete({
            source:"viestit.php?ID=37",
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
            var htmladd = '<input type="hidden" name="receiver[0]" value="<?php print $sUser["userid"]; ?>"/><?php print $sUser["firstname"] . " " . $sUser["lastname"] . ": " . $sUser["puhelin"]; ?></br>';
            $(htmladd).appendTo('#vastaanottajat');
            $('#vastaanottajat_ovat_oikealla').show();
    <?php
}
?>
    });
</script>
<?php
$backurl = $APP->BASEURL . "/viestit.php?ID=31";
if (!empty($_GET["FID"]))
    $backurl.= "&FID=" . $_GET["FID"];
?>




<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
<?php print $aMsg[0] ?>
    </div>

    <h2><?php print $APP->PAGEVARS["HEADERTEXT"]; ?></h2>

    <div id="tekstiviestiwrapper">
        <div class="tekstiviestilahetys">
            <h3><?php print $APP->PAGEVARS["HEADERTEXT"]; ?></h3>

            <form name="frmSms" id="frmSms" action="<?php print $APP->BASEURL; ?>/viestit.php?ID=36" method="POST">

                <table id="tbl_sms" class="lomake_taulukko" cellpadding="0" cellspacing="0">
                    <tr>
                        <th valign="top" class="labeled">Vastaanottaja(t):</th>
                        <td><input type="text" id="haku" name="haku" value="" class="textboxNormal"/><span id="vastaanottajat_ovat_oikealla" class="ui-icon_dib ui-icon-circle-arrow-e"></span></td>
                        <td rowspan="3" id="vastaanottajat"></td>
                    </tr>

                    <tr>
                        <th colspan="2" class="labeled">Sisältö:</th>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <textarea name="smsTxt" class="required" wrap="physical" cols="37" rows="4"
                                      onKeyDown="textCounter(document.frmSms.smsTxt,document.frmSms.strlen,160)"
                                      onKeyUp="textCounter(document.frmSms.smsTxt,document.frmSms.strlen,160)"><?php print $_POST["smstxt"]; ?></textarea>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="3">
                            <input type="hidden" name="hidSmsSend" value="1"/>
                            <a href="javascript:void(0);" onclick="javascript:sendSms();" title="Submit" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn">
                                <span class="ui-icon ui-icon-mail-closed"></span>Lähetä
                            </a>
                            <a class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn" style="font-weight:bold;" href="<?php print $backurl; ?>">
                                <span class="ui-icon ui-icon-cancel"></span>Peruuta
                            </a>
                            <div id="sms_laskuri"><input readonly type="text" name="strlen" size="2" maxlength="3" value="160"> merkkiä jäljellä</div>
                        </td>
                    </tr>
                </table>
            </form>

            <p id="info">Syötä vastaanottaja(t) -kenttään osa käyttäjän nimestä, niin ohjelma ehdottaa käyttäjiä.</p>
        </div>

    </div>
    <div class="viimeisimmatlahetetytsmst">
        <h3>Viimeisimmät lähettämäsi tekstiviestit</h3>
        <table id="tbl_sentsmss">
            <thead>
                <tr>
                    <th>Viesti</th>
                    <th>Lähetetty</th>
                </tr>
            </thead>
<?php
if (sizeof($tekstarit) > 0) {
    ?>
                <tbody>
                <?php
                $h = new hallinta();
                foreach ($tekstarit as $sms) {
                    $smsratio = 0;
                    if (strlen($sms["sent_ids"]) > 0) {
                        $lahetetyt = explode(",", $sms["sent_ids"]);
                        $smsratio = sizeof($lahetetyt);
                    }
                    $vastaanottajat = explode(",", $sms["receiver_ids"]);
                    $smsratio.= "/" . sizeof($vastaanottajat);
                    ?>
                        <tr>
                            <td width="350"><?php print $sms["message"]; ?></td>
                            <td><?php print $h->dbTimeToFiTime($sms["sent_time"]) . "<br/>(" . $smsratio . " vastaanottajalle)"; ?></td>
                        </tr>
        <?php }
    ?>
                </tbody>
                    <?php }
                ?>
        </table>
    </div>
</div>

<script type="text/javascript">
    function sendSms()
    {
        $("#frmSms").validate();
        $("#frmSms").submit();
    }
</script>