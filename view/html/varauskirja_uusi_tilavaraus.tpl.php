<?php
$id = ($_POST['varaus_id'] > 0) ? (int) $_POST['varaus_id'] : '';
$tila = new tilaVaraus($id);

$alku = new DateTime($tila->haeTiedot('alkuaika'));
$loppu = new DateTime($tila->haeTiedot('loppuaika'));


$alkupvm = $alku->format('d.m.Y');
$loppupvm = $loppu->format('d.m.Y');


$alkuaika = $alku->format('H.i');
$loppuaika = $loppu->format('H.i');
?>
<div class="varauslomake">
    <form class="uusi-tilavaraus-form" id="uusi-tilavaraus-form<?php echo $tila->id; ?>" action="#" method="POST">
        <input type="hidden" name="varaus_tyyppi" value="tila"/>
        <?php
        if ($tila->id > 0) {
            echo '<input type="hidden" name="varaus_id" value="' . $tila->id . '">';
        }
        ?>
        <div class="aika_wrapper" prefix="<?php $tila->ai('tila_'); ?>">
            <div class="taulukkorivi">
                <label for="<?php $tila->ai('tila_'); ?>alkuaika-pvm"><?php $tila->haeNimi('alkuaika'); ?></label>
                <input id="<?php $tila->ai('tila_'); ?>alkuaika-pvm" value="<?php echo $alkupvm; ?>" name="alkuaika-pvm" class="aika pvm varaus-pvm"><input name="alkuaika-aika" value="<?php echo $alkuaika; ?>" id="<?php $tila->ai('tila_'); ?>alkuaika-aika" size="8" class="aika klo varaus-aika">
            </div>
            <div class="taulukkorivi">
                <label for="<?php $tila->ai('tila_'); ?>loppuaika-pvm"><?php $tila->haeNimi('loppuaika'); ?></label>
                <input id="<?php $tila->ai('tila_'); ?>loppuaika-pvm" value="<?php echo $loppupvm; ?>" name="loppuaika-pvm" class="aika pvm varaus-pvm"><input id="<?php $tila->ai('tila_'); ?>loppuaika-aika" value="<?php echo $loppuaika; ?>" name="loppuaika-aika" size="8" class="aika klo varaus-aika">
            </div>
        </div>

        <div class="taulukkorivi">
            <label for="<?php $tila->ai('tila_'); ?>tila"><?php $tila->haeNimi('tila'); ?></label>
            <select id="<?php $tila->ai('tila_'); ?>tila" name="tila" class="vaaditaan">
                <option value="0">&nbsp;</option>
                <?php
                foreach ($tila->haeVaihtoehdot('tila') as $tilav) {
                    $selected = ($tilav['id'] == $tila->haeTiedot('tila')) ? 'selected="selected"' : '';
                    echo "<option value=\"{$tilav['id']}\" $selected >{$tilav['nimi']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="taulukkorivi">
            <fieldset>
                <legend><?php $tila->haeNimi('vastuuhenkilot'); ?></legend>
                <?php
                $vastuuhenkilot = $tila->haeTiedot('vastuuhenkilot');
                for ($i = 0; $i < 6; $i++) {
                    echo "<select id=\"tila_{$tila->id}vastuuhenkilot_$i\" name=\"vastuuhenkilo_$i\">";
                    echo '<option value="0">&nbsp;</option>';
                    foreach ($tila->haeVaihtoehdot('vastuuhenkilot') as $henkilo) {
                        $selected = ($vastuuhenkilot[$i]['userid'] == $henkilo['userid']) ? 'selected="selected"' : '';
                        echo "<option value=\"{$henkilo['userid']}\" $selected >{$henkilo['lastname']} {$henkilo['firstname']}</option>";
                    }
                    echo "</select> ";
                    echo ($i % 2) ? "<br/>" : "";
                }
                ?>
            </fieldset>
        </div>
        <div class="taulukkorivi">
            <fieldset id="<?php $tila->ai('osallistujat'); ?>">
                <legend><?php $tila->haeNimi('osallistujat'); ?></legend>
                <?php
                $osallistujat = $tila->haeTiedot('osallistujat');
                $i = 0;
                if (count($osallistujat) > 0) {
                    echo '<a class=lisaa_osallistuja ui-od-button-with-icon ui-state-default ui-corner-all" varaus_id="' . $tila->id . '"><span class="ui-icon ui-icon-plusthick"></span></a><br><br>';
                    foreach ($osallistujat as $osallistuja) {
                        echo "<select id=\"{$tila->id}osallistujat_$i\" class=\"osallistujat\" index=\"$i\">";
                        echo '<option value="0">&nbsp;</option>';
                        foreach ($tila->haeVaihtoehdot('osallistujat') as $henkilo) {
                            $selected = ($osallistuja['userid'] == $henkilo['userid']) ? 'selected="selected"' : '';
                            echo "<option value=\"{$henkilo['userid']}\" $selected >{$henkilo['lastname']} {$henkilo['firstname']}</option>";
                        }
                        echo "</select>";

                        if (($i > 0) && ($i % 2)) {
                            echo '<br>';
                        }
                        $i++;
                    }
                } else {
                    ?>
                    <a type="button" class="lisaa_osallistuja ui-od-button-with-icon ui-state-default ui-corner-all" varaus_id="<?php $tila->ai(''); ?>"><span class="ui-icon ui-icon-plus"></span>Enemmän osallistujia</a><br><br>
                    <select id="<?php $tila->ai('osallistujat'); ?>_0" class="osallistujat" index="0">
                        <option value="0">&nbsp;</option>
                        <?php
                        foreach ($tila->haeVaihtoehdot('osallistujat') as $henkilo) {
                            echo "<option value=\"{$henkilo['userid']}\">{$henkilo['lastname']} {$henkilo['firstname']}</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>

                <input type="hidden" name="osallistujat-string" id="<?php $tila->ai('osallistujat-string'); ?>">
            </fieldset>
        </div>

        <div class="taulukkorivi-wide">
            <label for="tila_kustannuspaikka"><?php $tila->haeNimi('kustannuspaikka'); ?></label>
            <select id="<?php $tila->ai('tila_'); ?>kustannuspaikka" name="kustannuspaikka">
                <option value="0">&nbsp;</option>
                <?php
                foreach ($tila->haeVaihtoehdot('kustannuspaikka') as $kustannuspaikka) {
                    $selected = ($tila->haeTiedot('kustannuspaikka') == $kustannuspaikka['id']) ? 'selected="selected"' : '';
                    echo "<option value=\"{$kustannuspaikka['id']}\" $selected >{$kustannuspaikka['nimi']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="taulukkorivi-wide">
            <label for="tila_lisatieto"><?php $tila->haeNimi('lisatieto'); ?></label>
            <input type="text" id="<?php $tila->ai('tila_'); ?>lisatieto" name="lisatieto" value="<?php echo $tila->haeTiedot('lisatieto'); ?>">
        </div>

        <div class="taulukkorivi-wide">
            <label for="tila_tarkennukset"><?php $tila->haeNimi('tarkennukset'); ?></label>
            <textarea id="<?php $tila->ai('tila_'); ?>tarkennukset" name="tarkennukset"><?php echo $tila->haeTiedot('tarkennukset'); ?></textarea>
        </div>

        <div class="taulukkorivi-wide">
            <label for="tila_tekstiviesti"><?php $tila->haeNimi('tekstiviesti'); ?></label>
            <?php
            $checked = ($tila->haeTiedot('tekstiviesti') == 1) ? 'checked="checked"' : '';
            ?>
            <input type="checkbox" <?php echo $checked; ?> value="1" id="<?php $tila->ai('tila_'); ?>tekstiviesti" name="tekstiviesti">
        </div> 

    </form>
</div>

<div class="tila-buttons varaus-buttons">
    <a href="javascript:void(0);" class="tallenna-tilavaraus ui-od-button-with-icon ui-state-default ui-corner-all" id="<?php $tila->ai('tallenna-tilavaraus'); ?>"><span class="ui-icon ui-icon-disk"></span>Tallenna</a> <a href="javascript:void(0);" class="ui-od-button-with-icon ui-state-default ui-corner-all" id="<?php $tila->ai('peruuta-tilavaraus'); ?>"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>
</div>

<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/its.tarkistaAjat.js"></script>

<script type="text/javascript">
<?php if ($tila->id == 0) { ?>
        $('.tila-buttons').off('click', '.tallenna-tilavaraus'); 
        $('.uusi-tilavaraus-form').off('click', '.lisaa_osallistuja');
<?php } ?>
    $('.tila-buttons').on('click', '.tallenna-tilavaraus', function() {
        var osallistujat = [];
        $('#uusi-tilavaraus-form<?php echo $tila->id; ?> .osallistujat').each(function() {
            osallistujat.push($(this).val());
        });
        var osallistujat_string = osallistujat.join(',');
        $('#osallistujat-string<?php echo $tila->id; ?>').val(osallistujat_string);
        var formData = $('#uusi-tilavaraus-form<?php echo $tila->id; ?>').serialize();
        var url = 'varauskirja.php?ID=57';
        $.post(url, formData, function(data) {
            tallennusFancyboxHandler(data, $('#uusi-tilavaraus-form<?php echo $tila->id; ?>'));
        });
    });
    
    /**
     * Lisää osallistuja-dropdownin tilavarauslomakkeeseen
     */
    $('.uusi-tilavaraus-form').on('click', '.lisaa_osallistuja', function() {
        var varausId = $(this).attr('varaus_id');
        var osallistujatElement = "#osallistujat" + varausId;
        var uusiOsallistujat = $(osallistujatElement + " select:last").clone();

        var uusiIndex = uusiOsallistujat.attr("index");
        uusiIndex++;

        uusiOsallistujat.attr({
            id:     "osallistujat" + varausId + "_" + uusiIndex,
            index:  uusiIndex
        });
        uusiOsallistujat.children().removeAttr("selected");

        if (((uusiIndex + 1) % 2) > 0) {
            $("<br/>").appendTo(osallistujatElement);
        }
        uusiOsallistujat.appendTo(osallistujatElement);

    }); 
    
    $('<?php $tila->ai('#peruuta-tilavaraus'); ?>').click(function() {
        element = $('<?php $tila->ai('#uusi-tilavaraus-form'); ?>');
        $.fancybox.close();
        element.find('input, select, textarea').val('');
        element.find('.aika-error').removeClass('aika-error');
        element.find('.ui-state-error').remove();
        // TODO: tyhjennä lomake
    });
<?php
if ($tila->id > 0) {
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