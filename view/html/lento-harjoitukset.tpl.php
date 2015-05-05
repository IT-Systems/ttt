<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Harjoitus perustettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
if ($_GET['PF'] == '2') {
    $aMsg[0] = "Harjoitus poistettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
if ($_GET['PF'] == '3') {
    $aMsg[0] = "Harjoituksen tiedot tallennettiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div">
    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>
    <h2>Harjoitukset</h2>
    <div class="tiedot_osio">
        <table class="datataulukko">
            <thead>
                <tr>
                    <th>Päivä</th>
                    <th>Toim.laatu</th>
                    <th>Harjoittelija</th>
                    <th>Harjoittelija<br/>(perämies)</th>
                    <th>Opettaja</th>
                    <th>IFR</th>
                    <th>Dual</th>
                    <th>Huomautukset</th>
                    <th>Lennettävä<br/>uudelleen</th>
                    <th>Syllabus</th>
                    <th>Toiminnot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (sizeof($harjoitukset) > 0) {
                    foreach ($harjoitukset as $harkka) {
                        $laatu = '';
                        if ($harkka["toiminnan_laatu"] == 1)
                            $laatu = "Yksityinen<br/>harjoitus";
                        if ($harkka["toiminnan_laatu"] == 2)
                            $laatu = "Ansiotoimintaa";
                        $ope = $len->haeHenkilot($harkka["opettaja_id"]);
                        $ope["nimi"] = (!$ope) ? "Poistunut" : $ope["nimi"] = $ope["firstname"] . " " . $ope["lastname"];

                        $harjoittelija = $len->haeHenkilot($harkka["harjoittelija_user_id"]);
                        $harjoittelija["nimi"] = (!$harjoittelija) ? "Poistunut" : $harjoittelija["nimi"] = $harjoittelija["firstname"] . " " . $harjoittelija["lastname"];

                        $peramies = $len->haeHenkilot($harkka["harjoittelija_peramies_user_id"]);
                        $peramies["nimi"] = (!$peramies) ? "Poistunut" : $peramies["firstname"] . " " . $peramies["lastname"];

                        $syl = $len->haeSyllabukset($harkka["syllabus_id"]);
                        ?>
                        <tr>
                            <td><?php print $ak->dbDateToFiDate($harkka["paivamaara"]); ?></td>
                            <td><?php print $laatu ?>
                            <td><?php print $harjoittelija["nimi"]; ?></td>
                            <td><?php print $peramies["nimi"]; ?></td>
                            <td><?php print $ope["nimi"]; ?></td>
                            <td><?php if ($harkka["ifr_on"] == 1) print "&check;"; ?></td>
                            <td><?php if ($harkka["dual_on"] == 1) print "&check;"; ?></td>
                            <td><?php print $harkka["huomautukset"]; ?></td>
                            <td><?php if ($harkka["uudelleen"] == 1) print "&check;"; ?></td>
                            <td><?php print $syl["nimi"]; ?></td>
                            <td>
                                <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=61&harjoitusId=' . stripslashes($harkka['id']); ?>">
                                    <img src="<?php print $APP->BASEURL; ?>/view/images/icon-edit.png" title="Muokkaa" alt="Muokkaa" class="icon"/>
                                </a>
                                <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=70&harjoitusId=' . stripslashes($harkka['id']); ?>" style="margin-left: 10px;">
                                    <img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Poista" alt="Poista" class="icon" onclick="javascript:return confirm('Haluatko varmasti poistaa tämän harjoituksen?');"/>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                else {
                    ?>
                    <tr>
                        <td colspan="11">Ei harjoituksia.</td>
                    </tr>
    <?php }
?>
            </tbody>
        </table>
    </div>
</div>