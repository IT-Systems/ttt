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
<a href="http://www.adiipl.com/delight/docs/controller.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div class="formSpacing">
<form name="frmControllerEdit" id="frmControllerEdit" action="<?php print $_SERVER['PHP_SELF'].'?ID=23&RecID='.stripslashes($aEachCtrDetails[0]['ctrid']);?>" method="post">
<div class="divSpacing">
<label>Controller File Name <span>(A-Z a-z 0-9, no white space, no special characters, should have .php extension)</span></label>
<input type="text" name="txtName"  id="txtName" value="<?php if(isset($_POST['txtName'])){ print $_POST['txtName']; }else{ print stripslashes($aEachCtrDetails[0]['ctrname']); } ?>" class="textboxNormal required space" style="width:250px;font-size:11px;"/>
</div>
<?php
$aEvents = $oControllers->getControllerEvents(stripslashes($aEachCtrDetails[0]['ctrid']));
if(count($aEvents) > 0){?>
<div class="divSpacing">
<label class="makeBold">Default Event</label><br/>
<select name="selEvent" id="selEvent" class="dropdown required" style="float:left;clear:both;width:200px;">
<option value="">Select Event</option>
<?php 
foreach($aEvents as $aRow) { ?>
<option <?php if($_POST['selEvent'] == stripslashes($aRow['eventid']) || stripslashes($aEachCtrDetails[0]['defaulteventid']) == stripslashes($aRow['eventid'])) print 'selected="selected"';?> value="<?php print stripslashes($aRow['eventid']);?>">
<?php print stripslashes($aRow['eventname']);?>
</option>
<?php } ?>
</select>
</div>
<?php }?>
<div class="divSpacing">
<label>Status</label>
<select name="selStatus" id="selStatus" class="dropdown required" style="float:left;clear:both;width:110px;">
<option value="">Select Status</option>
<option <?php if($_POST['selStatus'] == 0 || stripslashes($aEachCtrDetails[0]['ctrstatus']) == 0) print 'selected="selected"';?> value="0">
Inactive
</option>
<option <?php if($_POST['selStatus'] == 1 || stripslashes($aEachCtrDetails[0]['ctrstatus']) == 1) print 'selected="selected"';?> value="1">
Active
</option>
</select>
</div>

<div class="divSpacing">
<label for="radIsPublic">Access Privilege</label>
<input id="radPublicYes"  name="radPublic" class="required" type="radio"  onclick="javascript:displayController('0');" style="margin-left:0px;float:left;clear:both;" value="1" <?php if($aEachCtrDetails[0]['ispublic'] == 1) print 'checked="checked"';?>/>
<label for="radPublicYes"  class="radiolabel">Public</label>
<input id="radPublicNo" name="radPublic" class="radioItems required" onclick="javascript:displayController('1');" type="radio"  style="float:left;margin-left:25px;" value="0" <?php if($aEachCtrDetails[0]['ispublic'] == 0) print 'checked="checked"';?>/>
<label for="radPublicNo" class="radiolabel">Private</label>
<label for="radPublic" class="error" style="display:none;">Please select a radio button.</label>
</div>
<?php 
if(stripslashes($aEachCtrDetails[0]['ispublic']) == 0) $sDisplay = "block";
else $sDisplay = "none";
?>
<div class="divSpacing" style="display:<?php print $sDisplay;?>;" id="dispController">
<label>Select Sign In Controller</label>
<select name="selController" id="selController" class="dropdown" style="float:left;clear:both;width:200px;">
<option value="">Select Sign In Controller</option>
<?php
foreach($aPublicControllers as $aRow) { ?>
<option <?php if($_POST['selController'] == stripslashes($aRow['ctrid']) || stripslashes($aEachCtrDetails[0]['signinctrid']) == stripslashes($aRow['ctrid'])) print 'selected="selected"';?> value="<?php print stripslashes($aRow['ctrid']);?>">
<?php print stripslashes($aRow['ctrname']);?>
</option>
<?php } ?>
</select>
</div>
<div class="divSpacing">
<input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:editController();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>

</div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function editController()
{
 	$("#frmControllerEdit").validate();
 	$("#frmControllerEdit").submit();
}

function displayController(iVal)
{
  if(iVal == 1)
  {
	  document.getElementById('dispController').style.display = "block";
	  document.getElementById('selController').className = "dropdown required";
  }
  else 
  {
	  document.getElementById('dispController').style.display = "none";
	  document.getElementById('selController').className = "dropdown";
  }
}
</script>