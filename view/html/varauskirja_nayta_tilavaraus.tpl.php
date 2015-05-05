<div id="varauksen_tiedot_container">
<table class="datataulukko">
    <tr>
        <th>Alkuaika</th>
        <td><?php echo $tiedot['alkuaika']; ?></td>
    </tr>
    <tr>
        <th>Loppuaika</th>
        <td><?php echo $tiedot['loppuaika']; ?></td>
    </tr>
    <tr>
        <th>Tila</th>
        <td><?php echo $tila_tiedot['nimi']; ?></td>
    </tr>
    <tr>
        <th>Kustannuspaikka</th>
        <td><?php echo $kustannuspaikka_tiedot['nimi']; ?></td>
    </tr>
    <tr>
        <th>Lisätieto</th>
        <td><?php echo $tiedot['lisatieto']; ?></td>
    </tr>
    <tr>
        <th>Tarkennukset</th>
        <td><?php echo $tiedot['tarkennukset']; ?></td>
    </tr>
    <tr>
        <th>Vastuuhenkilöt</th>
        <td>
        <?php
            foreach ($tiedot['vastuuhenkilot'] as $osallistuja) {
                $vastuuhenkilot[] = '(' . $osallistuja['username'] . ') ' . $osallistuja['firstname'] . ' ' . $osallistuja['lastname'];
            }
            echo implode(', ', $vastuuhenkilot);
        ?>
        </td>
    </tr>
    <tr>
        <th>Osallistujat</th>
        <td>
        <?php
            foreach ($tiedot['osallistujat'] as $osallistuja) {
                $osallistujat[] = '(' . $osallistuja['username'] . ') ' . $osallistuja['firstname'] . ' ' . $osallistuja['lastname'];
            }
            echo implode(', ', $osallistujat);
        ?>
        </td>
    </tr>
    <tr>
        <td colspan="2"><?php echo ($tiedot['tekstiviesti'] == 1) ? 'Osallistujille lähetetään varauksesta tekstiviesti.' : 'Varauksesta ei lähetetä tekstiviestiä.'; ?></td>
    </tr>
</table>
</div>