<?php

$huolto = new huoltoVaraus();
$tila = new tilaVaraus();
$lento = new lentoVaraus();
$yleiset = new hallinta();
$paikallaolo = new paikallaoloVaraus();

$ajat[0] = $ajat[1] = date('Y-m-d', $viikon_paiva);
$ajat[0] .= ' 00:00:00';
$ajat[1] .= ' 23:59:59';


$varaukset = array(
    'tila'      => $tila->haeVarauksetAjalta($ajat[0], $ajat[1], 'tila'),
    'huolto'    => $huolto->haeVarauksetAjalta($ajat[0], $ajat[1], 'kone'),
    'lento'     => $lento->haeVarauksetAjalta($ajat[0], $ajat[1], 'kone'),
    'paikallaolo'   => $paikallaolo->haeVarauksetAjalta($ajat[0], $ajat[1], 'alkuaika')
);

$konevaraukset = Varaus::yhdistaVarausTiedot(array($varaukset['lento'], $varaukset['huolto']), 'kone');
$tilavaraukset = Varaus::varausToArray($varaukset['tila']);


$paikallaoloilmoitukset = Varaus::varausToArray($varaukset['paikallaolo']);
$henkilot = $yleiset->haeKayttajat('nimitiedot', array('lastname' => 'asc', 'firstname' => 'asc'));
its::array_group($henkilot, 'userid', false, true);
its::left_join($paikallaoloilmoitukset, $henkilot, 'userid');

$eilen = date('d.m.Y', $viikon_paiva - 86400);
$huomenna = date('d.m.Y', $viikon_paiva + 86400);
$tanaan = date('d.m.Y', $viikon_paiva);


?>

<?php
$eilen_url = "{$suunnittelu_url}d=$eilen&amp;nakyma=paiva";
$huomenna_url = "{$suunnittelu_url}d=$huomenna&amp;nakyma=paiva";

?>
<style type="text/css">
    #varaus_otsikot {
        float: left;
        display: inline;
        margin-top: 2em;
    }
    #varaus_otsikot caption {
        height: 41px;
        padding: 0;
    }
    #paivavaraukset_container {
        width: 1065px;
        overflow: auto;
    }
    #varaus_otsikot .paiva {
        min-width: 100px;
    }
    #paiva_caption {
        display: block;
        float: left;
        width: 1140px;
        background-color: #2B393C;
        text-align: center;
        font-size: 16px;
        color: #fff;
        padding: 0.5em;
        margin: 1em 22px;
        font-weight: bold;
    }
    
</style>



<?php

    
    // haetaan kaikki konetyypit ja ryhmitellään ne id:n mukaan
    $kaikki_konetyypit = $yleiset->haeKonetyypit();
    its::array_group($kaikki_konetyypit, 'id', true, true);
    
    // haetaan jokaisen konetyypin kaikki koneet ja ryhmitellään ne konetyypin mukaan
    $kaikki_tyypin_koneet = $yleiset->haeKonetyypinKoneet();
    its::array_group($kaikki_tyypin_koneet, 'konetyyppi');
    
    // ryhmitellään kaikki konevaraukset koneen mukaan
    its::array_group($konevaraukset, 'kone');
    
    $kaikki_tilat = $yleiset->haeTilat();
    its::array_group($kaikki_tilat, 'id', true, true);
    its::array_group($tilavaraukset, 'tila');
?>

<div id="paiva_caption">
<a href="<?php echo $eilen_url; ?>" class="viikkonuoli"><img src="<?php print $APP->BASEURL;?>/view/images/icon_previous.png"></a> <?php echo strftime('%A %d.%m.%Y, viikko %V', $viikon_paiva); ?> <a href="<?php echo $huomenna_url; ?>" class="viikkonuoli"><img src="<?php print $APP->BASEURL;?>/view/images/icon_next.png"></a>
</div>

<table id="varaus_otsikot">
    <tr>
        <th colspan="2" class="paiva">Konevaraukset</th>
    </tr>
    <?php
    foreach ($kaikki_tyypin_koneet as $konetyyppi => $koneet) {
        echo '<tr>';
        echo    '<th class="konetyyppi first" rowspan="' . count($koneet) . '">' . $kaikki_konetyypit[$konetyyppi]['nimi'] . '</th>';
        $kone_i = 1;
        // käydään kaikki konetyypin koneet läpi
        foreach ($koneet as $kone) {
            $kone_id = $kone['id'];
            // koska konetyypin <th>:ssa on rowspan, ei ensimmäisen koneen eteen laiteta tr-tagia
            if ($kone_i > 1) {
                echo '<tr>';
            }
            echo '<th id="koneotsikko_' . $kone_id . '" class="kone first">' . $kone['nimi'] . '</th>';
            if ($kone_i < count($koneet)) {
                echo '</tr>';
            }
            $kone_i++;
        }
        echo '</tr>';
    }
        ?>
    <tr>
        <th colspan="2" class="paiva">Tilavaraukset</th>
    </tr>
    
    <?php
        foreach ($kaikki_tilat as $tila_id => $tila) {
            echo '<tr>';
            echo '<th colspan="2" id="tilaotsikko_' . $tila['id'] . '" class="tila first">' . $tila['nimi'] . ' / ' . $tila['koodi'] . '</th>';
            echo '</tr>';
        }
    ?>
</table>

