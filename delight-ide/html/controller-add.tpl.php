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
<a href="http://www.adiipl.com/opendelight/docs/controller.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div class="formSpacing">
<form name="frmControllerAdd" id="frmControllerAdd" action="<?php print $_SERVER['PHP_SELF'].'?ID=22';?>" method="post">
<div class="divSpacing">
<label>Controller File Name <span>(A-Z a-z 0-9, no white space, no special characters, should have .php extension)</span></label>
<input type="text" name="txtName"  id="txtName" value="<?php if (isset($_POST[txtName])) print $_POST[txtName];?>" class="textboxNormal required space" style="width:250px;"/>
</div>
<div class="divSpacing">
<label for="radIsPublic">Access Privilege <span>(Determines if a signin is required to access the controller or not)</span></label>
<input id="radPublicYes" name="radPublic" class="radioItems required" type="radio"  value="1" style="margin-left:0px;float:left;clear:both;" onclick="javascript:displayController('0');"/>
<label for="radPublicYes" class="radiolabel">Public</label>
<input id="radPublicNo" style="float:left;margin-left:25px;" name="radPublic" class="radioItems required" type="radio"  value="0" style="margin-left:0px;" onclick="javascript:displayController('1');"/>
<label for="radPublicNo" class="radiolabel">Private</label>
<label for="radPublic" class="error" style="display:none;">Please select a radio button.</label>
</div>

<div class="divSpacing" id="dispController" style="display:none;">
<label>Select Sign In Controller</label>
<select name="selController" id="selController" class="dropdown" style="float:left;clear:both;width:200px;">
<option value="">Select Sign In Controller</option>
<?php 
foreach($aPublicControllers as $aRow) { ?>
<option <?php if($_POST['selController'] == stripslashes($aRow['ctrid'])) print 'selected="selected"';?> value="<?php print stripslashes($aRow['ctrid']);?>">
<?php print stripslashes($aRow['ctrname']);?>
</option>
<?php } ?>
</select>
</div>
<div class="divSpacing">
<input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:addController();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function addController()
{
 	$("#frmControllerAdd").validate();
 	$("#frmControllerAdd").submit();
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