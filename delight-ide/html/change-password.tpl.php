<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/opendelight-ide.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right:2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=70" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div class="formSpacing">
<form name="frmChangePwd" id="frmChangePwd" action="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=104" method="post">
<div class="divSpacing">
<label>Current Password</label>
<input type="password" name="txtCurrentPassword"  id="txtCurrentPassword" value="<?php if($_POST['txtCurrentPassword']) print $_POST['txtCurrentPassword'];?>" class="textboxNormal required" style="width:220px;"/>
</div>
<div class="divSpacing">
<label>New Password <span>(should be minimum 6 characters)</span></label>
<input type="password" name="txtNewPassword"  id="txtNewPassword" value="<?php if($_POST['txtNewPassword']) print $_POST['txtNewPassword'];?>" class="textboxNormal required" style="width:220px;"/>
</div>
<div class="divSpacing">
<label>Retype New Password <span>(for confirmation of typing)</span></label>
<input type="password" name="txtRetypePassword"  id="txtRetypePassword" value="<?php if($_POST['txtRetypePassword']) print $_POST['txtRetypePassword'];?>" class="textboxNormal required" style="width:220px;"/>
</div>
<div class="divSpacing">
<input type="hidden" name="hidPwd" id="hidPwd" value="1" />
<a href="javascript:void(0);" onclick="javascript:changePassword();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>
</div>
</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->
<script type="text/javascript">
function changePassword()
{
		$("#frmChangePwd").validate();
	 	$("#frmChangePwd").submit();
}
</script>