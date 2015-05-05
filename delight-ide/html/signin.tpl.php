<!-- Main content starts -->
<?php 
if($_REQUEST['Msg']) $sMsg = "You have signed out successfully. If you want to signin again, please enter your username and password to proceed.";
else $sMsg = "Please enter your username and password to sign into <strong>Integrated Development Environment (IDE) of opendelight</strong> for application <strong>".$aAppDetails[0]['appname']."</strong>.";        
?>
<a rel="external" href="http://www.adiipl.com/delight/docs/opendelight-ide.php"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>
<div class="ui-state-highlight ui-corner-all" style="padding:8px 0px 8px 5px;float:left;width:99.5%;margin-top:10px;font-size:12px;"> 
	<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	<?php print $sMsg;?>
</div>
<form name="frmLogin" id="frmLogin" action="./sign.php?ID=2" method="post">
<div style="margin-top:10px;float:left;width:99%;"> 
<div style="margin-top:5px;color:red;"><?php print $sErrMsg;?></div>
<div style="float:left;width:99%;">
<label class="makeBold">Username</label><br /><input type="text" name="txtUsername" id="txtUsername" value="" class="textboxNormal required" size=40" />
</div>
<div style="float:left;width:99%;margin-top:15px;">
<label class="makeBold">Password</label><br /><input type="password" name="txtPassword" id="txtPassword" value="" class="textboxNormal required" size="40" />
</div>
<div style="float:left;width:99%;margin-top:15px;">
<a href="javascript:void(0);" onclick="javascript:submitContactForm();" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Signin</a> 
&nbsp;<a href="./sign.php?ID=3" title="Forgot Password" style="color:#3a8104;">Forgot Password</a>?</div>
<input type="hidden" name="hidStatus" id="hidStatus" value="1" />
</div>
</form>
 <script type="text/javascript">
 function submitContactForm()
 {
 	$("#frmLogin").validate();
 	$("#frmLogin").submit();
 }
 </script>		
<!-- Main content ends -->