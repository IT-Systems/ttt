<?php
if (!empty($yhttiedot["email"])) { ?>
    <tr class="hlotieto">
        <th>Sähköposti</th>
        <td><?php print $yhttiedot["email"]; ?></td>
    </tr>
<?php
}
if (!empty($yhttiedot["puhelin"])) { ?>
    <tr class="hlotieto">
        <th>Puhelin</th>
        <td><?php print $yhttiedot["puhelin"]; ?></td>
    </tr>
<?php
}
if (!empty($yhttiedot["katuosoite"])) { ?>
    <tr class="hlotieto">
        <th>Katuosoite</th>
        <td><?php print $yhttiedot["katuosoite"]; ?></td>
    </tr>
<?php
}
if (!empty($yhttiedot["postinumero"])) { ?>
    <tr class="hlotieto">
        <th>Postinumero</th>
        <td><?php print $yhttiedot["postinumero"]; ?></td>
    </tr>
<?php
}
if (!empty($yhttiedot["kaupunki"])) { ?>
    <tr class="hlotieto">
        <th>Kaupunki</th>
        <td><?php print $yhttiedot["kaupunki"]; ?></td>
    </tr>
<?php
} ?>