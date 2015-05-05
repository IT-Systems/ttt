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
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=21&CtrID=<?php print $_GET['CtrID'];?>" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div class="formSpacing">
<form name="frmEventEdit" id="frmEventEdit" action="<?php print $_SERVER['PHP_SELF'].'?ID=25&RecID='.stripslashes($aEachEventDetails[0]['eventid']).'&CtrID='.stripslashes($aEachEventDetails[0]['ctrid']);?>" method="post">
<div class="divSpacing" style="width:700px;">
<label>Event Name, $APP->IDN <span>(A-Z a-z 0-9, no white space, no special characters, can have dashes or underscores)</span></label>
<input type="text" name="txtEventName" value="<?php if($_POST['txtEventName']){ print $_POST['txtEventName']; }else{ print stripslashes($aEachEventDetails[0]['eventname']); } ?>" class="textboxNormal required" style="width:250px;" readonly="readonly" />
</div>
<div style="float:right;clear:none;width:120px;">
<input type="checkbox"  <?php if(stripslashes($aEachEventDetails[0]['eventid']) == stripslashes($aCtrDetails[0]['defaulteventid'])) print 'checked="checked"';?> name="chkDefault" id="chkDefault" value="1" /><label for="chkDefault" class="makeBold">Set as default</label>
</div>

<div class="divSpacing">
<label for="chkRole">Roles <span>(Which user roles will be allowed to access this event?)</span></label>
<div style="float:left;clear:both;width:100%;">
<?php $aRoles  	= $oEvent->getRoles();
$i = 0;
foreach($aRoles as $aRow) { 
$i++;
$aRolesData = explode(',', stripslashes($aEachEventDetails[0]['roles']));

?>
<div style="float:left;margin-bottom:10px;">
<input type="checkbox" style="float:left;" <?php if ($aRow['rolename'] == 'Administrator') print 'checked="checked" disabled="disabled"'; else if(in_array(stripslashes($aRow['roleid']),$aRolesData)) print 'checked="checked"';?> name="chkRole<?php print $i;?>" id="chkRole<?php print $i;?>" value="<?php print stripslashes($aRow['roleid']);?>" />
<label class="checkboxlabel" for="chkRole<?php print $i;?>"><?php print stripslashes($aRow['rolename']);?></label>
</div>
<?php } ?>
</div>
</div>

<div class="divSpacing">
<label>Event Verifier <span>(Additional boolean expression that needs to be satisfied to make the event executed successfully)</span></label>
<input type="text" name="txtEventVerifier"  id="txtEventVerifier" value="<?php if($_POST['txtEventVerifier']){ print htmlentities($_POST['txtEventVerifier']); }else{ print htmlentities($aEachEventDetails[0]['eventverifier']); } ?>" class="textboxNormal" style="width:750px;"/>
</div>

<div class="divSpacing">
<label>Form Rules <span>(An array with information about form validation and sanitization as required)</span></label>
<input type="text" name="txtFormrules"  id="txtFormrules" value="<?php if($_POST['txtFormrules']){ print htmlentities($_POST['txtFormrules']); }else{ print htmlentities($aEachEventDetails[0]['formrules']); } ?>" class="textboxNormal" style="width:750px;"/>
</div>
<div class="divSpacing">
<label>Code for Calling Application Objects and Including External Scripts</label>
<textarea name="taBusinessLogic" id="textarea_1" class="textareaNormal" rows="15" cols="15" style="width:750px;"><?php if($_POST['taBusinessLogic']) print print $_POST['taBusinessLogic']; else print stripslashes($aEachEventDetails[0]['blcode']); ?></textarea>
</div>

<div class="divSpacing">
<label>View Page Parts <span>(A comma-separated ordered list of HTML files to be called for creating UI)</span></label>
<input type="text" name="txtViewParts"  id="txtViewParts" value="<?php if($_POST['txtViewParts']){ print $_POST['txtViewParts']; }else{ print stripslashes($aEachEventDetails[0]['viewparts']); } ?>" class="textboxNormal" style="width:750px;"/>
</div>

<div class="divSpacing">
<?php 
$j = 0;
$sPagevars = stripslashes($aEachEventDetails[0]['pagevars']);
$aPagevars = explode("^", $sPagevars);
foreach($aPageVarDetails as $aRow) {
$j++;
?>
<div style="float:left;width:250px;">
<label>Web Page Variables: <span style="font-family:courier;font-style:normal;"><?php print stripslashes($aRow[pagevarkey]);?></span></label>
<input type="text" name="txtPagevars<?php print $j;?>"  id="txtPagevars<?php print $j;?>" class="textboxNormal" style="width:230px;" value="<?php print $aPagevars[$j - 1];?>" />
</div>
<?php }?>
</div>


<div class="divSpacing">
<input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
<input type="hidden" name="hidCtrlID" id="hidCtrlID" value="<?php print $_GET['CtrID']; ?>" />
<input type="hidden" name="hidNumRoles" id="hidNumRoles" value="<?php print $i; ?>" />
<input type="hidden" name="hidNumPagevars" id="hidNumPagevars" value="<?php print count($aPageVarDetails); ?>" />
<input type="hidden" name="hidTextArea" id="hidTextArea" value="" />
<a href="javascript:void(0);" onclick="javascript:editEvent();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function editEvent()
{
	document.getElementById('frmEventEdit').hidTextArea.value = editAreaLoader.getValue("textarea_1");
 	$("#frmEventEdit").validate();
 	$("#frmEventEdit").submit();
}

editAreaLoader.init({
	id : "textarea_1"		// textarea id
	,syntax: "css"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
});
</script>
