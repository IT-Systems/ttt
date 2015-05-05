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
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=31" style="float:right;margin:5px 8px 11px 8px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all" title="Add Application Class"><span class="ui-icon ui-icon-plus"></span>Add Application Class</a>
<table width="100%" cellspacing="0" cellpadding="0"  class="tableData">
<thead>
	<tr>
	<th>Sl. No.</th>
	<th>Class Name (File name is the same as class name but with .cls.php appended)</th>	
	<th>Size of Class File (KB)</th>
	<th>Last Update</th> 
	<th colspan="2">Action</th>
	</tr>
</thead>
<tbody>
<?php 
if(count($aAppClassDetails) > 0){
$k = 1;
foreach($aAppClassDetails as $aRow) {
$aFileName = explode('.cls.php', $aRow[name]);
?>
   <tr>
		<td class="tableContent"><?php print $k++; ?></td>
		<td class="tableContent"><?php print stripslashes($aFileName[0]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[size]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[time]); ?></td>
		<!-- <td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=32&Class='.stripslashes($aFileName[0]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-view.png" title="View Application Class" alt="View Application Class" class="icon"/></a></td> -->
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=32&Class='.stripslashes($aFileName[0]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit-code.png" title="Edit Application Class" alt="Edit Application Class" class="icon"/></a></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=33&Class='.stripslashes($aFileName[0]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Application Class" alt="Delete Application Class" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a></td>
	</tr>
<?php } } else {?>
<tr>
<td colspan="8" class="tableContent" style="text-align:center;">No application class is currently present. <a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=31" title="Add one now!">Add one now!</a></td>
</tr>
<?php }?>
</tbody>

</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->