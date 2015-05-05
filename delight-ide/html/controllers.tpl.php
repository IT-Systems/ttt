<?php
if ($_GET['PF'] == '1') $aMsg[1] = "The new controller <strong>{$_GET['PC']}</strong> has been successfully added.";
else if ($_GET['PF'] == '2') $aMsg[1] = "The controller <strong>{$_GET['PC']}</strong> has successfully been edited.";
else if ($_GET['PF'] == '3') $aMsg[1] = "The controller <strong>{$_GET['PC']}</strong> has successfully been deleted.";
else if ($_GET['PF'] == '4') $aMsg[1] = "The display order of controllers has successfully been changed.";
else $aMsg[1] = "The list of controllers have been presented below. Click on a <strong>Controller File</strong> or click on <strong>View Events</strong> icon to see list of events thereof.";

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
<a href="http://www.adiipl.com/opendelight/docs/controller.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div id="ui-od-button-spacing">
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=27" title="Sort Display Order" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-triangle-2-n-s"></span>Sort Display Order</a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=22" title="Add Controller" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Add Controller</a>
</div>
<table width="100%" cellspacing="0" cellpadding="0" class="tableData">
<thead>
<tr>
	<th>$_DELIGHT[CTRID]</th>
	<th>Controller File Name (with Path from Root of Application)</th>
	<th>Public?</th>
	<th>Status</th>
	<th colspan="3">Action</th>
</tr>
</thead>
<tbody>
<?php 
if(count($aCtrlDetails) > 0){
foreach($aCtrlDetails as $aRow) { 
 $sDefaultEvent = $oControllers->getDefaultEvent(stripslashes($aRow[defaulteventid]));
 if(stripslashes($aRow[ispublic]) == 1) $sIsPublic = 'Yes';
 else $sIsPublic = 'No'; 
 if(stripslashes($aRow[ctrstatus]) == 1) $sCtrStatus = 'Active';
 else $sCtrStatus = 'Inactive'; 
?>
   <tr>
		<td class="tableContent"><?php print stripslashes($aRow[ctrid]); ?></td>
		<td class="tableContent"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=21&CtrID=<?php print $aRow[ctrid]; ?>" title="<?php print stripslashes($aRow[ctrname]); ?>">/<?php print stripslashes($aRow[ctrname]); ?></a></td>
		<td class="tableContent">
		<?php if($aRow[ispublic] == 1) { ?>
		<div style="float:left;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-public.png" title="Public" alt="Public" class="icon" style="width:22px;"/></div>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="javascript:changePublic('<?php print $aRow[ispublic]; ?>', '<?php print $aRow[ctrid]; ?>');"><span style="font-size:11px;">Change</span></a>)
		<?php } elseif($aRow[ispublic] == 0) { ?>
		<div style="float:left;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-private.png" title="Private" alt="Private" class="icon"/></div>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="javascript:changePublic('<?php print $aRow[ispublic]; ?>', '<?php print $aRow[ctrid]; ?>');"><span style="font-size:11px;">Change</span></a>)
		<?php } ?>
		</td>
		<td class="tableContent">
		<?php if($aRow[ctrstatus] == 1) { ?>
		<div style="width:16px;float:left;margin-top:0px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-active.png" title="Active" alt="Active" /></div>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="javascript:changeStatus('<?php print $aRow[ctrstatus]; ?>', '<?php print $aRow[ctrid]; ?>');"><span style="font-size:11px;">Change</span></a>)
		<?php } elseif($aRow[ctrstatus] == 0) { ?>
		<div style="width:16px;float:left;margin-top:0px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-inactive.png" title="Inactive" alt="Inactive" /></div>&nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="javascript:changeStatus('<?php print $aRow[ctrstatus]; ?>', '<?php print $aRow[ctrid]; ?>');"><span style="font-size:11px;">Change</span></a>)
		<?php } ?>
		</td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=21&CtrID=<?php print $aRow[ctrid]; ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-browse.png" title="View Events" alt="View Events" class="icon"/></a></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=23&RecID='.stripslashes($aRow['ctrid']); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit.png" title="Edit Controller" alt="Edit Controller" class="icon"/></a></td>
	    <td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=28&RecID='.stripslashes($aRow['ctrid']); ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Controller" alt="Delete Controller" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a></td>
	</tr>
<?php } } else {?>
<tr>
<td colspan="8" class="tableContent" style="text-align:center;">No controller found.</td>
</tr>
<?php }?>
</tbody>
</table>
</div>
<script type="text/javascript">
function changeStatus(iStatus, iCtrlId)
{ 	
 $.ajax({
	   type: "POST",
	   url: "<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=106",
	   data: "requeststatus="+iStatus+"&requestctrlid="+iCtrlId,
	   success: function(msg)
	   {
	   	  if(msg == "1") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20&Msg=1';
	   	  else if(msg == "2") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20&Msg=2';	   
       }
	 });
}

function changePublic(iPublic, iCtrlId)
{ 	
 $.ajax({
	   type: "POST",
	   url: "<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=107",
	   data: "requestpublic="+iPublic+"&requestctrlid="+iCtrlId,
	   success: function(msg)
	   {
	   	  if(msg == "1") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20&Msg=3';
	   	  else if(msg == "2") self.location.href = '<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20&Msg=4';	   
       }
	 });
}
</script>
<!-- Mid Body End -->
<!-- Main content ends -->