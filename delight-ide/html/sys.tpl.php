<?php
if ($_GET['PF'] == '2')
{
	$aMsg[0] = "The application settings have successfully been edited.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
else
{
	$aMsg[0] = "The details of application settings have been presented below. You can edit as required.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
?>
<a href="http://www.adiipl.com/opendelight/docs/settings.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right:2px;margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>

<div id="ui-od-button-spacing">
<a href="<?php print $_SERVER['PHP_SELF'].'?ID=102&RecID=1'; ?>" title="Edit Application Settings" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Edit Application Settings</a>
</div>

<table width="100%" cellspacing="0" cellpadding="0"  class="tableData" style="margin-top:5px;">
<?php 
$i = 0;
if(count($aSysDetails) > 0) {
foreach($aSysDetails as $aRow) {
$i++;
?>
<thead>
 <tr>
	<th><span class="labelsys">Application Name</span><br /><span class="valuesys"><?php print stripslashes($aRow[appname]); ?></span></th>
 </tr>
</thead>
<tbody>
   <tr><td class="tableContent"><span class="labelsys">Author Name</span><br /><span class="valuesys"><?php print stripslashes($aRow[author]); ?></span></td></tr>
   <tr><td class="tableContent"><span class="labelsys">Application Base URL</span><br /><span class="valuesys"><?php print stripslashes($aRow[baseurl]); ?></span></td></tr>
   <tr><td class="tableContent"><span class="labelsys">Lifestream Log Status</span><br /><span class="valuesys"><?php if (stripslashes($aRow[logstatus]) == 1) print 'Enabled'; else print 'Disabled'; ?></span></td></tr>
   <tr><td class="tableContent"><span class="labelsys">Application Status</span><br /><span class="valuesys"><?php if (stripslashes($aRow[sysstatus]) == 1) print 'Active'; else print 'Inactive'; ?></span></td></tr>
   <tr><td class="tableContent"><span class="labelsys">Application Description</span><br /><span class="valuesys"><?php print nl2br(stripslashes($aRow[description])); ?></span></td></tr>
<?php } } else{?>
<tr><td class="tableContent" style="text-align:center;">The application has not yet setup. <a href="install.php">Go to installation now!</a></td></tr>

</tbody>
<?php }?>
</table>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->