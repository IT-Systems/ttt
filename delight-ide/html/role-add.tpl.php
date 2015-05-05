<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/users.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right:2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=70" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div class="formSpacing">
<form name="frmRoleAdd" id="frmRoleAdd" action="<?php print $_SERVER['PHP_SELF'].'?ID=71';?>" method="post">
<div class="divSpacing">
<label>Role Name</label>
<input type="text" name="txtRoleName" id="txtRoleName" value="" class="textboxNormal required" style="width:200px;"/>
</div>
<div class="divSpacing">
<label>Default Controller <span>(Choose the Controller where any user with this role will head after signing in)</span></label>
<select name="selController" id="selController" class="dropdown required" style="float:left;clear:both;width:138px;" onChange="javascript:selectCtrl();">
<option value="">Select Controller</option>
<?php $aControllers  	= $oRoles->getControllers();
foreach($aControllers as $aRow) { ?>
<option value="<?php print stripslashes($aRow['ctrid']);?>">
<?php print stripslashes($aRow['ctrname']);?>
</option>
<?php } ?>
</select>
</div>
<div id="eventdiv" class="divSpacing">
<label>Default Event <span>(Choose the Event that will be instantiated by default after signing in)</span></label>
<select name="selEvent" id="selEvent" class="dropdown required" style="float:left;clear:both;width:138px;">
<option value="">Select Event</option>
</select>
</div>
<div class="divSpacing">
<input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:addRole();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function addRole()
{
 	$("#frmRoleAdd").validate(); 
 	$("#frmRoleAdd").submit();
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