<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1')
{
	$aMsg[0] = "The new user role <strong>{$_GET['PC']}</strong> has been successfully added.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2')
{
	$aMsg[0] = "The role <strong>{$_GET['PC']}</strong> has successfully been edited.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '3')
{
	$aMsg[0] = "The role <strong>{$_GET['PC']}</strong> has successfully been deleted.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '4')
{
	$aMsg[0] = "The display order of user roles has successfully been changed.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else
{
	$aMsg[0] = "The list of user roles have been presented below. The role <strong>Administrator</strong> cannot be deleted.";
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
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=74" title="Sort Display Order" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-triangle-2-n-s"></span>Sort Display Order</a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=71" title="Add User Role" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Add User Role</a>
</div>
<table width="100%" cellspacing="0" cellpadding="0" class="tableData">
<thead>
 <tr>
	<th>Sl. No.</th>
	<th>Role Name</th>
	<th>Default Controller File</th>
	<th>Default Event Name</th>
	<th colspan="2">Action</th>
 </tr>
</thead>
<tbody>
<?php 
$i = 0;
if(count($aRolesDetails) > 0) {
foreach($aRolesDetails as $aRow) { 
$sDefaultCtr   = $oRoles->getDefaultCtr(stripslashes($aRow[defaultctrid]));
$sDefaultEvent = $oRoles->getDefaultEvent(stripslashes($aRow[defaulteventid]));
$i++;
?>
   <tr>
		<td class="tableContent"><?php print $i; ?></td>
		<td class="tableContent"><?php print $aRow[rolename]; ?></td>
		<td class="tableContent"><?php print $sDefaultCtr; ?></td>
		<td class="tableContent"><?php print $sDefaultEvent; ?></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=72&RecID='.stripslashes($aRow['roleid']); ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit.png" title="Edit Role" alt="Edit Role" class="icon"/></a></td>
	    <td class="tableContent" style="text-align:center;">
	    <?php
	if($aRow[rolename] != 'Administrator') { ?>
	<a href="<?php print $_SERVER['PHP_SELF'].'?ID=73&RecID='.stripslashes($aRow['roleid']); ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Role" alt="Delete Role" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a>
	<?php } else {?>
	<img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross-no-delete.png" title="Administrator Role cannot be deleted" alt="Administrator Role cannot be deleted" class="icon"/>
	<?php } ?>
	    </td>
	</tr>
<?php } } else {?>
<tr>
<td colspan="6" class="tableContent" style="text-align:center;">SYSTEM ERROR! No user role has been found.</td>
</tr>
<?php }?>
</tbody>
</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->