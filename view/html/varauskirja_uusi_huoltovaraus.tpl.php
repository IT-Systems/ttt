<?php
$id = ($_POST['varaus_id'] > 0) ? (int) $_POST['varaus_id'] : '';
$huolto = new huoltoVaraus($id);

$alku = new DateTime($huolto->haeTiedot('alkuaika'));
$loppu = new DateTime($huolto->haeTiedot('loppuaika'));


$alkupvm = $alku->format('d.m.Y');
$loppupvm = $loppu->format('d.m.Y');


$alkuaika = $alku->format('H.i');
$loppuaika = $loppu->format('H.i');
?>

<div class="varauslomake">
    <form id="<?php $huolto->ai('uusi-huoltovaraus-form'); ?>" action="#" method="POST">
        <input type="hidden" name="varaus_tyyppi" value="huolto"/>
<?php
if ($huolto->id > 0) {
    echo '<input type="hidden" name="varaus_id" value="' . $huolto->id . '">';
}
?>
        <div class="aika_wrapper" prefix="<?php $huolto->ai('huolto_'); ?>">
            <div class="kone-taulukkorivi">
                <label for="<?php $huolto->ai('huolto_'); ?>alkuaika"><?php $huolto->haeNimi('alkuaika'); ?></label>
                <input id="<?php $huolto->ai('huolto_'); ?>alkuaika-pvm" name="alkuaika-pvm" class="aika pvm varaus-pvm" value="<?php echo $alkupvm; ?>"><input id="<?php $huolto->ai('huolto_'); ?>alkuaika-aika" size="8" name="alkuaika-aika" class="aika klo varaus-aika" value="<?php echo $alkuaika; ?>">
            </div>

            <div class="kone-taulukkorivi">
                <label for="<?php $huolto->ai('huolto_'); ?>loppuaika"><?php $huolto->haeNimi('loppuaika'); ?></label>
                <input id="<?php $huolto->ai('huolto_'); ?>loppuaika-pvm" name="loppuaika-pvm" class="aika pvm varaus-pvm" value="<?php echo $loppupvm; ?>"><input id="<?php $huolto->ai('huolto_'); ?>loppuaika-aika" size="8" name="loppuaika-aika" class="aika klo varaus-aika" value="<?php echo $loppuaika; ?>">
            </div>
        </div>

        <div class="taulukkorivi">
            <label for="<?php $huolto->ai('huolto_'); ?>kone"><?php $huolto->haeNimi('kone'); ?></label>
            <select id="<?php $huolto->ai('huolto_'); ?>kone" name="kone" class="vaaditaan">
                <option value="0">&nbsp;</option>
<?php
foreach ($huolto->haeVaihtoehdot('kone') as $kone) {
    $selected = ($kone['koneetId'] == $huolto->haeTiedot('kone')) ? 'selected="selected"' : '';
    echo "<option value=\"{$kone['koneetId']}\" $selected >{$kone['koneetNimi']} ({$kone['konetyypitNimi']})</option>";
}
?>
            </select>
        </div>

        <div class="taulukkorivi">
            <label for="<?php $huolto->ai('huolto_'); ?>_vastuuhenkilo"><?php $huolto->haeNimi('vastuuhenkilo'); ?></label>
            <select id="<?php $huolto->ai('huolto_'); ?>vastuuhenkilo" name="vastuuhenkilo">
                <option value="0">&nbsp;</option>
<?php
$valittu_valvoja = $huolto->haeTiedot('vastuuhenkilo');
foreach ($huolto->haeVaihtoehdot('vastuuhenkilo') as $valvoja) {
    $selected = ($valittu_valvoja == $valvoja['userid']) ? 'selected="selected"' : '';
    echo "<option value=\"{$valvoja['userid']}\" $selected >{$valvoja['lastname']} {$valvoja['firstname']}</option>";
}
?>
            </select>
        </div>

        <div class="kone-taulukkorivi">
            <label for="<?php $huolto->ai('huolto_'); ?>lisatieto"><?php $huolto->haeNimi('lisatieto'); ?></label>
            <input id="<?php $huolto->ai('huolto_'); ?>lisatieto" name="lisatieto" type="text" value="<?php echo $huolto->haeTiedot('lisatieto'); ?>">
        </div>

        <div class="kone-taulukkorivi">
            <label for="<?php $huolto->ai('huolto_'); ?>nimike"><?php $huolto->haeNimi('nimike'); ?></label>
            <select id="<?php $huolto->ai('huolto_'); ?>nimike" name="nimike">
                <option value="0">&nbsp;</option>
<?php
$valittu_nimike = $huolto->haeTiedot('nimike');
foreach ($huolto->haeVaihtoehdot('nimike') as $nimike) {
    $selected = ($valittu_nimike == $nimike['id']) ? 'selected="selected"' : '';
    echo "<option value=\"{$nimike['id']}\" $selected >{$nimike['nimike']} ({$nimike['koodi']})</option>";
}
?>
            </select>
        </div>
    </form>
</div>
<div class="huolto-buttons varaus-buttons">
    <a href="javascript:void(0);" class="tallenna-huoltovaraus ui-od-button-with-icon ui-state-default ui-corner-all" id="<?php $huolto->ai('tallenna-huoltovaraus'); ?>"><span class="ui-icon ui-icon-disk"></span>Tallenna</a> <a href="javascript:void(0);" class="ui-od-button-with-icon ui-state-default ui-corner-all" id="<?php $huolto->ai('peruuta-huoltovaraus'); ?>"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
</div>

<script type="text/javascript">
<?php if ($huolto->id == 0) { ?>
            $('.huolto-buttons').off('click', '.tallenna-huoltovaraus'); 
<?php } ?>
        $('.huolto-buttons').on('click', '.tallenna-huoltovaraus', function() {
            var formData = $('<?php $huolto->ai('#uusi-huoltovaraus-form'); ?>').serialize();
            var url = 'varauskirja.php?ID=57';
            $.post(url, formData, function(data) {
                tallennusFancyboxHandler(data, $('<?php $huolto->ai('#uusi-huoltovaraus-form'); ?>'));
            });
        });
    
        $('#peruuta-huoltovaraus<?php echo $huolto->id; ?>').click(function() {
            element = $('<?php $huolto->ai('#uusi-huoltovaraus-form'); ?>');
            $.fancybox.close();
            element.find('input, select, textarea').val('');
            element.find('.aika-error').removeClass('aika-error');
            element.find('.ui-state-error').remove();
        });
<?php
if ($huolto->id > 0) {
    ?>
            $.datepicker.setDefaults($.datepicker.regional["fi"]);
            $(".klo").timePicker({
                startTime: "00.00", 
                endTime: "23.45", 
                separator: '.', // tämä pitää olla määriteltynä, jos on asetettu startTime tai endTime
                step: 15
            }); 
            $(".pvm").datepicker();
            $(".aika_wrapper .aika").change(function() { tarkistaAjat($(this), false); });
<?php } ?>
</script>
