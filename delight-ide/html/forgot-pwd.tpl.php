<?php $iTabIndex = 0;
if($iMsg == '1') $sMsg = 'The email id does not exist';
else if($iMsg == '2') $sMsg = 'An email has been sent with your sign in details. Please <a href='.$APP->BASEURL.'/delight-ide/sign.php title="Click here">click here</a> to Sign In with the sent details.';
?>
<a rel="external" href="http://www.adiipl.com/delight/docs/opendelight-ide.php">
<span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span>
</a>
<a  href="<?php print $APP->BASEURL;?>/delight-ide/sign.php" title="Back to Signin Page" style="font-weight:normal;color:#3a8104;">
Back to Signin Page</span>
</a>
</div>
<div id="markerdiv"></div>
<?php if($iMsg == '0' || $iMsg == '1') { ?>
<div id="disp" class="ui-state-highlight ui-corner-all"  style="padding:8px 0px 8px 5px;float:left;width:99.5%;margin-top:10px;"> 
	<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	Please enter the email address below to receive instructions to reset your password.
</div>
<?php } ?>
<div class="ui-state-error ui-corner-all" id="showerror" style="padding:8px 0px 8px 5px;float:left;width:99.5%;margin-top:10px;display:none;"> 
					<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
					The security code is incorrect.
</div>
<?php if($sMsg == '') { ?>
<div style="margin-top:10px;float:left;width:99%;">
<form name="forgetPwd" id="forgetPwd" action="./sign.php?ID=4" method="post">
<div style="float:left;width:99%;">
<label class="makeBold" for="txtEmailId">Email</label><br /><input type="text" name="txtEmailId" id="txtEmailId" value="" class="textboxNormal required" size="42" />
</div>
<label id="txtEmailIdError" style="display:none;clear:both;color:red;">This field is required.</label>
<div style="float:left;margin-top:10px;">
<label for="txtSecurity" class="makeBold">Security Code</label><br/>
   <input class="textboxNormal required" type="text" id="txtSecurity" name="txtSecurity" style="padding-left:2px;" />
   </div>
   <div style="float:left;padding-left:5px;margin-top:25px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/script/captcha.php" id="imgCaptcha" alt="Captcha" style="border:1px solid #CDCDCD;"/></div>
   </div>
<label id="txtSecurityError" style="display:none;clear:both;color:red;">This field is required.</label>
<div style="float:left;margin-top:10px;margin-bottom:5px;">
<a href="javascript:void(0);" onclick="javascript:submitPwdForm();" style="margin:5px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a> 
</div>
<input type="hidden" name="hidPwd" id="hidPwd" value="1" />
</form>
</div>
</div>
<?php } else if($sMsg == "The email id does not exist") { ?>
<div style="padding:0px;margin-top:10px;">
<?php print '<p style="color:#000000;font-weight:normal;line-height:18px;" >'.$sMsg.'</p>'; ?>
<form name="forgetPwd" id="forgetPwd" action="./sign.php?ID=4" method="post">
<div style="clear:both;">
<label class="makeBold" for="txtEmailId">Email</label><br /><input type="text" name="txtEmailId" id="txtEmailId" value="" class="textboxNormal required" size="42" />
</div>
<label id="txtEmailIdError" style="display:none;clear:both;color:red;">This field is required.</label>
<div style="clear:both;padding-top:10px;">
<label for="txtSecurity" class="makeBold">Security Code</label><br/>
   <input class="textboxNormal required" type="text" id="txtSecurity" name="txtSecurity" style="padding-left:2px;" />
   </div>
   <div style="float:left;padding-left:5px;"><img src="<?php print $APP->BASEURL;?>/delight-ide/script/captcha.php" id="imgCaptcha" alt="Captcha" style="border:1px solid #CDCDCD;"/></div>
   </div>
<label id="txtSecurityError" style="display:none;clear:both;color:red;">This field is required.</label>
<div style="clear:both;padding-top:15px;margin-bottom:5px;">
<a href="javascript:void(0);" onclick="javascript:submitPwdForm();" style="margin:5px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a> 
</div>
<input type="hidden" name="hidPwd" id="hidPwd" value="1" />
</form>
</div>
<?php } else {
print '<p style="color:#000000;font-weight:normal;line-height:18px;" >'.$sMsg.'</p>';
} ?>
</div>
</div>
<script type="text/javascript">
 function submitPwdForm()
 {
 	$("#forgetPwd").validate();
	 $.ajax({
		   type: "POST",
		   url: "<?php print $APP->BASEURL;?>/delight-ide/script/check-email.php",
		   data: "name="+document.getElementById('txtEmailId').value+"&code="+document.getElementById('txtSecurity').value,
		   success: function(msg){
		   if(msg == "The email field is blank")
		   {
	    	 document.getElementById("txtEmailIdError").style.display = "block";
	    	 document.getElementById("txtSecurityError").style.display = "none";
		   }
		   else if(msg == "The security code is blank")
		   {
	         document.getElementById("txtSecurityError").style.display = "block";
	         document.getElementById("txtEmailIdError").style.display = "none";
		   }
		   else if(msg == "Success")
		   {
		     $("#forgetPwd").submit();
		   }
		   else
		   {
		     document.getElementById("txtSecurityError").style.display = "none";
		     document.getElementById("txtEmailIdError").style.display = "none";				    
		     document.getElementById("showerror").style.display = "block";
		   }
	 }
		 });
 }
 </script>
<!-- Main content ends -->


