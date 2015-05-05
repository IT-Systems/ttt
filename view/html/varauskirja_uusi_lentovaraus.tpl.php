<?php
$id = ($_POST['varaus_id'] > 0) ? (int) $_POST['varaus_id'] : '';
$lento = new lentoVaraus($id);

$alku = new DateTime($lento->haeTiedot('alkuaika'));
$loppu = new DateTime($lento->haeTiedot('loppuaika'));


$alkupvm = $alku->format('d.m.Y');
$loppupvm = $loppu->format('d.m.Y');


$alkuaika = $alku->format('H.i');
$loppuaika = $loppu->format('H.i');
?>

<div class="varauslomake">

<form id="<?php echo $lento->ai('uusi-lentovaraus-form'); ?>" action="#" method="POST">
    <input type="hidden" name="varaus_tyyppi" value="lento"/>
<?php
if ($lento->id > 0) {
    echo '<input type="hidden" name="varaus_id" value="' . $lento->id . '">';
}
?>
    <div class="aika_wrapper" prefix="<?php $lento->ai('lento_'); ?>">
        <div class="kone-taulukkorivi">
        <label for="<?php $lento->ai('lento_'); ?>alkuaika"><?php $lento->haeNimi('alkuaika'); ?></label>
        <input id="<?php $lento->ai('lento_'); ?>alkuaika-pvm" name="alkuaika-pvm" class="aika pvm varaus-pvm" value="<?php echo $alkupvm; ?>"><input id="<?php $lento->ai('lento_'); ?>alkuaika-aika" name="alkuaika-aika" size="8" class="aika klo varaus-aika" value="<?php echo $alkuaika; ?>">
        </div>
        <div class="kone-taulukkorivi">
        <label for="<?php $lento->ai('lento_'); ?>loppuaika"><?php $lento->haeNimi('loppuaika'); ?></label>
        <input id="<?php $lento->ai('lento_'); ?>loppuaika-pvm" name="loppuaika-pvm" class="aika pvm varaus-pvm" value="<?php echo $loppupvm; ?>"><input id="<?php $lento->ai('lento_'); ?>loppuaika-aika" size="8" name="loppuaika-aika" class="aika klo varaus-aika" value="<?php echo $loppuaika; ?>">
        </div>
    </div>
   
    <div class="kone-taulukkorivi">
    <label for="<?php $lento->ai('lento_'); ?>kone"><?php $lento->haeNimi('kone'); ?></label>
    <select id="<?php $lento->ai('lento_'); ?>kone" name="kone" class="vaaditaan">
        <option value="0">&nbsp;</option>
<?php
foreach ($lento->haeVaihtoehdot('kone') as $kone) {
    $selected = ($kone['koneetId'] == $lento->haeTiedot('kone')) ? 'selected="selected"' : '';
    echo "<option value=\"{$kone['koneetId']}\" $selected >{$kone['koneetNimi']} ({$kone['konetyypitNimi']})</option>";
}
?>
    </select>
    </div>
    <div class="kone-taulukkorivi">
    <fieldset>
        <legend>Miehistö</legend>
<?php
$miehisto = $lento->haeTiedot('miehisto');
for ($i = 0; $i < 6; $i++) {
    echo '<select id="lento_' . $lento->id . 'miehisto_' . $i . '" name="miehisto_' . $i . '">';
    echo '<option value="0">&nbsp;</option>';
    foreach ($lento->haeVaihtoehdot('miehisto') as $mies) {
        $selected = ($miehisto[$i]['userid'] == $mies['userid']) ? 'selected="selected"' : '';
        echo "<option value=\"{$mies['userid']}\" $selected >{$mies['lastname']} {$mies['firstname']}</option>";
    }
    echo '</select>';
    echo ($i % 2) ? '<br>' : '';
}
?>

    </fieldset>
    </div>
     <div class="kone-taulukkorivi">
    <label for="<?php $lento->ai('lento_'); ?>kustannuspaikka"><?php $lento->haeNimi('kustannuspaikka'); ?></label>
    <select id="<?php $lento->ai('lento_'); ?>kustannuspaikka" name="kustannuspaikka">
        <option value="0">&nbsp;</option>
<?php
foreach ($lento->haeVaihtoehdot('kustannuspaikka') as $kustannuspaikka) {
    $selected = ($kustannuspaikka['id'] == $lento->haeTiedot('kustannuspaikka')) ? 'selected="selected"' : '';
    echo "<option value=\"{$kustannuspaikka['id']}\" $selected > {$kustannuspaikka['nimi']}</option>";
}
?>
    </select>
    </div>
    <div class="kone-taulukkorivi">
    <label for="<?php $lento->ai('lento_'); ?>valvoja"><?php $lento->haeNimi('valvoja'); ?></label>
    <select id="<?php $lento->ai('lento_'); ?>valvoja" name="valvoja">
        <option value="0">&nbsp;</option>
