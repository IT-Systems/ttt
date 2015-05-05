<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/users.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=63" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>

<div class="formSpacing">
<form id="frmUserAdd" method="post" action="<?php print $_SERVER['PHP_SELF'].'?ID=64';?>" >
<div class="divSpacing">
<label>First Name</label>
<input type="text" name="txtFirstName" id="txtFirstName" value="<?php if (isset($_POST[txtFirstName])) print $_POST[txtFirstName];?>" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Last Name</label>
<input type="text" name="txtLastName" id="txtLastName" value="<?php if (isset($_POST[txtLastName])) print $_POST[txtLastName];?>" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Username <span>(A-Z a-z 0-9, and no white space, no special characters, unique)</span></label>
<input type="text" name="txtUsername" id="txtUsername" value="<?php if (isset($_POST[txtUsername])) print $_POST[txtUsername];?>" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Email <span>(unique)</span></label>
<input type="text" name="txtEmail" id="txtEmail" value="<?php if (isset($_POST[txtEmail])) print $_POST[txtEmail];?>" class="textboxNormal required email" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Password</label>
<input type="password" name="txtPassword" id="txtPassword" value="<?php if (isset($_POST[txtPassword])) print $_POST[txtPassword];?>" class="textboxNormal required" style="width:150px;"/>
</div>
<div class="divSpacing">
<label>User Role</label>
<select name="selRole" id="selRole" class="dropdown required" style="float:left;clear:both;width:150px;">
<option value="">Select Role</option>
<?php $aRoles  	= $oUsers->getRoles();
foreach($aRoles as $aRow) { ?>
<option value="<?php print stripslashes($aRow['roleid']);?>">
<?php print stripslashes($aRow['rolename']);?>
</option>
<?php } ?>
</select>
</div>
<div class="divSpacing">
<input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:validateUserAdd();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</form>
</div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->

<script type="text/javascript">
function validateUserAdd()
 {
 	$("#frmUserAdd").validate();
 	$("#frmUserAdd").submit();
 }
 </script>		