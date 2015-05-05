<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/settings.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=100" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div class="formSpacing">
<form name="frmSysEdit" id="frmSysEdit" action="<?php print $_SERVER['PHP_SELF'].'?ID=102&RecID='.stripslashes($aEachSysDetails[0]['sysid']);?>" method="post">
<div class="divSpacing">
<label>Name of the Application</label>
<input type="text" name="txtAppName" value="<?php if($_POST['txtAppName']){ print $_POST['txtAppName']; }else{ print stripslashes($aEachSysDetails[0]['appname']); } ?>"  id="txtAppName" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Name of Author</label>
<input type="text" name="txtAuthor"  id="txtAuthor" value="<?php if($_POST['txtAuthor']){ print $_POST['txtAuthor']; }else{ print stripslashes($aEachSysDetails[0]['author']); } ?>" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Application Base URL <span>(without trailing slash "/")</span></label>
<input type="text" name="txtBaseURL"  id="txtBaseURL" value="<?php if($_POST['txtBaseURL']){ print $_POST['txtBaseURL']; }else{ print stripslashes($aEachSysDetails[0]['baseurl']); } ?>" class="textboxNormal required" style="width:350px;"/>
</div>
<div class="divSpacing">
<label>Description of Application <span>(without trailing slash "/")</span></label>
<textarea name="taDescription" id="textarea_1" class="textareaNormal" rows="15" cols="85"><?php if($_POST['taDescription']){ print $_POST['taDescription']; }else{ print stripslashes($aEachSysDetails[0]['description']); } ?></textarea>
</div>

<div class="divSpacing">
<label>Enable Log? <span>(This determines if log files are to be created or not)</span></label>
<input id="radLogYes" name="radLog" class="required" type="radio" style="margin-left:0px;float:left;clear:both;" value="1" <?php if($_POST['radLog'] == 1 || $aEachSysDetails[0]['logstatus'] == 1) print 'checked="checked"';?>/>
<label for="radLogYes" class="radiolabel">Yes</label>
<input id="radLogNo" name="radLog" class="required" type="radio" style="float:left;margin-left:25px;" value="0" <?php if($_POST['radLog'] == '0' || stripslashes($aEachSysDetails[0]['logstatus']) == '0') print 'checked="checked"';?>/>
<label for="radLogNo" class="radiolabel" style="margin-left:5px;">No</label>
<!--<label for="radStatus" class="error" style="display:none;">Please select a radio button.</label>-->
</div>

<div class="divSpacing">
<label>Status of Application <span>(This determines if the application is active or not)</span></label>
<input id="radStatusActive" name="radStatus" class="required" type="radio" style="margin-left:0px;float:left;clear:both;" value="1" <?php if($_POST['radStatus'] == 1 || $aEachSysDetails[0]['sysstatus'] == 1) print 'checked="checked"';?>/>
<label for="radStatusActive" class="radiolabel">Active</label>
<input id="radStatusInactive" name="radStatus" class="required" type="radio" style="float:left;margin-left:25px;" value="0" <?php if($_POST['radStatus'] == '0' || stripslashes($aEachSysDetails[0]['sysstatus']) == '0') print 'checked="checked"';?>/>
<label for="radStatusInactive" class="radiolabel" style="margin-left:5px;">Inactive</label>
<!--<label for="radStatus" class="error" style="display:none;">Please select a radio button.</label>-->
</div>

<div class="divSpacing">
<input type="hidden" name="hidEditStatus" id="hidEditStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:editSys();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function editSys()
{
 	$("#frmSysEdit").validate();
 	$("#frmSysEdit").submit();
}
</script>