<?php
$valittu_valvoja = $lento->haeTiedot('valvoja');
foreach ($lento->haeVaihtoehdot('valvoja') as $valvoja) {
    $selected = ($valittu_valvoja == $valvoja['userid']) ? 'selected="selected"' : '';
    echo "<option value=\"{$valvoja['userid']}\" $selected >{$valvoja['lastname']} {$valvoja['firstname']}</option>";
}
?>
    </select>
    </div>

    <div class="kone-taulukkorivi">
    <label for="<?php $lento->ai('lento_'); ?>ilma_aika"><?php $lento->haeNimi('ilma_aika'); ?></label>
    <input id="<?php $lento->ai('lento_'); ?>ilma_aika" name="ilma_aika" value="<?php $lento->haeTiedot('ilma_aika'); ?>">
    </div>

    <div class="kone-taulukkorivi">
    <label for="<?php $lento->ai('lento_'); ?>lisatieto"><?php $lento->haeNimi('lisatieto'); ?></label>
    <input id="<?php $lento->ai('lento_'); ?>lisatieto" name="lisatieto" type="text" value="<?php echo $lento->haeTiedot('lisatieto'); ?>">
    </div>
    
    <div class="kone-taulukkorivi">
    <label for="<?php $lento->ai('lento_'); ?>tarkennukset"><?php $lento->haeNimi('tarkennukset'); ?></label>
    <textarea name="<?php $lento->ai('lento_'); ?>tarkennukset" id="lento_tarkennukset"><?php echo $lento->haeTiedot('tarkennukset'); ?></textarea>
    </div>
    
    <div class="kone-taulukkorivi">
    <label for="<?php $lento->ai('lento_'); ?>tekstiviesti"><?php $lento->haeNimi('tekstiviesti'); ?></label>
<?php $checked = ($lento->haeTiedot('tekstiviesti') == 1) ? 'checked="checked"' : ''; ?>
    <input type="checkbox" <?php echo $checked; ?> id="<?php $lento->ai('lento_'); ?>tekstiviesti" name="tekstiviesti" value="1" />
    </div>
</form>
</div>
<div class="lento-buttons varaus-buttons">
    <a href="javascript:void(0);" class="tallenna-lentovaraus ui-od-button-with-icon ui-state-default ui-corner-all" id="<?php $lento->ai('tallenna-lentovaraus'); ?>"><span class="ui-icon ui-icon-disk"></span>Tallenna</a> 
    <a href="javascript:void(0);" class="ui-od-button-with-icon ui-state-default ui-corner-all" id="<?php $lento->ai('peruuta-lentovaraus'); ?>"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
    <?php if ($lento->id > 0 && in_array($USER->iRole, array(1,2))) { ?>        
    <a href="javascript:void(0);" class="poista-lentovaraus ui-od-button-with-icon ui-state-default ui-corner-all" onClick="javascript:if(confirm('Oletko varma, että haluat poistaa varauksen?')) poistaVaraus();"><span class="ui-icon ui-icon-trash"></span>Poista</a>
    <?php
    } ?>
</div>
<script type="text/javascript">
<?php if ($lento->id == 0) { ?>
            $('.lento-buttons').off('click', '.tallenna-lentovaraus'); 
<?php } ?>
        $('.lento-buttons').on('click', '.tallenna-lentovaraus', function() {
            var formData = $('<?php $lento->ai('#uusi-lentovaraus-form'); ?>').serialize();
            var url = 'varauskirja.php?ID=57';
            $.post(url, formData, function(data) {
                tallennusFancyboxHandler(data, $('<?php $lento->ai('#uusi-lentovaraus-form'); ?>'));
            });
        });
        
        function poistaVaraus () {
            window.location.href = 'varauskirja.php?ID=57&poista=1&varaus_id='+($("input[name='varaus_id']").val());
        }
        
        $('<?php $lento->ai('#peruuta-lentovaraus'); ?>').click(function() {
            element = $('<?php $lento->ai('#uusi-lentovaraus-form'); ?>');
            $.fancybox.close();
            element.find('input, select, textarea').val('');
            element.find('.aika-error').removeClass('aika-error');
            element.find('.ui-state-error').remove();
        });
<?php
if ($lento->id > 0) {
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