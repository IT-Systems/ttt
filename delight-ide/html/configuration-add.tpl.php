<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/model.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=50" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div class="formSpacing">
<form name="frmConfigurationAdd" id="frmConfigurationAdd" action="<?php print $_SERVER['PHP_SELF'].'?ID=51';?>" method="post">
<div class="divSpacing">
<label>Variable Name <span>(A-Z 0-9, and no white space, no special characters)</span></label>
<input type="text" name="txtVariable" id="txtVariable" value="<?php if (isset($_POST[txtVariable])) print $_POST[txtVariable];?>" class="textboxNormal required space" style="width:325px;"/>
</div>
<div class="divSpacing">
<label>Description <span>(what is this variable about?)</span></label>
<input type="text" name="txtDescription" id="txtDescription" value="<?php if (isset($_POST[txtDescription])) print $_POST[txtDescription];?>" class="textboxNormal required" style="width:325px;"/>
</div>
<div class="divSpacing">
<label>Value of Variable <span>(any string)</span></label>
<input type="text" name="txtValue"  id="txtValue" value="<?php if (isset($_POST[txtValue])) print $_POST[txtValue];?>" class="textboxNormal required" style="width:325px;"/>
</div>
<div class="divSpacing">
<input type="hidden" name="hidAddStatus" id="hidAddStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:addConfiguration();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>

</div>

</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function addConfiguration()
{
 	$("#frmConfigurationAdd").validate();
 	$("#frmConfigurationAdd").submit();
}
</script>