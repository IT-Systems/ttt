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
        <th>Kone</th>
        <td><?php echo $kone_tiedot['nimi'] . ' (' . $kone_tiedot['konetyyppi_nimi'] . ')'; ?></td>
    </tr>
    <tr>
        <th>Huolto</th>
        <td><?php echo '(' . $tiedot['nimike']['koodi'] . ') ' . $tiedot['nimike']['nimike']; ?></td>
    </tr>
    <tr>
        <th>Vastuuhenkilö</th>
        <td><?php echo '(' . $tiedot['vastuuhenkilo']['username'] . ') ' . $tiedot['vastuuhenkilo']['firstname'] . ' ' . $tiedot['vastuuhenkilo']['lastname']; ?></td>
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
        <th>Miehistö</th>
        <td>
        <?php
            foreach ($tiedot['miehisto'] as $osallistuja) {
                $miehisto[] = '(' . $osallistuja['username'] . ') ' . $osallistuja['firstname'] . ' ' . $osallistuja['lastname'];
            }
            echo implode(', ', $miehisto);
        ?>
        </td>
    </tr>
    <tr>
        <td colspan="2"><?php echo ($tiedot['tekstiviesti'] == 1) ? 'Osallistujille lähetetään varauksesta tekstiviesti.' : 'Varauksesta ei lähetetä tekstiviestiä.'; ?></td>
    </tr>
</table>
</div>
