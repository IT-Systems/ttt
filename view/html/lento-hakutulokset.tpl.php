<div class="tiedot_iso_osio">
<div class="hakutulokset">
<?php
if ($lennot == '') { }
elseif (!$lennot) { ?>
Hakuehdoilla ei löytynyt lentoja
<?php
}
else { ?>
    <div class="scrollContent">
<?php
    $lentolkm = sizeof($lennot);
    if ($_POST["intView"] == 1) { ?>
        <table class="datataulukko">
            <tr>
                <th class="ul_toprow" colspan="18">Haun tulokset ( <?php print $lentolkm; ?> kpl )</th>
            </tr>
            <tr>
                <th>Päivä</th>
                <th>Ilma-alus</th>
                <th>PIC</th>
                <th>COP</th>
                <th>Muu</th>
                <th>Matkustajia</th>
                <th>Lähtöpaikka</th>
                <th>Laskupaikka</th>
                <th>Offblock</th>
                <th>Lähtöaika</th>
                <th>Laskuaika</th>
                <th>Onblock</th>
                <th>Ilma-aika</th>
                <th>Lentoaika</th>
                <th>Laskuja</th>
                <th>Laatu</th>
                <th>Simulaattori</th>
                <th>Huomautus</th>
            </tr>
<?php
      foreach ($lennot as $lento) {
            $kone = $len->haeIlmaAlus($lento["kone_id"]);
            if (!$kone) $kone["nimi"] = "Poistunut";
            $laatu = $len->haeLennonToiminnanLaadut($lento["toim_tyyppi_id"]);
            if (!$laatu) $laatu["nimi"] = "Poistunut";
        
            $paallikko = $len->haeHenkilot($lento["paallikko_user_id"]);
            if (!$paallikko) {
                $paallikko["nimi"] = "Poistunut";
            }
            else {
                $paallikko["nimi"] = $paallikko["firstname"] . " " . $paallikko["lastname"];
            }

            $peramies = $len->haeHenkilot($lento["peramies_user_id"]);
            if (!$peramies) {
                $peramies["nimi"] = "Poistunut";
            }
            else {
                $peramies["nimi"] = $peramies["firstname"] . " " . $peramies["lastname"];
            }

            $muujasen = $len->haeHenkilot($lento["muu_jasen_user_id"]);
            if (!$muujasen) {
                $muujasen["nimi"] = "Poistunut";
            }
            else {
                $muujasen["nimi"] = $muujasen["firstname"] . " " . $muujasen["lastname"];
            } ?>
            <tr>
                <td><?php print $ak->dbDateToFiDate($lento["alkamispaiva"]); ?></td>
                <td><?php print $kone["nimi"]; ?></td>
                <td><?php print $paallikko["nimi"]; ?></td>
                <td><?php print $peramies["nimi"]; ?></td>
                <td><?php print $muujasen["nimi"]; ?></td>
                <td><?php print $lento["matkustajia"]; ?></td>
                <td><?php print $lento["lahtopaikka"]; ?></td>
                <td><?php print $lento["maarapaikka"]; ?></td>
                <td><?php print substr($lento["off_block_aika"],0,-3); ?></td>
                <td><?php print substr($lento["lahtoaika"],0,-3); ?></td>
                <td><?php print substr($lento["saapumisaika"],0,-3); ?></td>
                <td><?php print substr($lento["on_block_aika"],0,-3); ?></td>
                <td><?php print substr($lento["ilma_aika"],0,-3); ?></td>
                <td><?php print substr($lento["lentoaika"],0,-3); ?></td>
                <td><?php print $lento["laskeutumisia_paiva"] + $lento["laskeutumisia_yo"]; ?></td>
                <td><?php print $laatu["nimi"]; ?></td>
                <td></td>
                <td><?php print $lento["huomautukset"]; ?></td>
            </tr>
<?php
      } ?>
        </table>
<?php
    } 
    elseif ($_POST["intView"] == 2) { ?>
        <table class="tbl_kalenteri">
            <tr>
                <th class="ul_toprow" colspan="18">Haun tulokset ( <?php print $lentolkm; ?> kpl )</th>
            </tr>
            <tr>
                <th>Päivä</th>
                <th colspan="2">Lähtö</th>
                <th colspan="2">Tulo</th>
                <th colspan="2">Ilma-alus</th>
                <th>Block</th>
                <th>PIC</th>
                <th>COP</th>
                <th colspan="2">Lähdöt</th>
                <th colspan="2">Laskut</th>
                <th>Yö</th>
                <th>IFR</th>
            </tr>
<?php
      foreach ($lennot as $lento) {
            $kone = $len->haeIlmaAlus($lento["kone_id"]);
            if (!$kone) {
                $kone["nimi"] = "Poistunut";
                $konetyyppi["nimi"] = "Ei tiedossa";
            }
            else {
                $konetyyppi = $len->haeKoneTyypit($kone["konetyyppi"]);
                if (!$konetyyppi) $konetyyppi["nimi"] = "Poistunut";
            }
            $laatu = $len->haeLennonToiminnanLaadut($lento["toim_tyyppi_id"]);
            if (!$laatu) $laatu["nimi"] = "Poistunut";

            $paallikko = $len->haeHenkilot($lento["paallikko_user_id"]);
            if (!$paallikko) {
                $paallikko["nimi"] = "Poistunut";
            }
            else {
                $paallikko["nimi"] = $paallikko["firstname"] . " " . $paallikko["lastname"];
            }

            $peramies = $len->haeHenkilot($lento["peramies_user_id"]);
            if (!$peramies) {
                $peramies["nimi"] = "Poistunut";
            }
            else {
                $peramies["nimi"] = $peramies["firstname"] . " " . $peramies["lastname"];
            } ?>
            <tr>
                <td><?php print $ak->dbDateToFiDate($lento["alkamispaiva"]); ?></td>
                <td><?php print $lento["lahtopaikka"]; ?></td>
                <td><?php print substr($lento["lahtoaika"],0,-3); ?></td>
                <td><?php print $lento["maarapaikka"]; ?></td>
                <td><?php print substr($lento["saapumisaika"],0,-3); ?></td>
                <td><?php print $konetyyppi["nimi"]; ?></td>
                <td><?php print $kone["nimi"]; ?></td>
                <td><?php print substr($lento["lentoaika"],0,-3); ?></td>
                <td><?php print $paallikko["nimi"]; ?></td>
                <td><?php print $peramies["nimi"]; ?></td>
                <td><?php print $lento["lentoonlahtoja_paiva"]; ?></td>
                <td><?php print $lento["lentoonlahtoja_yo"]; ?></td>
                <td><?php print $lento["laskeutumisia_paiva"]; ?></td>
                <td><?php print $lento["laskeutumisia_yo"]; ?></td>
                <td><?php if ($lento["yoaika_on"] == 1) print substr($lento["yoaika"],0,-3); ?></td>
                <td><?php if ($lento["ifr_aika_on"] == 1) print substr($lento["ifr_aika"],0,-3); ?></td>
            </tr>
<?php
        } ?>
        </table>

<?php
    } ?>
        </div>
        <script>$(".scrollContent").scrollTop(3000);</script>
    </div>
<?php 
} ?>
</div>
</div>