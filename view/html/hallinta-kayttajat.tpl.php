<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') {
    $aMsg[0] = "Uusi käyttäjä <strong>{$_GET['PC']}</strong> lisättiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} else if ($_GET['PF'] == '2') {
    $aMsg[0] = "Käyttäjän <strong>{$_GET['PC']}</strong> tiedot muokattiin.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} else if ($_GET['PF'] == '3') {
    $aMsg[0] = "Käyttäjä <strong>{$_GET['PC']}</strong> poistettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} else if ($_GET['PF'] == '4') {
    $aMsg[0] = "Valitun käyttäjän tila muutettiin onnistuneesti.";
    $aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-info";
} else if ($_GET['PF'] == '5') {
    $aMsg[0] = "Valitun käyttäjän tilaa ei onnistuttu muuttamaan.";
    $aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
    $aMsg[2] = "ui-icon ui-icon-alert";
}
?>

<?php require_once('view/html/hallinta-napit.tpl.php'); ?>


<div class="kp_div">
    <div class="ilmoitus <?php print $aMsg[1]; ?>">
        <span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
        <?php print $aMsg[0] ?>
    </div>
    <h2>Lista käyttäjistä</h2>
    <div class="tiedot_osio">

        <a href="<?php print $_SERVER['PHP_SELF'] ?>?ID=18" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Lisää käyttäjä</a><br><br>

        <div id="data_table_container">
            <table id="data_table">
                <thead>
                    <tr>
                        <th>Etunimi</th>
                        <th>Sukunimi</th>
                        <th>Käyttäjänimi</th>
                        <th>Sähköposti</th>
<!--                        <th>Viimeksi kirjautunut</th>-->
                        <th>Rooli</th>
                        <th>Kurssi(t)</th>
                        <th>Toiminnot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($aUsersDetails) > 0) {
                        foreach ($aUsersDetails as $aRow) {
                            if ($aRow[firstname])
                                $sFullName = stripslashes($aRow[firstname]) . ' ' . stripslashes($aRow[lastname]);
                            else
                                $sFullName = 'N/A';
                            if ($aRow[userstatus] == 1)
                                $sUserStatus = 'Active';
                            else
                                $sUserStatus = 'Inactive';
                            $sRoleName = $hal->getRoleName(stripslashes($aRow[roleid]));

                            $kurssit = $hal->haeHenkilonKurssit($aRow["userid"]);
                            $kurssit_out = "";
                            foreach ($kurssit as $kurssi) $kurssit_out.= $kurssi["kurssi_nimi"].", ";
                            $kurssit_out = substr($kurssit_out, 0, -2);
                            ?>
                            <tr>
                                <td ><?php print stripslashes($aRow[firstname]); ?></td>
                                <td ><?php print stripslashes($aRow[lastname]); ?></td>
                                <td ><?php print stripslashes($aRow[username]); ?></td>

                                <td ><?php print stripslashes($aRow[email]); ?></td>
<!--                                <td ><?php print $hal->dbTimeToFiTime(stripslashes($aRow[lastlogin])); ?></td>-->
                                <td ><?php print $sRoleName; ?></td>
                                <td><?php print $kurssit_out; ?></td>

                                <td  style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'] . '?ID=19&RecID=' . stripslashes($aRow['userid']); ?>"><img src="<?php print $APP->BASEURL; ?>/view/images/icon-edit.png" title="Edit User" alt="Edit User" class="icon"/></a>
                                    <?php if ($aRow[username] != 'admin') { ?>
                                        <a href="<?php print $_SERVER['PHP_SELF'] . '?ID=20&RecID=' . stripslashes($aRow['userid']); ?>" ><img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross.png" title="Delete User" alt="Delete User" class="icon" onclick="javascript:return confirm('Do you want to delete?');"/></a>
                                    <?php } else { ?>
                                        <img src="<?php print $APP->BASEURL; ?>/view/images/icon-cross-no-delete.png" title="Cannot Delete Primary User" alt="Cannot Delete Primary User" class="icon"/>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8"  style="text-align:center;">No user found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <p><em><strong>admin</strong> on vastuussa käyttäjienhallinnasta eikä häntä voi poistaa.</em></p>
    </div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
    function changeStatus(iStatus, iUserId)
    {
        $.ajax({
            type: "POST",
            url: "<?php print $APP->BASEURL; ?>/delight-ide/index.php?ID=109",
            data: "requeststatus="+iStatus+"&requestuserid="+iUserId,
            success: function(msg)
            {
                if(msg == "1") self.location.href = '<?php print $APP->BASEURL; ?>/delight-ide/index.php?ID=63&PF=4';
                else if(msg == "2") self.location.href = '<?php print $APP->BASEURL; ?>/delight-ide/index.php?ID=63&PF=5';
            }
        });
    }
</script>

<link href="<?php print $APP->BASEURL; ?>/view/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $APP->BASEURL; ?>/view/css/jquery.dataTables_themeroller.css" rel="stylesheet" type="text/css"/>

<script src="<?php print $APP->BASEURL; ?>/view/js/jquery.dataTables.min.js" type="text/javascript"></script>
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
            },
            aoColumnDefs: [ { bSortable: false, aTargets: [6] } ]
        });
    });    
</script>