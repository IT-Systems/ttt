<?php
if ($_GET['PF'] == '1')
{
	$aMsg[0] = "The new view page variable has successfully been added. Please check in the list below.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2')
{
	$aMsg[0] = "The view page variable <strong>{$_GET['PC']}</strong> has successfully been edited.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '3')
{
	$aMsg[0] = "The view page variable <strong>{$_GET['PC']}</strong> has successfully been deleted.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else
{
	$aMsg[0] = "The list of view page variables have been presented below.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
?>
<!-- Mid Body Start -->
<?php 
if($_GET['Msg'] == '1') $sMsg = "The page variable has been added successfully.";
else if($_GET['Msg'] == '1') $sMsg = "The page variable has been edited successfully.";
else $sMsg = "view page variables";
?>
<a href="http://www.adiipl.com/opendelight/docs/view.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div id="ui-od-button-spacing">
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=91" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all" title="Add Page Variables"><span class="ui-icon ui-icon-plus"></span>Add Page Variables</a>
</div>
<table width="100%" cellspacing="0" cellpadding="0"  class="tableData">
<thead>
	<tr>
	<th>Sl. No.</th>
	<th>Page Variable Key</th>
	<th>Action</th>
	</tr>
</thead>
<tbody>
  <?php 
  if(count($aPagevarDetails) > 0) 
 { 
foreach($aPagevarDetails as $aRow) 
{  
?>
   <tr>
		<td class="tableContent"><?php print stripslashes($aRow[pagevarid]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[pagevarkey]); ?></td>
		<!--<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=92&RecID='.stripslashes($aRow['pagevarid']); ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit.png" title="Edit Page Variable" alt="Edit Page Variable" class="icon"/></a></td>-->
		<td class="tableContent" style="text-align:center;">
		<?php if($aRow[pagevarid] > 3){?>
		<a href="<?php print $_SERVER['PHP_SELF'].'?ID=93&RecID='.stripslashes($aRow['pagevarid']); ?>" ><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Page Variable" alt="Delete Page Variable" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a>
		<?php }else{?>
		<img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross-no-delete.png" title="Cannot be deleted" alt="Cannot be deleted" class="icon"/>
		<?php }?>
		</td>
	</tr>
<?php } } else {?>
<tr>
<td colspan="8" class="tableContent" style="text-align:center;">No pagevars found.</td>
</tr>
<?php }?>
</tbody>
</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->