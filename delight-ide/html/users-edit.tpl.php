<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/users.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=63" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>

<div class="formSpacing">
<form name="frmUserEdit" id="frmUserEdit" action="<?php print $_SERVER['PHP_SELF'].'?ID=65&RecID='.stripslashes($aEachUsersDetails[0]['userid']);?>" method="post">
<div class="divSpacing">
<label>First Name</label>
<input type="text" name="txtFirstName" id="txtFirstName" value="<?php if($_POST['txtFirstName']){ print $_POST['txtFirstName']; }else{ print stripslashes($aEachUsersDetails[0]['firstname']); } ?>" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Last Name</label>
<input type="text" name="txtLastName" id="txtLastName" value="<?php if($_POST['txtLastName']){ print $_POST['txtLastName']; }else{ print stripslashes($aEachUsersDetails[0]['lastname']); } ?>" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Username <span>(A-Z a-z 0-9, and no white space, no special characters, unique)</span></label>
<input type="text" name="txtUsername" id="txtUsername" value="<?php if($_POST['txtUsername']){ print $_POST['txtUsername']; }else{ print stripslashes($aEachUsersDetails[0]['username']); } ?>" class="textboxNormal required" style="width:350px;" <?php if($aEachUsersDetails[0]['username'] == 'admin') { print 'readonly="readonly"';} ?>/>
</div>
<div class="divSpacing">
<label>Email <span>(unique)</span></label>
<input type="text" name="txtEmail" id="txtEmail" value="<?php if($_POST['txtEmail']){ print $_POST['txtEmail']; }else{ print stripslashes($aEachUsersDetails[0]['email']); } ?>" class="textboxNormal required email" style="width:350px;"/>
</div>
<?php if($aEachUsersDetails[0]['username'] != 'admin') { ?>
<div class="divSpacing">
<label>Password <span>(Fill up only if you want to change the password, else leave it blank)</span></label>
<input type="text" name="txtPassword" id="txtPassword" value="" class="textboxNormal" style="width:150px;"/>
</div>
<div class="divSpacing">
<label class="makeBold">Role</label><br/>
<select name="selRole" id="selRole" class="dropdown required" style="float:left;clear:both;width:150px;">
<option value="">Select Role</option>
<?php $aRoles  	= $oUsers->getRoles();
foreach($aRoles as $aRow) { ?>
<option <?php if($_POST['selRole'] == $aRow['roleid'] || stripslashes($aEachUsersDetails[0]['roleid']) == stripslashes($aRow['roleid']) ) print 'selected="selected"';?> value="<?php print stripslashes($aRow['roleid']);?>">
<?php print stripslashes($aRow['rolename']);?>
</option>
<?php } ?>
</select>
</div>
<?php } ?>
<div class="divSpacing">
<input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:editUser();"title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</form>
</div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
 function editUser()
 {
 	$("#frmUserEdit").validate();
 	$("#frmUserEdit").submit();
 }
 </script>