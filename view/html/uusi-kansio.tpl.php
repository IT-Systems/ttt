<?php
$backurl = $APP->BASEURL . "/viestit.php?ID=31";
if (!empty($_GET["FID"]))
    $backurl.= "&FID=" . $_GET["FID"];
?>
<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">
<h2><?php print $APP->PAGEVARS["HEADERTEXT"]; ?></h2>
    
<div class="tiedot_osio">
        <form action="<?php print $APP->BASEURL ?>/viestit.php?ID=33" method="POST" name="frmFolder" id="frmFolder">
            <table>
                <tr>
                    <td style="width:50px">Nimi:</td>
                    <td><input type="text" name="txtFolderName" id="name" value="<?php print $_POST["txtFolderName"]; ?>" class="textboxNormal required"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div style="float:left;">
                            <input type="hidden" name="hidNewFolder" id="hidNewFolder" value="1" />
                            <a href="javascript:void(0);" onclick="javascript:uusiKansio();"title="Submit" class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn"><span class="ui-icon ui-icon-disk"></span>Tallenna</a>
                            <a class="ui-od-button-with-icon ui-state-default ui-corner-all lomake-btn" href="<?php print $backurl; ?>">
                                <span class="ui-icon ui-icon-cancel"></span>Peruuta
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript">
    function uusiKansio()
    {
        $("#frmFolder").validate();
        $("#frmFolder").submit();
    }
</script>