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
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=30" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsgClass[0]; ?>"> 
	<span class="<?php print $aMsgClass[1]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsgText;?>
</div>
<div class="formSpacing">
<form name="frmAppClassAdd" id="frmAppClassAdd" action="<?php print $_SERVER['PHP_SELF'].'?ID=31';?>" method="post">
<div class="divSpacing">
<label>Application Class Name <span>(File name is the same as class name but with .cls.php appended. Cannot be edited later.)</span></label>
<input type="text" name="txtClassName" id="txtClassName" value="" class="textboxNormal required space" style="width:250px;font-size:11px;"/>
</div>
<div class="divSpacing">
<input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:addAppClass();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</form>
</div>

</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function addAppClass()
{
 	$("#frmAppClassAdd").validate();
 	$("#frmAppClassAdd").submit();
}
</script>