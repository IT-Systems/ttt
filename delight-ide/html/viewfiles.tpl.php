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
<a href="http://www.adiipl.com/opendelight/docs/view.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=41&VFT=<?php print $_GET['VFT'];?>" style="float:right;margin:5px 8px 11px 8px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all" title="Add <?php print $sFileTypeTexts;?>"><span class="ui-icon ui-icon-plus"></span>Add <?php print $sFileTypeTexts;?></a>
<table width="100%" cellspacing="0" cellpadding="0"  class="tableData">
<thead>
	<tr>
	<th>Sl. No.</th>
	<th><?php print $sFileTypeTexts;?> Name</th>	
	<th>Size of File (KB)</th>
	<th>Last Update</th> 
	<th colspan="2">Action</th>
	</tr>
</thead>
<tbody>
<?php 
if(count($aViewFileDetails) > 0){
$k = 1;
foreach($aViewFileDetails as $aRow) {
	if ($_GET['VFT'] == 'html')
	{
		$sExtension = '.tpl.php';
	}
	else if ($_GET['VFT'] == 'js')
	{
		$sExtension = '.js';
	}
	else if ($_GET['VFT'] == 'css')
	{
		$sExtension = '.css';
	}
	else
	{
		exit();
	}
	$aFile1 = explode('.php', $aRow[name]);
    $aFile2 = explode($sExtension, $aRow[name]);
    if (strcmp($aFile1[0],$aRow[name]) != 0 || strcmp($aFile2[0],$aRow[name]) != 0)
    {
	    $sFileName = $aRow[name];
?>
   <tr>
		<td class="tableContent"><?php print $k++; ?></td>
		<td class="tableContent"><?php print stripslashes($sFileName); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[size]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[time]); ?></td>
		<!-- <td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=42&Class='.stripslashes($aFileName[0]); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-view.png" title="View Application Class" alt="View Application Class" class="icon"/></a></td> -->
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=42&VFT='.$_GET['VFT'].'&File='.stripslashes($sFileName); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit-code.png" title="Edit Application Class" alt="Edit Application Class" class="icon"/></a></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=43&VFT='.$_GET['VFT'].'&File='.stripslashes($sFileName); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Application Class" alt="Delete Application Class" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a></td>
	</tr>
<?php }} } else {?>
<tr>
<td colspan="8" class="tableContent" style="text-align:center;">No file is currently present. <a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=41&VFT=<?php print $_GET['VFT'];?>" title="Add one now!">Add one now!</a></td>
</tr>
<?php }?>
</tbody>

</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->