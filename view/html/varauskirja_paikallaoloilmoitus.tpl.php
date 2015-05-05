<?php
$on_admin = ($USER->iRole == 1) ? true : false;

$alku = new DateTime($paikallaolo->haeTiedot('alkuaika'));
$loppu = new DateTime($paikallaolo->haeTiedot('loppuaika'));


$alkupvm = $alku->format('d.m.Y');
$loppupvm = $loppu->format('d.m.Y');


$alkuaika = $alku->format('H.i');
$loppuaika = $loppu->format('H.i');

function ilmoitusrivi($ilmoitus, $id, $vanhat) {
    global $paikallaolo, $on_admin, $APP;
    $saatavuudet = $paikallaolo->haeVaihtoehdot('saatavuus');
    $kohteet = '';
    if ($ilmoitus['koskee_lentoja'] == 1) {
        $kohteet .= '<img class="paikallaoloilmoitus_icon" src="' . $APP->BASEURL . '/view/images/icon_plane.png" alt="ilmoitus koskee lentoja"/> ';
    }
    if ($ilmoitus['koskee_teoriaopetusta'] == 1) {
        $kohteet .= '<img class="paikallaoloilmoitus_icon" src="' . $APP->BASEURL . '/view/images/icon_ground.png" alt="ilmoitus koskee teoriaopetusta"/>';
    }
    $alku = strtotime($ilmoitus['alkuaika']);
    $loppu = strtotime($ilmoitus['loppuaika']);
    if (date('dmY', $alku) != date('dmY', $loppu)) {
        $alkuaika = date('j.n.Y H:i', $alku);
        $loppuaika = date('j.n.Y H:i', $loppu);
        $aikarivi = "<td>$alkuaika</td><td>$loppuaika</td>";
    } else {
        $aikarivi = '<td colspan="2">' . date('j.n.Y H:i', $alku) . '-' . date('H:i', $loppu);
    }
    ?>
    <tr class="saatavuus_<?php echo $ilmoitus['saatavuus']; ?>">

        <td><?php echo $kohteet; ?></td>
        <?php if ($on_admin) { ?>
            <td><?php echo $ilmoitus['userid']['username']; ?></td>
            <td><?php echo $ilmoitus['userid']['lastname'] . ', ' . $ilmoitus['userid']['firstname']; ?></td>
        <?php } ?>
        <?php echo $aikarivi; ?>
        <td><?php echo $saatavuudet[$ilmoitus['saatavuus']]; ?></td>
        <td><?php echo $ilmoitus['lisatieto']; ?></td>
        <td><a href="javascript:void(0);" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only paikallaoloilmoitus_muokkaa_btn" poid="<?php echo $id; ?>" role="button" aria-disabled="false" title="Muokkaa"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                <span class="ui-button-text">Muokkaa</span></a>&nbsp;<a href="javascript:void(0);" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only paikallaoloilmoitus_poista" poid="<?php echo $id; ?>" role="button" aria-disabled="false" title="Poista"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                <span class="ui-button-text">Poista</span></a></td>
    </tr>
    <?php
}
?>

<style type="text/css">
    #paikallaoloilmoitus_taulukko {
        width: auto;
        max-width: <?php echo ($on_admin) ? '100%' : '860px'; ?>;
    }
</style>

<form id="paikallaoloilmoitus_muokkaa_form" method="POST" action="varauskirja.php?ID=73" class="hidden">
    <input id="paikallaoloilmoitus_muokkaa_poid" type="hidden" name="poid" value="0" />
