<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "jotain tehtiin";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
}
?>
    <?php require("omattiedot_napit.tpl.php"); ?>

<div class="kp_div">

    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0]?>
    </div>

    <h2>Käyttäjät</h2>

    <div class="omattiedot_osio">

        <div id="data_table_container">
        <table id="data_table">
            <thead>
<?php
/*
if (empty($_GET["orderby"])) {
    $oLink = '<a href="'.$APP->BASEURL.'/omattiedot.php?ID=49&order=';
    $oLink.= (empty($_GET["order"]) || $_GET["order"] == "down") ? 'up"><img src="'.$APP->BASEURL.'/view/images/icons/arrow_up.png"></a>' : 'down"><img src="'.$APP->BASEURL.'/view/images/icons/arrow_down.png"></a>';
}

if (sizeof($users) > 0)
{
    $pages = $navi->generate($users);

} */?>
                <tr>
                    <th>Tunnus</th>
                    <th>Nimi</th>
                    <th>Postiosoite</th>
                    <th>Postinumero</th>
                    <th>Postitoimipaikka</th>
                    <th>Sähköposti</th>
                    <th>Ryhmä</th>
                    <th>Toiminnot</th>
                </tr>
            </thead>
            <tbody>
<?php
if (sizeof($users)) {
    foreach ($users as $user) { ?>
                <tr>
                    <td><?php print $user["username"]; ?></td>
                    <td><?php print $user["firstname"] . " " . $user["lastname"]; ?></td>
                    <td><?php print $user["katuosoite"]; ?></td>
                    <td><?php print $user["postinumero"]; ?></td>
                    <td><?php print $user["kaupunki"]; ?></td>
                    <td><?php print $user["email"]; ?></td>
                    <td><?php print $user["rolename"]; ?></td>
                    <td><?php
        if ($USER->ID != $user["userid"]) { ?>
                        <a href="<?php print $APP->BASEURL; ?>/viestit.php?ID=32&user_id=<?php print $user["userid"]; ?>" title="Lähetä viesti"><img src="<?php print $APP->BASEURL; ?>/view/images/icons/notesend.png"></a>
                        <a href="<?php print $APP->BASEURL; ?>/viestit.php?ID=36&user_id=<?php print $user["userid"]; ?>" title="Lähetä tekstiviesti"><img src="<?php print $APP->BASEURL; ?>/view/images/icons/smssend.png"></a>
<?php
        }
            ?></td>
                </tr>

<?php
    }
}
else { ?>
                <tr>
                    <td colspan="8">Ei tallennettuja käyttäjiä.</td>
                </tr>
            
<?php
} ?>
            </tbody>
        </table>
        </div>
    </div>

</div>
<link href="<?php print $APP->BASEURL;?>/view/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $APP->BASEURL;?>/view/css/jquery.dataTables_themeroller.css" rel="stylesheet" type="text/css"/>

<script src="<?php print $APP->BASEURL;?>/view/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $('#data_table').dataTable({
        bPaginate: true,
        aaSorting: [],
        bJQueryUI: true,
        aLengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'kaikki']],
        iDisplayLength: 10,
        oLanguage: {
            sSearch: 'Suodata:',
            sInfo: 'Näytettäviä käyttäjiä: _TOTAL_',
            sInfoEmpty: 'Ei näytettäviä käyttäjiä',
            sZeroRecords: 'Ei hakutuloksia',
            sInfoFiltered: ' (kaikkiaan yhteensä _MAX_ käyttäjää)',
            sLengthMenu: 'Näytä _MENU_ käyttäjää'
        }
    });
});    
</script>
