<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/users.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=70" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div class="formSpacing">
<form name="frmRoleEdit" id="frmRoleEdit" action="<?php print $_SERVER['PHP_SELF'].'?ID=72&RecID='.stripslashes($aEachRoleDetails[0]['roleid']);?>" method="post">
<div class="divSpacing">
<label>Role Name</label>
<input type="text" name="txtRoleName" id="txtRoleName" value="<?php if($_POST['txtRoleName']){ print $_POST['txtRoleName']; }else{ print stripslashes($aEachRoleDetails[0]['rolename']); } ?>" class="textboxNormal required" style="width:200px;" <?php if($aEachRoleDetails[0]['rolename'] == 'Administrator') { print 'readonly="readonly"';} ?>/>
</div>
<div class="divSpacing">
<label>Default Controller <span>(Choose the Controller where any user with this role will head after signing in)</span></label>
<select name="selController" id="selController" class="dropdown required" style="float:left;clear:both;width:138px;" onChange="javascript:selectCtrl();">
<option value="">Select a Controller</option>
<?php $aControllers  	= $oRoles->getControllers();
foreach($aControllers as $aRow) { ?>
<option <?php if($_POST['selController'] == stripslashes($aRow['ctrid']) || stripslashes($aEachRoleDetails[0]['defaultctrid']) == stripslashes($aRow['ctrid'])) print 'selected="selected"';?> value="<?php print stripslashes($aRow['ctrid']);?>">
<?php print stripslashes($aRow['ctrname']);?>
</option>
<?php } ?>
</select>
</div>
<div id="eventdiv" class="divSpacing">
<label>Default Event <span>(Choose the Event that will be instantiated by default after signing in)</span></label>
<select name="selEvent" id="selEvent" class="dropdown required" style="float:left;clear:both;width:138px;">
<option value="">Select Event</option>
<?php $aEvents  	= $oRoles->listEventPage($aEachRoleDetails[0]['defaultctrid']);
foreach($aEvents as $aRow) { ?>
<option <?php if($_POST['selEvent'] == stripslashes($aRow['eventid']) || stripslashes($aEachRoleDetails[0]['defaulteventid']) == stripslashes($aRow['eventid'])) print 'selected="selected"';?> value="<?php print stripslashes($aRow['eventid']);?>">
<?php print stripslashes($aRow['eventname']);?>
</option>
<?php } ?>
</select>
</div>
<div class="divSpacing">
<input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:editRole();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function editRole()
{
 	$("#frmRoleEdit").validate();
 	$("#frmRoleEdit").submit();
}

function selectCtrl()
{	
	 $.ajax({
		   type: "POST",
		   url: "<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=105",
		   data: "requestctrlid="+document.getElementById('selController').value,
		   success: function(msg)
		   {
		   	  if(msg != "") document.getElementById('eventdiv').innerHTML = msg;		   
	       }
		 });
}
 </script>