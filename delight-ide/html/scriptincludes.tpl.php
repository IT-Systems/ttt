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
<a href="http://www.adiipl.com/opendelight/docs/model.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=36" style="float:right;margin:5px 8px 11px 8px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all" title="Add Script Include"><span class="ui-icon ui-icon-plus"></span>Add Script Include</a>
<table width="100%" cellspacing="0" cellpadding="0"  class="tableData">
<thead>
	<tr>
	<th>Sl. No.</th>
	<th>Script Include File Name (File name must have <span style="font-family:courier;">.inc.php</span> extension)</th>	
	<th>Size of File (KB)</th>
	<th>Last Update</th> 
	<th colspan="2">Action</th>
	</tr>
</thead>
<tbody>
<?php 
if(count($aAppSIDetails) > 0){
$k = 1;
foreach($aAppSIDetails as $aRow) {
$aFileName = explode('.cls.php', $aRow[name]);
?>
   <tr>
		<td class="tableContent"><?php print $k++; ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[name]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[size]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[time]); ?></td>
		<!-- <td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=32&Class='.stripslashes($aRow[name]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-view.png" title="View Script Include" alt="View Script Include" class="icon"/></a></td> -->
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=37&File='.stripslashes($aRow[name]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit-code.png" title="Edit Script Include" alt="Edit Script Include" class="icon"/></a></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=38&File='.stripslashes($aRow[name]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Script Include" alt="Delete Script Include" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a></td>
	</tr>
<?php } } else {?>
<tr>
<td colspan="8" class="tableContent" style="text-align:center;">No file is currently present. <a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=36" title="Add one now!">Add one now!</a></td>
</tr>
<?php }?>
</tbody>

</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->