<!-- Mid Body Start -->
<?php
if ($_GET['PF'] == '1') $aMsg[1] = "The new event <strong>{$_GET['PC']}</strong> has successfully been added.";
else if ($_GET['PF'] == '2') $aMsg[1] = "The event <strong>{$_GET['PC']}</strong> has successfully been edited.";
else if ($_GET['PF'] == '3') $aMsg[1] = "The event <strong>{$_GET['PC']}</strong> has successfully been deleted.";
else if ($_GET['PF'] == '4') $aMsg[1] = "The display order of events has successfully been changed.";
else $aMsg[1] = "The list of events have been presented below. Click on <strong>Edit</strong> icon to edit an event.";

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
<a href="http://www.adiipl.com/opendelight/docs/event.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float:right;margin-right:2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div id="ui-od-button-spacing">
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=29&CtrID=<?php print $_GET['CtrID'];?>" title="Sort Display Order" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-triangle-2-n-s"></span>Sort Display Order</a>	
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=24&CtrID=<?php print $_GET['CtrID'];?>" title="Add Event" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Add Event</a>
</div>
<table width="100%" cellspacing="0" cellpadding="0" class="tableData">
<thead>
	<tr>
	<th>Event ID ($APP->ID)</th>
	<th>Event Name ($APP->IDN)</th>
	<th>User Roles Which Access Event</th>
	<th>Status</th>
	<th colspan="3">Action</th>
	</tr>
</thead>
<tbody>
<?php if(count($aEventDetails) > 0) {
foreach($aEventDetails as $aRow) { 
$sRoleName   = $oEvent->getRoleName(stripslashes($aRow[roles]));   
if($aRow[estatus] == 1) $sStatus = 'Active';
else $sStatus = 'Inactive';    
?>
   <tr>
		<td class="tableContent"><?php print stripslashes($aRow[eventid]); if ($aCtrDetails[0][defaulteventid] == $aRow[eventid]) print ' <em>[Default Event]</em>';?></td>
		<td class="tableContent"><?php print stripslashes($aRow[eventname]); ?></td>
		<td class="tableContent"><?php print $sRoleName; ?></td>		
		<td class="tableContent">
		<?php if($aRow[estatus] == 1) { ?>
		<div style="width:16px;float:left;margin-top:0px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-active.png" title="Active" alt="Active" /></div>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="javascript:changeStatus('<?php print $aRow[estatus]; ?>', '<?php print $aRow[eventid]; ?>', '<?php print $_GET['CtrID']; ?>');"><span style="font-size:11px;">Change</span></a>)
		<?php } elseif($aRow[estatus] == 0) { ?>
		<div style="width:16px;float:left;margin-top:0px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-inactive.png" title="Inactive" alt="Inactive" /></div>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="javascript:changeStatus('<?php print $aRow[estatus]; ?>', '<?php print $aRow[eventid]; ?>', '<?php print $_GET['CtrID']; ?>');"><span style="font-size:11px;">Change</span></a>)
		<?php } ?>
		</td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print '../'.stripslashes($aCtrDetails[0]['ctrname']).'?ID='.stripslashes($aRow['eventid']); ?>" target="_blank"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-run.png" title="Run Event" alt="Run Event" class="icon" style="width:22px;" /></a></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=25&RecID='.stripslashes($aRow['eventid']).'&CtrID='.$_GET['CtrID']; ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit-code.png" title="Edit Event" alt="Edit Event" class="icon"/></a></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=26&RecID='.stripslashes($aRow['eventid']).'&CtrID='.$_GET['CtrID']; ?>" title="Delete"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Event" alt="Delete Event" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a></td>
	</tr>
<?php } } else {?>
<tr>
<td colspan="8" class="tableContent" style="text-align:center;">The Controller currently has no event. <a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=24&CtrID=<?php print $_GET['CtrID'];?>" title="Please create one now!">Please create one now!</a></td>
</tr>
<?php }?>
</tbody>
</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function changeStatus(iStatus, iEventId, iCtrID)
{ 	
 $.ajax({
	   type: "POST",
	   url: "<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=108",
	   data: "requeststatus="+iStatus+"&requesteventid="+iEventId,
	   success: function(msg)
	   {
	   	  if(msg == "1") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=21&CtrID='+iCtrID+'&Msg=1';
	   	  else if(msg == "2") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=21&CtrID='+iCtrID+'&Msg=2';
       }
	 });
}
</script>