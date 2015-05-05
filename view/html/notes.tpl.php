<?php

$kansio_id = (empty($_GET["FID"])) ? 1 : $_GET["FID"];
$vkansio = $v->haeKansio($kansio_id);
$viestit = $v->haeKansionViestit($kansio_id);
?>








<div id="kansiot">
    <h3>Kansiot</h3>
    <?php
    foreach ($kansiot as $kansio) {
        // Luodaan jokaisesta kansiosta linkki, jota käytetään viestilistan rakennukseen.
        $sis = $v->haeKansionViestit($kansio["id"]);
        /** Näytetäänkö avatun vai suljetun kansion kuvake */
        $kansio_icon = (($kansio['id'] == $_GET['FID']) || (!$_GET['FID'] && ($kansio['id'] == 1))) ? 'ui-icon-folder-open' : 'ui-icon-folder-collapsed';
        ?>
        <a href="<?php print $_SERVER["PHP_SELF"]; ?>?ID=31&FID=<?php print $kansio["id"]; ?>" class="kansio-linkki">
            <div class="kansio ui-od-button-with-icon ui-state-default ui-corner-all">
                <span class="ui-icon <?php echo $kansio_icon; ?>"></span>
                <h4><?php print $kansio["nimi"]; ?></h4>
                <p>Viestejä: <?php print sizeof($sis); ?></p>
            </div>
        </a>
    <?php }
?>
</div>

<div id="viestit">

    <table id="tbl_notes" cellpadding="0" cellspacing="0" class="datataulukko">
        <tr>
            <th colspan="5" class="viestihead" id="tbl_notes_head"><?php print $vkansio["nimi"]; ?></th>
        </tr>
<?php
if (sizeof($viestit) > 0) {
    // Laitetaan tässä vaiheessa vain 5 viestiä / sivu
    $pages = $navi->generate($viestit, 10); // Sama alla
    if ($navi->pages > 1) {
        ?>
                <tr>
                    <th colspan="5" class="navi"><?php print $navi->links(); ?></th>
                </tr>
        <?php
    }
}
?>
        <tr>
            <th></th>
            <th>Aihe</th>
            <th>Lähettäjä</th>
            <th>Lähetetty</th>
            <th></th>
        </tr>
<?php
if (sizeof($pages) > 0) {
    $h = new hallinta();
    ?>
            <form action="<?php print $APP->BASEURL; ?>/viestit.php?ID=31&FID=<?php print $kansio_id; ?>" method="POST" id="frmMessages">
    <?php
    foreach ($pages as $viesti) {
        $lahettaja = $v->haeKayttaja($viesti["user_id"]);
        $aika = $h->dbTimeToFiTime($viesti["lahetetty_aika"]);
        $rcls = ($viesti["tarkea"] == 1) ? " class='impt'" : "";
        ?>
                    <tr<?php print $rcls; ?>>
                        <td><input type="checkbox" name="viesti_id[<?php print $viesti["id"]; ?>]" value="1" class="required"/></td>
                        <td>
                            <a href="<?php print $APP->BASEURL; ?>/viestit.php?ID=35&FID=<?php print $kansio_id; ?>&note_id=<?php print $viesti["id"]; ?>">
                <?php print $viesti["aihe"]; ?>
                            </a>
                        </td>
                        <td><?php print $lahettaja["email"]; ?></td>
                        <td><?php print $aika; ?></td>
                        <td>
                    <?php if ($viesti["tarkea"] == 0) { ?>
                                <a href="<?php print $APP->BASEURL; ?>/viestit.php?ID=31&FID=<?php print $kansio_id; ?>&note_id=<?php print $viesti["id"]; ?>&set=important"><img src="<?php print $APP->BASEURL; ?>/view/images/icon_star.png"></a>
                        <?php } else {
                        ?>
                                <a href="<?php print $APP->BASEURL; ?>/viestit.php?ID=31&FID=<?php print $kansio_id; ?>&note_id=<?php print $viesti["id"]; ?>&set=nimportant"><img src="<?php print $APP->BASEURL; ?>/view/images/icon_staroff.png"></a>
            <?php }
        ?>
                            <a href="<?php print $APP->BASEURL; ?>/viestit.php?ID=31&FID=<?php print $kansio_id; ?>&note_id=<?php print $viesti["id"]; ?>&set=trash" onclick="return confirm('Haluatko varmasti siirtää viestin roskakoriin?')"><img src="<?php print $APP->BASEURL; ?>/view/images/icon_trash.png"></a>
                        </td>
                    </tr>
        <?php }
    ?>
                <tr>
                    <td colspan="5">Siirrä valitut viestit kansioon
                        <select name="tgtFolderId">
                        <?php
                        $kansioVal = $v->haeKansiotValintaListaan($kansio_id);
                        foreach ($kansioVal as $fld) {
                            ?>
                                <option value="<?php print $fld["id"]; ?>"><?php print $fld["nimi"]; ?></option>
                            <?php }
                        ?>
                        </select>
                        <input type="hidden" name="hidMoveNotes" id="hidMoveNotes" value="1" />
                        <a href="javascript:void(0);" onclick="javascript:siirraViestit();" title="Submit" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Siirrä</a>
                    </td>
                </tr>
            </form>
            <script type="text/javascript">
                function siirraViestit()
                {
                    $("#frmMessages").submit();
                }
            </script>
                            <?php
                        } else {
                            ?>
            <tr>
                <td colspan="5">Ei viestejä</td>
            </tr>
    <?php }
?>
    </table>
</div>