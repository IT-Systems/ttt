<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1')
{
	$aMsg[0] = "The new user <strong>{$_GET['PC']}</strong> has been successfully added.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2')
{
	$aMsg[0] = "The user <strong>{$_GET['PC']}</strong> has successfully been edited.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '3')
{
	$aMsg[0] = "The user <strong>{$_GET['PC']}</strong> has successfully been deleted.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '4')
{
	$aMsg[0] = "The status of the chosen user has successfully been changed.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '5')
{
	$aMsg[0] = "The status of the chosen user could not been changed.";
	$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-alert";
}
else
{
	$aMsg[0] = "The list of users have been presented below. Only user with username <strong>admin</strong> is responsible for IDE management.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
?>
<a href="http://www.adiipl.com/opendelight/docs/users.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div id="ui-od-button-spacing">
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=64" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Add User</a>
</div>

<table width="100%" cellspacing="0" cellpadding="0" class="tableData">
<thead>
 <tr>
	<th>Name</th>
	<th>Username</th>
	<th>Email</th>
	<th>Last Login</th>
	<th>User Status</th>
	<th>User Role</th>
	<th colspan="2">Action</th>
 </tr>
</thead>
<tbody>
<?php 
if(count($aUsersDetails) > 0) {
foreach($aUsersDetails as $aRow) {
    if($aRow[firstname]) $sFullName = stripslashes($aRow[firstname]).' '.stripslashes($aRow[lastname]);
    else $sFullName = 'N/A';
    if($aRow[userstatus] == 1) $sUserStatus = 'Active';
    else $sUserStatus = 'Inactive';    
    $sRoleName   = $oUsers->getRoleName(stripslashes($aRow[roleid]));    
?>
 <tr>
	<td class="tableContent"><?php print $sFullName; ?></td>
	<td class="tableContent"><?php print stripslashes($aRow[username]); ?></td>
	<td class="tableContent"><?php print stripslashes($aRow[email]); ?></td>
	<td class="tableContent"><?php print stripslashes($aRow[lastlogin]); ?></td>	
	<td class="tableContent">
		<?php if($aRow[userstatus] == 1) { ?>
		<div style="width:16px;float:left;margin-top:0px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-active.png" title="Active" alt="Active" /></div><?php if($aRow[username] != 'admin') { ?>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);"  onclick="javascript:changeStatus('<?php print $aRow[userstatus]; ?>', '<?php print $aRow[userid]; ?>');" ><span style="font-size:11px;">Change</span></a>)<?php } ?>
		<?php } elseif($aRow[userstatus] == 0) { ?>
		<div style="width:16px;float:left;margin-top:0px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-inactive.png" title="Inactive" alt="Inactive" /></div> <?php if($aRow[username] != 'admin') { ?>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="javascript:changeStatus('<?php print $aRow[userstatus]; ?>', '<?php print $aRow[userid]; ?>');" ><span style="font-size:11px;">Change</span></a>)<?php } ?>
		<?php } ?>
		</td>
	<td class="tableContent"><?php print $sRoleName; ?></td>
	<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=65&RecID='.stripslashes($aRow['userid']); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit.png" title="Edit User" alt="Edit User" class="icon"/></a></td>
	<td class="tableContent" style="text-align:center;">
	<?php
	if($aRow[username] != 'admin') { ?>
	<a href="<?php print $_SERVER['PHP_SELF'].'?ID=66&RecID='.stripslashes($aRow['userid']); ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete User" alt="Delete User" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a>
	<?php } else {?>
	<img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross-no-delete.png" title="Cannot Delete Primary User" alt="Cannot Delete Primary User" class="icon"/>
	<?php } ?>
	</td>
 </tr>
<?php } } else {?>
<tr>
<td colspan="8" class="tableContent" style="text-align:center;">No user found.</td>
</tr>
<?php }?> 
</tbody>
</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function changeStatus(iStatus, iUserId)
{ 	
 $.ajax({
	   type: "POST",
	   url: "<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=109",
	   data: "requeststatus="+iStatus+"&requestuserid="+iUserId,
	   success: function(msg)
	   {
		  if(msg == "1") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=63&PF=4';
	   	  else if(msg == "2") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=63&PF=5';	   
       }
	 });
}
</script>