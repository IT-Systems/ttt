<?php
$aMsg[0] = 0;
$aFileParts = explode('.txt',stripslashes($sFile));
$sFileDate  = $aFileParts[0];
$aMsg[1] = "The access log of visitors/users to website/application <strong>{$aAppDetails[0]['appname']}</strong> on date <strong>$sFileDate</strong> has been presented below.";
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
<a href="http://www.adiipl.com/opendelight/docs/lifestream.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float:right;margin-right:2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=80" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>

<div style="font-family:courier;text-align:left;float:left;clear:both;">
<?php print nl2br($sFileContent); ?>
</div>

</div>
<!-- Mid Body End -->
<!-- Main content ends -->