<table id="paikallaoloilmoitukset" class="viikkovaraukset">
    <caption>Paikallaoloilmoitukset</caption>
    <thead>
        <tr>
            <th class="paiva" id="kohde-sarake">Kohde</th>
            <th class="paiva" id="nimi-sarake">Henkilö</th>
            <th class="paiva" id="tunnus-sarake">Tunnus</th>
            <th class="paiva">Aika</th>
            <th class="paiva">Tila</th>
            <th class="paiva">Lisätiedot</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $saatavuudet = $paikallaolo->haeVaihtoehdot('saatavuus');
        if (isset($paikallaoloilmoitukset)) {
            foreach ($paikallaoloilmoitukset as $ilmoitus) {
                $kohteet = '';
                if ($ilmoitus['koskee_lentoja'] == 1) {
                    $kohteet .= '<img class="paikallaoloilmoitus_icon" src="' . $APP->BASEURL . '/view/images/icon_plane.png" alt="ilmoitus koskee lentoja"/> ';
                }
                if ($ilmoitus['koskee_teoriaopetusta'] == 1) {
                    $kohteet .= '<img class="paikallaoloilmoitus_icon" src="' . $APP->BASEURL . '/view/images/icon_ground.png" alt="ilmoitus koskee teoriaopetusta"/>';
                }
                $alku = strtotime($ilmoitus['alkuaika']);
                $loppu = strtotime($ilmoitus['loppuaika']);
                $aikarivi = strftime('<strong>%a</strong> %H:%M', $alku) . ' - ';
                if (date('dmY', $alku) != date('dmY', $loppu)) {
                    $aikarivi .= strftime('<strong>%a</strong> %H:%M', $loppu);
                } else {
                    $aikarivi .= strftime('%H:%M', $loppu);
                }
                ?>
                <tr class="saatavuus_<?php echo $ilmoitus['saatavuus']; ?>">
                    <td><?php echo $kohteet; ?></td>
                    <td><?php echo $ilmoitus['userid']['lastname'] . ', ' . $ilmoitus['userid']['firstname']; ?></td>
                    <td><?php echo $ilmoitus['userid']['username']; ?></td>
                    <td><?php echo $aikarivi; ?></td>
                    <td><?php echo $saatavuudet[$ilmoitus['saatavuus']]; ?></td>
                    <td><?php echo $ilmoitus['lisatieto']; ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>

</table>