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
	$aMsg[0] = "Develop and manage your application with Opendelight. For help, click on info icon on right of header texts bar at any time.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
?>
<a href="http://www.adiipl.com/opendelight/docs/opendelight-ide.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div class="formSpacing" style="padding-top:0px;">
<div style="float:left;width:25%;padding-right:10px;">
<div style="background-color:#E5F4D5;border:1px solid #C2E2AC;padding:0px 7px 7px 7px;overflow:hidden;">
<p style="font-size:14px;font-weight:bold;">What You Edited Last -</p>
<?php
foreach($aLatestEvents as $aLatestEvent)
{
?>
<p><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=25&RecID=<?php print $aLatestEvent[eventid];?>&CtrID=<?php print $aLatestEvent[ctrid];?>" title="<?php print $aLatestEvent[eventname];?>">Event: <code><?php print $aLatestEvent[eventname];?></code> (CTR: <code><?php print $aLatestEvent[ctrname];?></code>)</a></p>
<?php
}
?>

<?php
foreach($aLatestClasses as $aLatestClass)
{
	$aClassFileParts = explode('.cls.php',$aLatestClass[name]);
	$sClassFileName = $aClassFileParts[0];
?>
<p><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=32&Class=<?php print $sClassFileName;?>" title="<?php print $sClassFileName;?>">Model Class: <code><?php print $sClassFileName;?></code></a></p>
<?php
}
?>

</div> <!-- Last Edits Content -->
</div> <!-- Left Content -->

<div style="float:left;width:50%;padding-left:10px;padding-right:10px;border-left:1px dashed #C2E2AC;">
<div style="background-color:#E5F4D5;border:1px solid #C2E2AC;padding:7px;font-size:14px;font-weight:bold;overflow:hidden;">Opendelight Newsfeed from ADII</div>
<?php
foreach($oFeedItems as $oFeedItem)
{
?>
<p><a href="<?php print $oFeedItem->link;?>" target="_blank"><strong><?php print $oFeedItem->title;?></strong></a><br /><?php print $oFeedItem->description;?></p>
<?php
}
?>
</div> <!-- Middle Content -->

<div style="float:right;width:20%;padding-left:10px;border-left:1px dashed #C2E2AC;">
<!--<div style="background-color:#E5F4D5;border:1px solid #C2E2AC;padding:7px;font-size:14px;font-weight:bold;overflow:hidden;"><a href="#" title="Upgrade to Latest Version" rel="external">Upgrade to Latest Version</a></div>

<div class="titlebox" style="margin-top:10px;margin-bottom:10px;"><a href="#" title="Order CSS Files" rel="external">Order CSS Files</a></div>-->

<div style="background-color:#E5F4D5;border:1px solid #C2E2AC;padding:0px 7px 7px 7px;font-size:14px;overflow:hidden;">
<p style="font-weight:bold;">Documentation Links</p>
<ul>
<li><a href="http://www.adiipl.com/opendelight/docs/get-started.php" title="Get Started" rel="external">Get Started</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/opendelight-ide.php" title="Opendelight IDE" rel="external">Opendelight IDE</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/framework-architecture.php" title="Architecture of Framework" rel="external">Architecture of Framework</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/developer-workflow.php" title="Developer Workflow" rel="external">Developer Workflow</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/glossary.php" title="Glossary of Terms" rel="external">Glossary of Terms</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/opendelight-objects.php" title="Opendelight Objects" rel="external">Opendelight Objects</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/opendelight-array.php" title="Opendelight Array" rel="external">Opendelight Array</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/opendelight-libraries.php" title="Opendelight Libraries" rel="external">Opendelight Libraries</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/application-database-structure.php" title="Database Structure" rel="external">Database Structure</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/application-file-structure.php" title="File Structure" rel="external">File Structure</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/controller.php" title="Controller" rel="external">Controller</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/event.php" title="Event" rel="external">Event</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/model.php" title="Model" rel="external">Model</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/view.php" title="View" rel="external">View</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/users.php" title="Users and Roles" rel="external">Users and Roles</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/settings.php" title="Application Settings" rel="external">Application Settings</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/coding-standards-conventions.php" title="Standards and Conventions" rel="external">Standards &amp; Conventions</a></li>
<li><a href="http://www.adiipl.com/opendelight/docs/future-roadmap.php" title="Future Roadmap" rel="external">Future Roadmap</a></li>
</ul>
<p style="font-style:italic;font-size:11px;"><strong>NOTE:</strong> For help on using Opendelight IDE, click on the icon <span class="ui-icon ui-icon-info"></span> on the top right of any screen on IDE.
</div> <!-- Documentation Content -->
</div> <!-- Right Content -->
</div> <!-- Middle Content -->
</div>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
