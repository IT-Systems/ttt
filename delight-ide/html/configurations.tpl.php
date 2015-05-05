<?php
if ($_GET['PF'] == '1')
{
	$aMsg[0] = "The new variable has been successfully added. Please check in the list below.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '2')
{
	$aMsg[0] = "The variable <strong>{$_GET['PC']}</strong> has successfully been edited.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '3')
{
	$aMsg[0] = "The variable <strong>{$_GET['PC']}</strong> has successfully been deleted.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else if ($_GET['PF'] == '4')
{
	$aMsg[0] = "The display order of configuration variables have successfully been sorted.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else
{
	$aMsg[0] = "The list of configuration variables have been presented below. You can add, edit and delete any configuration variable.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
?>
<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/model.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>

<div id="ui-od-button-spacing">
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=54" title="Sort Display Order" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-triangle-2-n-s"></span>Sort Display Order</a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=51" title="Add Configuration Variable" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Add Configuration Variable</a>
</div>
<table width="100%" cellspacing="0" cellpadding="0"  class="tableData">
<thead>
 <tr>
	<th >Sl. No.</th>
	<th>Variable Name</th>
	<th>Description</th>
	<th>Value</th>
	<th colspan="2">Action</th>
 </tr>
</thead>
<tbody>
<?php 
$i = 0;
if(count($aConfigDetails) > 0) {
foreach($aConfigDetails as $aRow) {
$i++;
?>
   <tr>
		<td class="tableContent"><?php print $i; ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[configname]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[description]); ?></td>
		<td class="tableContent"><?php print stripslashes($aRow[configvalue]); ?></td>
		<td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=52&RecID='.stripslashes($aRow['configid']); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-edit.png" title="Edit Configuration" alt="Edit Configuration" class="icon"/></a></td>
	    <td class="tableContent" style="text-align:center;"><a href="<?php print $_SERVER['PHP_SELF'].'?ID=53&RecID='.stripslashes($aRow['configid']); ?>"><img src="<?php print $APP->BASEURL;?>/delight-ide/images/icon-cross.png" title="Delete Configuration" alt="Delete Configuration" class="icon" onclick="javascript:return confirm(&quot;Do you want to delete?&quot;);"/></a></td>
	</tr>
<?php } } else{?>
<tr>
<td colspan="6" class="tableContent" style="text-align:center;">No configuration variable has been created yet. <a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=51" title="Add a configuration variable now!">Add a configuration variable now!</a></td>
</tr>
<?php }?>
</tbody>
</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->