<div id="paivavaraukset_container">
<table id="varaukset" class="paivavaraukset">
    
    <tr>
        <?php
            $tunnit_html = '';
            for ($i = 0; $i <= 23; $i++) {
                $id = 'id="kello_' . $i . '"';
                $tunnit_html .= '<th ' . $id . ' class="paiva">' . str_pad($i, 2, "0", STR_PAD_LEFT) . '</th>';
            }
            echo $tunnit_html;
        ?>
    </tr>
    
    <?php

    
    // käydään kaikki konetyypit läpi
    foreach ($kaikki_tyypin_koneet as $konetyyppi => $koneet) {
        // käydään kaikki konetyypin koneet läpi
        foreach ($koneet as $kone) {
            $kone_id = $kone['id'];
            echo '<tr class="varausrivi" tyyppi="kone" varaus_id="' . $kone_id . '">';
            
            // koska konetyypin <th>:ssa on rowspan, ei ensimmäisen koneen eteen laiteta tr-tagia  
            
            its::array_group($konevaraukset[$kone_id], 'alkutunti', true, true);

            for ($tunti = 0; $tunti <= 23; $tunti++) {
                $varaus_auki--;
                $varaus = $konevaraukset[$kone_id][$tunti];
                $lisaa_varaus = ($suunnittelutila) ? '<div class="lisaa-varaus-container"><a class="lisaa-varaus ui-od-button-with-icon ui-state-default ui-corner-all" href="#uusi-konevaraus" paiva="' . $tanaan . '" aika="' . str_pad($tunti, 2, "0", STR_PAD_LEFT)  . ':00" vaaditaan="' . $kone_id . '"><span class="ui-icon ui-icon-plus"></span> Uusi varaus</a>' : '&nbsp;';
                if ((empty($varaus)) && ($varaus_auki < 1)) {
                    echo '<td class="tyhja-varaus">' . $lisaa_varaus . '</td>';
                }
                elseif (!empty($varaus)) {
                    $colspan = $varaus['lopputunti'] - $varaus['alkutunti'];
                     $linkki = ($suunnittelutila) ? array('<a class="muokkaa-varausta" href="#!" varaus_id="' . $varaus['id'] . '" varaus_tyyppi="' . $varaus['varaus_tyyppi'] . '">','</a>') : array('', '');
                    echo '<td class="on-varaus" colspan="' . $colspan . '">';
                    echo $lisaa_varaus;
                    echo '<div class="' . $varaus['varaus_tyyppi'] . '-varaus">' . $linkki[0] . 'Varaus: ' . $varaus['lisatieto'] . $linkki[1] . '</div>';
                    echo '</td>';
                }
                unset($varaus);
                if ($colspan > 0) {
                    $varaus_auki = $colspan;
                    unset($colspan);
                }
            } 
                echo '</tr>';
        }
    }
    ?>
    <tr>
        <th class="paiva" colspan="24">&nbsp;</td>
    </tr>
    <?php

    foreach ($kaikki_tilat as $tila_id => $tila) {        
        its::array_group($tilavaraukset[$tila_id], 'alkutunti', true, true);
        echo '<tr class="varausrivi" tyyppi="tila" varaus_id="' . $tila_id . '">';
        
        for ($tunti = 0; $tunti <= 23; $tunti++) {
            $varaus_auki--;
            $varaus = $tilavaraukset[$tila_id][$tunti];
            $lisaa_varaus = ($suunnittelutila) ? '<div class="lisaa-varaus-container"><a class="lisaa-varaus ui-od-button-with-icon ui-state-default ui-corner-all" href="#uusi-tilavaraus" paiva="' . $tanaan . '" aika="' . str_pad($tunti, 2, "0", STR_PAD_LEFT)  . ':00" vaaditaan="' . $tila_id . '"><span class="ui-icon ui-icon-plus"></span> Uusi varaus</a>' : '&nbsp;';
            if ((empty($varaus)) && ($varaus_auki < 1)) {
                echo '<td class="tyhja-varaus">' . $lisaa_varaus . '</td>';
            }
            elseif (!empty($varaus)) {
                $colspan = $varaus['lopputunti'] - $varaus['alkutunti'];
                $linkki = ($suunnittelutila) ? array('<a class="muokkaa-varausta" href="#!" varaus_id="' . $varaus['id'] . '" varaus_tyyppi="tila">','</a>') : array('', '');
                echo '<td class="on-varaus" colspan="' . $colspan . '">';
                echo $lisaa_varaus;
                echo '<div class="tila-varaus">' . $linkki[0] . 'Varaus: ' . $varaus['lisatieto'] . $linkki[1] . '</div>';
                echo '</td>';
            }
            unset($varaus);
            if ($colspan > 0) {
                $varaus_auki = $colspan;
                unset($colspan);
            }
        }
        echo '</tr>';
    }
    ?>
    
</table>
</div>


<script type="text/javascript">
   $('.varausrivi').each(function () {
      var korkeus = $(this).height();
      if (!$.browser.mozilla) {
          korkeus -= 22;
      }
      var tyyppi = $(this).attr('tyyppi');
      var varaus_id = $(this).attr('varaus_id');
      var otsikko_reference = '#' + tyyppi + 'otsikko_' + varaus_id;
      $(otsikko_reference).height(korkeus);
      console.log(otsikko_reference);
   });

   if ($.browser.msie) {
       offset = 305;
   } else {
       offset = 185;
   }
   $('#paivavaraukset_container').scrollLeft($('#kello_8').offset().left - offset);
    
</script>