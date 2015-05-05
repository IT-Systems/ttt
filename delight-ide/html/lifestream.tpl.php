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
<a href="http://www.adiipl.com/opendelight/docs/lifestream.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=83" style="float:right;margin:5px 8px 11px 8px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all" title="Remove History"><span class="ui-icon ui-icon-plus"></span>Remove History</a>
<table width="100%" cellspacing="0" cellpadding="0"  class="tableData">
<thead>
	<tr>
	<th>Sl. No.</th>
	<th>Log Files (Date-wise)</th>	
	<th>Size of Log File (KB)</th>
	<th colspan="2">Action</th>
	</tr>
</thead>
<tbody>
<?php 
if(count($aListofFiles) > 0){
$k = 1;
foreach($aListofFiles as $aRow) {
?>
   <tr>
		<td class="tableContent"><?php print $k++; ?></td>
		<td class="tableContent"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=81&File='.stripslashes($aRow[name]); ?>" title="<?php print stripslashes($aRow[name]); ?>"><?php print stripslashes($aRow[name]); ?></a></td>
		<td class="tableContent"><?php print stripslashes($aRow[size]); ?></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=81&File='.stripslashes($aRow[name]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-view.png" title="View Log File" alt="View Log File" class="icon"/></a></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=82&File='.stripslashes($aRow[name]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Log File" alt="Delete Log File" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a></td>
	</tr>
<?php } } else {?>
<tr>
<td colspan="5" class="tableContent" style="text-align:center;">No log file is currently present.</td>
</tr>
<?php }?>
</tbody>

</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->