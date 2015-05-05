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
<a href="http://www.adiipl.com/opendelight/docs/event.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float:right;margin-right:2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=21&CtrID=<?php print $_GET['CtrID'];?>" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div class="formSpacing">
<form name="frmEventAdd" id="frmEventAdd" action="<?php print $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>" method="post">
<div class="divSpacing" style="width:700px;">
<label>Event Name, $APP->IDN <span>(A-Z a-z 0-9, no white space, no special characters, can have dashes or underscores)</span></label>
<input type="text" name="txtEventName" value="<?php if (isset($_POST[txtEventName])) print $_POST[txtEventName];?>" class="textboxNormal required" style="width:300px;"/>
</div>
<div style="float:right;clear:none;width:120px;">
<input type="checkbox"  name="chkDefault" id="chkDefault" value="1" <?php if ($_POST[chkDefault] == '1') print 'checked="checked"';?> /><label for="chkDefault" class="makeBold">Set as Default</label>
</div>
<div class="divSpacing">
<label for="chkRole">Roles <span>(Which user roles will be allowed to access this event?)</span></label>
<div style="float:left;clear:both;width:100%;">
<?php $aRoles  	= $oEvent->getRoles();
$i = 0;
foreach($aRoles as $aRow) { 
$i++;
?>
<div style="float:left;margin-bottom:10px;">
<input style="float:left;" type="checkbox" name="chkRole<?php print $i;?>" id="chkRole<?php print $i;?>" value="<?php print stripslashes($aRow['roleid']);?>" <?php if ($aRow['rolename'] == 'Administrator') print 'checked="checked" disabled="disabled"'; else if ($_POST['chkRole'.$i] == $aRow['roleid']) print 'checked="checked"';?> />
<label class="checkboxlabel" for="chkRole<?php print $i;?>"><?php print stripslashes($aRow['rolename']);?></label>
</div>
<?php } ?>
</div>
</div>

<div class="divSpacing">
<label>Event Verifier <span>(Additional boolean expression that needs to be satisfied to make the event executed successfully)</span></label>
<input type="text" name="txtEventVerifier"  id="txtEventVerifier" value="<?php if (isset($_POST[txtEventVerifier])) print $_POST[txtEventVerifier];?>" class="textboxNormal" style="width:750px;"/>
</div>

<div class="divSpacing">
<label>Form Rules <span>(An array with information about form validation and sanitization as required)</span></label>
<input type="text" name="txtFormrules"  id="txtFormrules" value="<?php if (isset($_POST[txtFormrules])) print $_POST[txtFormrules];?>" class="textboxNormal" style="width:750px;"/>
</div>
<div class="divSpacing">
<label>Code for Calling Application Objects and Including External Scripts</label>
<textarea name="taBusinessLogic" id="textarea_1" class="textareaNormal" rows="15" cols="15" style="width:750px;"><?php if (isset($_POST[hidTextArea])) print $_POST[hidTextArea];?></textarea>
</div>

<div class="divSpacing">
<label>View Page Parts <span>(A comma-separated ordered list of HTML files to be called for creating UI)</span></label>
<input type="text" name="txtViewParts"  id="txtViewParts" value="<?php if (isset($_POST[txtViewParts])) print $_POST[txtViewParts];?>" class="textboxNormal" style="width:750px;"/>
</div>

<div class="divSpacing">
<?php 
$j = 0;
foreach($aPageVarDetails as $aRow) {
$j++;
?>
<div style="float:left;width:250px;">
<label>Web Page Variables: <span style="font-family:courier;font-style:normal;"><?php print stripslashes($aRow[pagevarkey]);?></span></label><br/>
<input type="text" name="txtPagevars<?php print $j;?>"  id="txtPagevars<?php print $j;?>" value="<?php if (isset($_POST['txtPagevars'.$j])) print $_POST['txtPagevars'.$j];?>" class="textboxNormal" style="width:230px;"/>
</div>
<?php } ?>
</div>
<div class="divSpacing">
<input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
<input type="hidden" name="hidCtrlID" id="hidCtrlID" value="<?php print $_GET['CtrID']; ?>" />
<input type="hidden" name="hidNumRoles" id="hidNumRoles" value="<?php print $i; ?>" />
<input type="hidden" name="hidNumPagevars" id="hidNumPagevars" value="<?php print count($aPageVarDetails); ?>" />
<input type="hidden" name="hidTextArea" id="hidTextArea" value="" />
<a href="javascript:void(0);" onclick="javascript:addEvent();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</div>
</form>
</div>

<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function addEvent()
{
	document.getElementById('frmEventAdd').hidTextArea.value = editAreaLoader.getValue("textarea_1");
 	$("#frmEventAdd").validate();
 	$("#frmEventAdd").submit();
}

editAreaLoader.init({
	id : "textarea_1"		// textarea id
	,syntax: "php"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
	
});
</script>
