<!-- Mid Body Start -->
<a href="http://www.adiipl.com/delight/docs/model.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=50" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div class="formSpacing">
<form name="frmConfigurationEdit" id="frmConfigurationEdit" action="<?php print $_SERVER['PHP_SELF'].'?ID=52&RecID='.stripslashes($aEachConfigDetails[0]['configid']);?>" method="post">
<div class="divSpacing">
<label>Variable Name <span>(Read-only)</span></label>
<input type="text" name="txtVariable" id="txtVariable" value="<?php if($_POST['txtVariable']){ print $_POST['txtVariable']; }else{ print stripslashes($aEachConfigDetails[0]['configname']); } ?>" class="textboxNormal required space" style="width:325px;" readonly/>
</div>
<div class="divSpacing">
<label>Description <span>(what is this variable about?)</span></label>
<input type="text" name="txtDescription" id="txtDescription" value="<?php if($_POST['txtDescription']){ print $_POST['txtDescription']; }else{ print stripslashes($aEachConfigDetails[0]['description']); } ?>" class="textboxNormal required" style="width:325px;"/>
</div>
<div class="divSpacing">
<label>Value of Variable <span>(any string)</span></label>
<input type="text" name="txtValue"  id="txtValue" value="<?php if($_POST['txtValue']){ print $_POST['txtValue']; }else{ print stripslashes($aEachConfigDetails[0]['configvalue']); } ?>" class="textboxNormal required" style="width:325px;"/>
</div>
<div class="divSpacing">
<input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:editConfiguration();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>

</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function editConfiguration()
{
 	$("#frmConfigurationEdit").validate();
 	$("#frmConfigurationEdit").submit();
}
</script>