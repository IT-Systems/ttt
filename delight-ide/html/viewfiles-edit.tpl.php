<?php
if ($aMsg[0] == 1)
{
	$aMsgClass[0] = "ui-state-error ui-corner-all ui-message-box";
    $aMsgClass[1] = "ui-icon ui-icon-alert";
}
else
{
	$aMsgClass[0] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsgClass[1] = "ui-icon ui-icon-info";
}
$sMsgText = $aMsg[1];
?>
<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/view.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float:right;margin-right:2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=40&VFT=<?php print $_GET['VFT'];?>" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div class="formSpacing">
<form name="frmViewFileEdit" id="frmViewFileEdit" action="<?php print $_SERVER['PHP_SELF'].'?ID=42&VFT='.$_GET['VFT'];?>" method="post">
<div class="divSpacing">
<label><?php print $sFileTypeTexts;?> Name <span>(File name should have proper file extension included. Filename Cannot be edited later.)</span></label>
<input type="text" name="txtFileName" id="txtFileName" value="<?php if($_POST['txtFileName']){ print $_POST['txtFileName']; }else{ print stripslashes($sFile); } ?>" class="textboxNormal required" style="width:250px;" readonly="readonly" />
</div>
<div class="divSpacing">
<label><?php print $sFileTypeTexts;?> File Code <span>(Entire content is editable)</span></label>
<textarea name="taCodeEdit" id="textarea_1" class="textareaNormal" rows="35" cols="" style="width:750px;"><?php if(stripslashes($sFileContent)) { print stripslashes($sFileContent); }else{ print $_POST['taCodeEdit']; } ?></textarea>
</div>
<div class="divSpacing">
<input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
<input type="hidden" name="hidClassName" id="hidClassName" value="<?php print stripslashes($sClass);?>" />
<input type="hidden" name="hidTextArea" id="hidTextArea" value="" />
<a href="javascript:void(0);" onclick="javascript:editViewFile();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</form>
</div>

</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function editViewFile()
{
	document.getElementById('frmViewFileEdit').hidTextArea.value = editAreaLoader.getValue("textarea_1");
 	$("#frmViewFileEdit").validate();
 	$("#frmViewFileEdit").submit();
}

editAreaLoader.init({
	id : "textarea_1"		// textarea id
	,syntax: "css"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
});
</script>