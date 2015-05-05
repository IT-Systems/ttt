<?php
$ajat = its::viikonAlkuLoppu($viikon_paiva);
$paivat = its::viikonPaivat($viikon_paiva);

list($seur_vuosi, $seur_vk) = explode('-', date('Y-W', strtotime('+1 week', $viikon_paiva)));
list($ed_vuosi, $ed_vk) = explode('-', date('Y-W', strtotime('-1 week', $viikon_paiva)));

$huolto = new huoltoVaraus();
$tila = new tilaVaraus();
$lento = new lentoVaraus();
$paikallaolo = new paikallaoloVaraus();

$yleiset = new hallinta();

$ajat[1] = $ajat[1] . " 23:59:59"; // Fixed (19.7.2014) to get sundays reservations viewable
$varaukset = array(
    'tila' => $tila->haeVarauksetAjalta($ajat[0], $ajat[1], 'tila'),
    'huolto' => $huolto->haeVarauksetAjalta($ajat[0], $ajat[1], 'kone'),
    'lento' => $lento->haeVarauksetAjalta($ajat[0], $ajat[1], 'kone'),
    'paikallaolo' => $paikallaolo->haeVarauksetAjalta($ajat[0], $ajat[1], 'alkuaika')
);

$varaukset['konevaraukset'] = Varaus::yhdistaVarausTiedot(array($varaukset['lento'], $varaukset['huolto']), 'kone');
$tilavaraukset = Varaus::varausToArray($varaukset['tila']);
$paikallaoloilmoitukset = Varaus::varausToArray($varaukset['paikallaolo']);

$henkilot = $yleiset->haeKayttajat('nimitiedot', array('lastname' => 'asc', 'firstname' => 'asc'));
its::array_group($henkilot, 'userid', false, true);
its::left_join($paikallaoloilmoitukset, $henkilot, 'userid');

?>

<?php
$edellinen_url = "{$suunnittelu_url}y=$ed_vuosi&amp;w=$ed_vk";
$seuraava_url = "{$suunnittelu_url}y=$seur_vuosi&amp;w=$seur_vk";
?>


<table id="varaukset" class="viikkovaraukset">
    <caption><a href="<?php echo $edellinen_url; ?>" class="viikkonuoli"><img src="<?php print $APP->BASEURL; ?>/view/images/icon_previous.png"></a> <?php echo "Viikko " . date('W / Y, d.m.', $paivat[0]) . " - " . date('d.m.', $paivat[6]); ?> <a href="<?php echo $seuraava_url; ?>" class="viikkonuoli"><img src="<?php print $APP->BASEURL; ?>/view/images/icon_next.png"></a></caption>
    <tr>
        <th class="paiva" colspan="2">Konevaraukset</td>
<?php
$paivat_html = '';
foreach ($paivat as $paiva) {
    $paivat_html .= '<th class="paiva">' . date('d.m.', $paiva) . '<br>' . strftime('%A', $paiva) . '</th>';
}
echo $paivat_html;
?>
    </tr>

<?php
// haetaan kaikki konetyypit ja ryhmitellään ne id:n mukaan
$kaikki_konetyypit = $yleiset->haeKonetyypit();
its::array_group($kaikki_konetyypit, 'id', true, true);

// haetaan jokaisen konetyypin kaikki koneet ja ryhmitellään ne konetyypin mukaan
$kaikki_tyypin_koneet = $yleiset->haeKonetyypinKoneet();
its::array_group($kaikki_tyypin_koneet, 'konetyyppi');

// ryhmitellään kaikki konevaraukset koneen mukaan
its::array_group($varaukset['konevaraukset'], 'kone');


