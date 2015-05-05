<?php
$aFileFullPath = explode("/delight-ide/install.php", $_SERVER[PHP_SELF]);
$sHost         = $_SERVER[HTTP_HOST];
$sBaseUrl      = 'http://'.$sHost.$aFileFullPath[0];
?>
<form name="frmInstall" id="frmInstall" method="post" action="<?php print $_SERVER['PHP_SELF'];?>?ID=2">
			    <div style="margin-top:50px;width:99.9%;">
			    <h3 style="margin-bottom:0px;padding-bottom:0px;">Define Application</h3>
			    </div>
				<div class="formSpacing" style="margin-top:0px;">
				<div class="divApplication">
				<label class="makeBold">Application Name</label><br/>
				<input type="text" name="txtAppName" value=""  id="txtAppName" class="textboxNormal required" style="width:250px;"/>
				</div>
				<div class="divApplication">
				<label class="makeBold">Author</label><br/>
				<input type="text" name="txtAuthor" value=""  id="txtAuthor" class="textboxNormal required" style="width:250px;"/>
				</div>
				<div class="divApplication">
				<label class="makeBold">Application Base URL (without trailing slash '/')</label><br/>
				<input type="text" name="txtBaseURL" value="<?php if($_POST['txtBaseURL']) print $_POST['txtBaseURL']; else print $sBaseUrl;?>"  id="txtBaseURL" class="textboxNormal required" style="width:300px;"/>
				</div>
				<div class="divApplication" style="margin-top:5px;clear:left;width:936px;">
				<div style="float:left;"> 
				<label class="makeBold">Description of Application</label><br/>
				<textarea name="taAppDesc" id="taAppDesc" class="textareaNormal" rows="7" cols="" style="width:565px;"></textarea>
				</div>
				<div style="float:right;"> 
				<label class="makeBold">Email</label><br/>
				<input type="text" name="txtEmail" id="txtEmail" class="textboxNormal required email" value="" style="width:300px;"/>
				</div>
				</div>
				</div>
				<h3 style="margin-bottom:0px;padding-bottom:0px;">Database Settings (Opendelight uses PDO - PHP Data Object)</h3>
				<div class="formSpacing" style="margin-top:0px;">
				<div class="divApplication" id="divDBDSN">
				<label>DSN <span>(e.g., mysql:host=localhost;dbname=newapp)</span></label><br/>
				<input type="text" name="txtDSN" value="" id="txtDSN" class="textboxNormal required" style="width:250px;"/>
				</div>
				<div class="divApplication" id="divDBUser">
				<label>Database Username</label><br/>
				<input type="text" name="txtDbUser" value="" id="txtDbUser" class="textboxNormal required" style="width:250px;"/>
				</div>
				<div class="divApplication" id="divDBPwd">
				<label class="makeBold">Database Password</label><br/>
				<input type="text" name="txtDatabasePwd" value=""  id="txtDatabasePwd" class="textboxNormal required" style="width:250px;"/>
				</div>
				<div class="divApplication">
				<label class="makeBold">Table Prefix</label><br/>
				<input type="text" name="txtTablePrefix" id="txtTablePrefix" class="textboxNormal required" value="app_" style="width:250px;"/>
				</div>
				</div>
			    <div style="clear:both;padding-top:5px;">
				<a href="javascript:void(0);" onclick="javascript:funcInstall();" style="margin:5px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span>Submit</a>
				<input type="hidden" name="hidInstallStatus" value="1"/>
				</div>
</form>
