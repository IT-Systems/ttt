<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<div class="kp_div">

    <h2>Syllabuksien listaus</h2>

    <div class="tiedot_osio">
        <form action="./hallinta.php?ID=30&act=1" method="POST" id="frmProperties" name="frmProperties">

            <table class="datataulukko">
                <thead>
                    <tr>
                        <th>Syllabus</th>
                        <th>Selite</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (sizeof($syllabukset) > 0) {
                        foreach ($syllabukset as $syllabus) {
                            ?>
                            <tr>
                                <td><?php print $syllabus["nimi"]; ?></td>
                                <td><?php print $syllabus["selite"]; ?></td>
                                <td>
                                    <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=67&syllabus_id=<?php print $syllabus["id"]; ?>" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-pencil"></span>Harjoitukset</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="2">Ei tallennettuja syllabuksia.</td>
                        </tr>
                        <?php }
                    ?>
                </tbody>
            </table>
    </div>
</div>