// käydään kaikki konetyypit läpi
foreach ($kaikki_tyypin_koneet as $konetyyppi => $koneet) {
    echo '<tr>';
    echo '<th class="konetyyppi" rowspan="' . count($koneet) . '">' . $kaikki_konetyypit[$konetyyppi]['nimi'] . '</th>';
    $kone_i = 1;
    // käydään kaikki konetyypin koneet läpi
    foreach ($koneet as $kone) {
        $kone_id = $kone['id'];

        // koska konetyypin <th>:ssa on rowspan, ei ensimmäisen koneen eteen laiteta tr-tagia
        if ($kone_i > 1) {
            echo '<tr>';
        }

        echo '<th class="kone">' . $kone['nimi'] . '</th>';
        its::array_group($varaukset['konevaraukset'][$kone_id], 'alkupvm');
        foreach ($paivat as $paiva) {
            $date = date('Y-m-d', $paiva);

            $lisaa_varaus = ($suunnittelutila) ? '<div class="lisaa-varaus-container"><a class="lisaa-varaus ui-od-button-with-icon ui-state-default ui-corner-all" href="#uusi-konevaraus" paiva="' . date('d.m.Y', $paiva) . '" aika="" vaaditaan="' . $kone_id . '"><span class="ui-icon ui-icon-plus"></span> Uusi varaus</a>' : '';

            if (empty($varaukset['konevaraukset'][$kone_id][$date])) {
                echo '<td class="tyhja-varaus">';
                echo $lisaa_varaus;
                echo '</td>';
            } else {
                echo '<td class="on-varaus">';
                // Yhdelle päivälle voi olla useampi varaus; loopataan ne läpi
                echo $lisaa_varaus;
                foreach ($varaukset['konevaraukset'][$kone_id][$date] as $varaus) {
                    $alku = new DateTime($varaus['alkuaika']);
                    $loppu = new DateTime($varaus['loppuaika']);
                    $linkki = ($suunnittelutila) ? array('<a class="muokkaa-varausta" href="#!" varaus_id="' . $varaus['id'] . '" varaus_tyyppi="' . $varaus['varaus_tyyppi'] . '">', '</a>') : array('<a class="nayta-varaus" href="#!" varaus_id="' . $varaus['id'] . '" varaus_tyyppi="' . $varaus['varaus_tyyppi'] . '">', '</a>');
                    echo '<div class="varaus ' . $varaus['varaus_tyyppi'] . '-varaus">' . $linkki[0] . $varaus['varaus_tyyppi'] . '&rarr; ' . $alku->format('H:i') . '-' . $loppu->format('H:i') . '<br/>' . $varaus['lisatieto'] . $linkki[1] . '</div>';
                }
                echo '</td>';
            }
        }
        if ($kone_i < count($koneet)) {
            echo '</tr>';
        }
        $kone_i++;
    }
    echo '</tr>';
}
?>
    <tr>
        <th class="paiva" colspan="2">Tilavaraukset</th>
    <?php echo $paivat_html; ?>
    </tr>

        <?php
        $kaikki_tilat = $yleiset->haeTilat();
        its::array_group($kaikki_tilat, 'id', true, true);
        its::array_group($tilavaraukset, 'tila');

        foreach ($kaikki_tilat as $tila_id => $tila) {
            echo '<tr>';
            echo '<th colspan="2" class="tila">' . $tila['nimi'] . ' / ' . $tila['koodi'] . '</th>';

            its::array_group($tilavaraukset[$tila_id], 'alkupvm');
            foreach ($paivat as $paiva) {
                $date = date('Y-m-d', $paiva);

                $lisaa_varaus = ($suunnittelutila) ? '<div class="lisaa-varaus-container"><a class="lisaa-varaus ui-od-button-with-icon ui-state-default ui-corner-all" href="#uusi-tilavaraus" paiva="' . date('d.m.Y', $paiva) . '" aika="" vaaditaan="' . $tila_id . '"><span class="ui-icon ui-icon-plus"></span> Uusi varaus</a>' : '';
                if (empty($tilavaraukset[$tila_id][$date])) {
                    echo '<td class="tyhja-varaus">' . $lisaa_varaus . '</td>';
                } else {

                    echo '<td class="on-varaus">';
                    echo $lisaa_varaus;
                    foreach ($tilavaraukset[$tila_id][$date] as $varaus_id => $varaus) {
                        $alku = new DateTime($varaus['alkuaika']);
                        $loppu = new DateTime($varaus['loppuaika']);
                        $linkki = ($suunnittelutila) ? array('<a class="muokkaa-varausta" href="#!" varaus_id="' . $varaus['id'] . '" varaus_tyyppi="tila">', '</a>') : array('<a class="nayta-varaus" href="#!" varaus_id="' . $varaus['id'] . '" varaus_tyyppi="tila">', '</a>');
                        
                        echo '<div class="varaus tila-varaus">' . $linkki[0] . $alku->format('H:i') . '-' . $loppu->format('H:i') . '<br/>' . $varaus['lisatieto'] . $linkki[1] . '</div>';
                    }
                    echo '</td>';
                }
            }
            echo '</tr>';
        }
        ?>
</table>