</form>
<div class="kp_div">
    <div class="paikallaolo_osio">
        <h2>
            <?php
            echo ($muokataan_paikallaoloilmoitusta) ? 'Muokkaa ilmoitusta' : 'Lisää uusi ilmoitus';
            ?>
        </h2>
        <form id="paikallaoloilmoitus_form">
            <input type="hidden" name="varaus_tyyppi" value="paikallaoloilmoitus">
            <input type="hidden" name="varaus_id" value="<?php echo $paikallaolo->id; ?>">

            <table class="lomake_taulukko">

                <?php if ($USER->iRole == 1) { ?>
                    <tr class="tr-end">
                        <td><label for="henkilo"><?php echo $paikallaolo->haeNimi('userid'); ?></label></td>
                        <td>
                            <select id="henkilo" name="henkilo">
                                <option value="0">&nbsp;</option>
                                <?php
                                foreach ($paikallaolo->haeVaihtoehdot('userid') as $henkilo) {
                                    $selected = ($paikallaolo->haeTiedot('userid') == $henkilo['userid']) ? 'selected="selected"' : '';
                                    printf('<option %s value="%s">%s - %s, %s</option>', $selected, $henkilo['userid'], $henkilo['username'], $henkilo['lastname'], $henkilo['firstname']);
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>

                <tr class="aika_wrapper" prefix="<?php $paikallaolo->ai('paikallaolo_'); ?>">
                    <td><label for="<?php $paikallaolo->ai('paikallaolo_'); ?>alkuaika-pvm"><?php $paikallaolo->haeNimi('alkuaika'); ?></label></td>
                    <td><input id="<?php $paikallaolo->ai('paikallaolo_'); ?>alkuaika-pvm" name="alkuaika-pvm" class="aika pvm varaus-pvm" value="<?php echo $alkupvm; ?>"> <input id="<?php $paikallaolo->ai('paikallaolo_'); ?>alkuaika-aika" size="8" name="alkuaika-aika" class="aika klo varaus-aika" value="<?php echo $alkuaika; ?>"></td>
                </tr>
                <tr class="aika_wrapper tr-end" prefix="<?php $paikallaolo->ai('paikallaolo_'); ?>">
                    <td><label for="<?php $paikallaolo->ai('paikallaolo_'); ?>loppuaika-pvm"><?php $paikallaolo->haeNimi('loppuaika'); ?></label></td>
                    <td><input id="<?php $paikallaolo->ai('paikallaolo_'); ?>loppuaika-pvm" name="loppuaika-pvm" class="aika pvm varaus-pvm" value="<?php echo $loppupvm; ?>"> <input id="<?php $paikallaolo->ai('paikallaolo_'); ?>loppuaika-aika" size="8" name="loppuaika-aika" class="aika klo varaus-aika" value="<?php echo $loppuaika; ?>"></td>
                </tr>

                <?php
                $saatavuudet = $paikallaolo->haeVaihtoehdot('saatavuus');
                $i = 0;
                foreach ($saatavuudet as $id => $nimi) {
                    $i++;
                    $checked = ($paikallaolo->haeTiedot('saatavuus') == $id) ? 'checked="checked"' : '';
                    ?>
                    <tr class="<?php echo ($i == count($saatavuudet)) ? 'tr-end' : ''; ?>">
                        <td><label for="saatavuus_<?php echo $id; ?>"><?php echo $nimi; ?></label></td>
                        <td><input <?php echo $checked; ?> type="radio" name="saatavuus" id="saatavuus_<?php echo $id; ?>" value="<?php echo $id; ?>"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <?php $checked = ($paikallaolo->haeTiedot('koskee_lentoja') == 1) ? 'checked="checked"' : ''; ?>
                    <td><label for="koskee_lentoja"><?php $paikallaolo->haeNimi('koskee_lentoja'); ?></label></td>
                    <td><input <?php echo $checked; ?> type="checkbox" name="koskee_lentoja" id="koskee_lentoja" value="1"></td>
                </tr>
                <tr class="tr-end">
                    <?php $checked = ($paikallaolo->haeTiedot('koskee_teoriaopetusta') == 1) ? 'checked="checked"' : ''; ?>
                    <td><label for="koskee_teoriaopetusta"><?php $paikallaolo->haeNimi('koskee_teoriaopetusta'); ?></label></td>
                    <td><input <?php echo $checked; ?> type="checkbox" name="koskee_teoriaopetusta" id="koskee_teoriaopetusta" value="1"></td>
                </tr>
                <tr>
                    <td><label for="lisatieto"><?php $paikallaolo->haeNimi('lisatieto'); ?></label></td>
                    <td><input type="text" name="lisatieto" id="lisatieto" value="<?php echo $paikallaolo->haeTiedot('lisatieto'); ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a title="Tallenna" id="paikallaoloilmoitus_tallenna" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                        <a href="varauskirja.php<?php
                    if ($muokataan_paikallaoloilmoitusta) {
                        echo '?ID=73';
                    }
                    ?>" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-cancel"></span>Peruuta</a>

                    </td>
                </tr>
            </table>
        </form>

    </div>
    <?php
    $kenen_ilmoitukset = ($on_admin) ? 'Kaikkien' : 'Omat';
    ?>
    <div class="paikallaolo_osio">
        <table class="paikallaolo_taulukko" id="paikallaoloilmoitus_taulukko">
            <caption><?php echo $kenen_ilmoitukset; ?> paikallaoloilmoitukset</caption>
            <thead>
                <tr>
                    <th>Kohde</th>
                    <?php if ($on_admin) { ?>
                        <th>Käyttäjä</th>
                        <th>Nimi</th>
<?php } ?>
                    <th colspan="2">Ajankohta</th>
                    <th>Tila</th>
                    <th>Lisätiedot</th>
                    <th id="toiminnot-sarake">Toiminnot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($tulevat_ilmoitukset as $id => $ilmoitus) {
                    ilmoitusrivi($ilmoitus, $id, false);
                }
                ?>
                <tr>
                    <th colspan="<?php echo ($on_admin) ? '8' : '6'; ?>">Vanhat paikallaoloilmoitukset</th>
                </tr>
                <?php
                foreach ($vanhat_ilmoitukset as $id => $ilmoitus) {
                    ilmoitusrivi($ilmoitus, $id, true);
                }
                ?>
            </tbody>
        </table>

    </div>

</div>

<script type="text/javascript">
    $(function() {
        $('#paikallaoloilmoitus_tallenna').click(function() {
            $('#paikallaoloilmoitus_form').validate();
            lomake = $('#paikallaoloilmoitus_form').serialize();
            $.post('varauskirja.php?ID=57', lomake, function(data) {
                if (data == '777') {
                    window.location = 'varauskirja.php?ID=73';
                } else {
                    //alert(data);
                }
            });
            
        });
        $('.paikallaoloilmoitus_poista').click(function() {
            var poid = $(this).attr('poid');
            $.post('varauskirja.php?ID=74', { poid: poid }, function (data) {
                if (data == '1') {
                    window.location = 'varauskirja.php?ID=73';
                }
            })
        });
        $('.paikallaoloilmoitus_muokkaa_btn').click(function() {
            var poid = $(this).attr('poid');
            $('#paikallaoloilmoitus_muokkaa_poid').val(poid);
            $('#paikallaoloilmoitus_muokkaa_form').submit();
        });
    }); 
</script>