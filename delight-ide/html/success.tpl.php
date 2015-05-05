<div style="font-size:13px;">
<div class="ui-state-highlight ui-corner-all"  style="padding:8px 0px 8px 5px;float:left;width:99.5%;margin:5px 0px;"> 
	<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	The application <strong><?php print stripslashes($aAppDetails[0]['appname']);?></strong> has been installed successfully.
</div>
<p>You can now start developing your application:</p>
<p><strong>Login:</strong> <a href="<?php print stripslashes($aAppDetails[0]['baseurl']);?>/delight-ide/sign.php"><?php print stripslashes($aAppDetails[0]['baseurl']);?>/delight-ide/sign.php</a><br/>
<strong>Username:</strong> admin<br/>
<strong>Password:</strong> delight</p>
<p>Remember to change the password after signing in.</p>
<p><strong>Happy application development!</strong></p></div>