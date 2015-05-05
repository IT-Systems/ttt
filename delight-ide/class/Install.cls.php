<?php
/*------------------------------------------------------------------------------+
 * Opendelight - A PHP based Rapid Web Application Development Framework        |
 * (c)Copyright ADII Research & Applications (P) Limited. All rights reserved.  |
 * Author: Ashwini Kumar Rath                                                   |
 * Website of Opendelight: http://www.adiipl.com/opendelight                    |
 * Licensed under the terms of the GNU General Public License Version 2 or later|
 * (the "GPL"): http://www.gnu.org/licenses/gpl.html                            |
 * NOTE: The copyright notice like this on any of the distributed files         |
 *       (downloaded or obtained as the part of Opendelight) must NOT be        |
 *       removed or modified.                                                   |
 *------------------------------------------------------------------------------+
 */
class Install
{
	/* CONSTRUCTOR */
	public function __construct() 
	{		
		//global $APP;	       
		return true;
	}
	
	public function createApplication()
	{
	    $sSysFile = "sys.inc";
		$sFileName = '../'.$sSysFile.'.php';
		unlink($sFileName);
		$sFileName = '../'.$sSysFile.'.php';	
	    if(!file_exists($sFileName)) 
		{
			if(!$handle = fopen($sFileName, 'w+'))
			{
		        $sMessage = 'Error Log File '.$sFileName.' Could Not Opened!';
		        $bError = true;
			}
	    }
		else
		{
		   if (!$handle = fopen($sFileName, 'a')) 
		   {
		        $sMessage = "Cannot open file ($sFileName)";
		        $bError = true;
			}
		}  
				$sContent  = '<?php
/* Do not edit - it is created by Opendelight installation. */
$_DELIGHT["DSN"]                    = "'.$_POST["txtDSN"].'";
$_DELIGHT["DBUSER"]       		    = "'.$_POST["txtDbUser"].'";
$_DELIGHT["DBPASSWORD"]             = "'.$_POST["txtDatabasePwd"].'";
$_DELIGHT["TABLE_PREFIX"]			= "'.$_POST["txtTablePrefix"].'";
?>
';   	
	   if (fwrite($handle, $sContent) === FALSE) 
	   {			   
	       $sMessage = "Cannot write to file ($sFileName)";
	       $bError = true;
	   }	
	   if($sMessage == '')
	   {
		   include '../sys.inc.php';
		   try
			{
				$DB = new PDO("{$_DELIGHT['DSN']}", "{$_DELIGHT['DBUSER']}", "{$_DELIGHT['DBPASSWORD']}");	
			}
			catch (PDOException $oException) 
			{
				 print "Connection error: " . $oException->getMessage();
			}
		   $this->DB  = $DB;
		   $sTablePrefix = $_DELIGHT["TABLE_PREFIX"];
		   $DELIGHT_CONFIG = "CREATE TABLE IF NOT EXISTS `{$sTablePrefix}od_config` (
									  `configid` int(11) NOT NULL AUTO_INCREMENT,
									  `configname` varchar(255) NOT NULL,
									  `description` varchar(255) NOT NULL,
									  `configvalue` varchar(255) NOT NULL,
									  `sortorder` int(11) NOT NULL,
									  PRIMARY KEY (`configid`)
									)";
		   $oStmt = $this->DB->prepare($DELIGHT_CONFIG);
		   $oStmt->execute();
		   
		   $DELIGHT_CONTROLLER = "CREATE TABLE IF NOT EXISTS `{$sTablePrefix}od_controller` (
	  										`ctrid` int(11) NOT NULL auto_increment,
	  										`ctrname` varchar(255) NOT NULL default '',
	 										`ispublic` enum('0','1') NOT NULL default '0',
											`defaulteventid` int(11) NOT NULL default '0',
	  										`sortorder` int(11) NOT NULL default '0',
	  										`ctrstatus` enum('0','1') NOT NULL default '1',
											 `signinctrid` int(11) NOT NULL default '0',
											 `lastupdate` datetime NOT NULL default '0000-00-00 00:00:00',
											 PRIMARY KEY  (`ctrid`)
											)";
		    $oStmt = $this->DB->prepare($DELIGHT_CONTROLLER);
		    $oStmt->execute();
									
		    $DELIGHT_EVENT = "CREATE TABLE IF NOT EXISTS `{$sTablePrefix}od_event` (
									  `eventid` int(11) NOT NULL auto_increment,
									  `eventname` varchar(255) NOT NULL default '',
									  `ctrid` int(11) NOT NULL default '0',
									  `sortorder` int(11) NOT NULL default '0',
									  `estatus` enum('0','1') NOT NULL default '0',
									  `eventverifier` varchar(255) NOT NULL default '',
									  `formrules` longtext NOT NULL,
									  `blcode` longtext NOT NULL,
									  `viewparts` text NOT NULL,
									  `pagevars` text NOT NULL,
									  `roles` varchar(255) NOT NULL default '',
									  `lastupdate` datetime NOT NULL default '0000-00-00 00:00:00',
									  PRIMARY KEY  (`eventid`)
									)";
		    $oStmt = $this->DB->prepare($DELIGHT_EVENT);
		    $oStmt->execute();
					
			$DELIGHT_PAGEVARS = "CREATE TABLE IF NOT EXISTS `{$sTablePrefix}od_pagevars` (
										  `pagevarid` int(11) NOT NULL AUTO_INCREMENT,
										  `pagevarkey` varchar(255) NOT NULL,
										  PRIMARY KEY (`pagevarid`)
										)";
			$oStmt = $this->DB->prepare($DELIGHT_PAGEVARS);
		    $oStmt->execute();
					
			$DELIGHT_ROLE = "CREATE TABLE IF NOT EXISTS `{$sTablePrefix}od_role` (
									  `roleid` int(11) NOT NULL AUTO_INCREMENT,
									  `rolename` varchar(255) NOT NULL DEFAULT '',
									  `sortorder` int(11) NOT NULL DEFAULT '0',
									  `defaultctrid` int(11) NOT NULL,
									  `defaulteventid` int(11) NOT NULL,
									  PRIMARY KEY (`roleid`)
									)";
			$oStmt = $this->DB->prepare($DELIGHT_ROLE);
		    $oStmt->execute();
									
			$DELIGHT_SYS = "CREATE TABLE IF NOT EXISTS `{$sTablePrefix}od_sys` (
									  `sysid` int(11) NOT NULL auto_increment,
									  `appname` varchar(255) NOT NULL default '',
									  `author` varchar(255) NOT NULL default '',
									  `description` text NOT NULL,
									  `baseurl` varchar(255) NOT NULL default '',
									  `logstatus` enum('0','1') NOT NULL default '1',
									  `sysstatus` enum('0','1') NOT NULL default '1',
									  PRIMARY KEY  (`sysid`)
									)";
			$oStmt = $this->DB->prepare($DELIGHT_SYS);
		    $oStmt->execute();
									
			$DELIGHT_USER = "CREATE TABLE IF NOT EXISTS `{$sTablePrefix}od_user` (
									  `userid` int(11) NOT NULL AUTO_INCREMENT,
									  `idverifier` varchar(255) NOT NULL DEFAULT '',
									  `username` varchar(255) NOT NULL DEFAULT '',
									  `password` varchar(255) NOT NULL DEFAULT '',
									  `email` varchar(255) NOT NULL DEFAULT '',
									  `firstname` varchar(255) NOT NULL DEFAULT '',
									  `lastname` varchar(255) NOT NULL DEFAULT '0',
									  `userstatus` enum('0','1','2') NOT NULL DEFAULT '0',
									  `roleid` int(11) NOT NULL DEFAULT '0',
									  `lastlogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
									  PRIMARY KEY (`userid`)
									)";
			$oStmt = $this->DB->prepare($DELIGHT_USER);
		    $oStmt->execute();
									
			//INSERT RECORD FOR DEFAULT USER
			$sLastLogin  = gmdate("Y-m-d H:i:s");
		    $iSortOrder	 = 1;
		    $sAuthor     = $_POST['txtAuthor'];
		    $aAuthor     = explode(' ',$sAuthor);
		    $sFirstName  = trim($aAuthor[0]);
		    $sLastName   = trim($aAuthor[1]);
			$sQry  =  "INSERT INTO {$sTablePrefix}od_user (idverifier, username, password, email, firstname, lastname, userstatus, roleid, lastlogin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->bindParam(1, $sVal1);
			$oStmt->bindParam(2, $sVal2);
			$oStmt->bindParam(3, $sVal3);
			$oStmt->bindParam(4, $sVal4);
			$oStmt->bindParam(5, $sVal5);
			$oStmt->bindParam(6, $sVal6);
			$oStmt->bindParam(7, $sVal7);
			$oStmt->bindParam(8, $sVal8);
			$oStmt->bindParam(9, $sVal9);
			$sVal1 = uniqid();
			$sVal2 = 'admin';
			$sVal3 = md5('delight');
			$sVal4 = $_POST['txtEmail'];
			$sVal5 = $sFirstName;
			$sVal6 = $sLastName;
			$sVal7 = '1';
			$sVal8 = 1;
			$sVal9 = $sLastLogin;
			$oStmt->execute();
			
			//INSERT RECORD FOR DEFAULT ROLE
			$sQry  =  "INSERT INTO {$sTablePrefix}od_role (rolename, sortorder, defaultctrid, defaulteventid) VALUES (?, ?, ?, ?)";
	        $oStmt = $this->DB->prepare($sQry);
	        $oStmt->bindParam(1, $sRoleName);
			$oStmt->bindParam(2, $sSortOrder);
			$oStmt->bindParam(3, $sDefaultctrid);
			$oStmt->bindParam(4, $sDefaulteventid);
			$sRoleName = 'Administrator';
			$sSortOrder = 1;
			$sDefaultctrid = 2;
			$sDefaulteventid = 6;
			$oStmt->execute();
			
			//INSERT RECORD FOR APPLICATION DETAILS
			$sQry  =  "INSERT INTO {$sTablePrefix}od_sys (appname, author, description, baseurl) VALUES (?, ?, ?, ?)";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->bindParam(1, $sAppName);
			$oStmt->bindParam(2, $sAuthor);
			$oStmt->bindParam(3, $sDescription);
			$oStmt->bindParam(4, $sBaseURL);
			$sAppName = $_POST['txtAppName'];
			$sAuthor  = $_POST['txtAuthor'];
			$sDescription = $_POST['taAppDesc'];
			$sBaseURL     = $_POST['txtBaseURL'];
			$oStmt->execute();
			
			//INSERT RECORDS FOR PAGEVARS
			$sQry  = "INSERT INTO {$sTablePrefix}od_pagevars (pagevarkey) VALUES (?)";
	        $oStmt = $this->DB->prepare($sQry);
	        $oStmt->bindParam(1, $sPageVar);
			$sPageVar = 'TITLE';
			$oStmt->execute();
			$sPageVar = 'HEADERTEXT';
			$oStmt->execute();
			$sPageVar = 'BREADCRUMB';
			$oStmt->execute();
			
			//INSERT RECORDS FOR CONTROLLER				
			$sQry  =  "INSERT INTO {$sTablePrefix}od_controller VALUES('1', 'sign.php', '1', '1', '1', '1', '0', '$sLastLogin')";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->execute();
            
			$sQry  =  "INSERT INTO {$sTablePrefix}od_controller VALUES('2', 'index.php', '0', '6', '2', '1', '1', '$sLastLogin')";
	        $oStmt = $this->DB->prepare($sQry);				
			$oStmt->execute();

			//INSERT RECORDS FOR EVENT				
			$sQry  =  "INSERT INTO {$sTablePrefix}od_event VALUES('1', 'SignIn', '1', '1', '1', '', '', '', '''header-sign.tpl.php'', ''signin.tpl.php'', ''footer-sign.tpl.php''', 'User Sign In', '1', '$sLastLogin')";
	        $oStmt = $this->DB->prepare($sQry);				
			$oStmt->execute();
			
			$sFormRule = '"txtUsername" => array("validate" => 1, "validation_type" => "required", "reg_exp" => "", "error_message" => "This field is required", "sanitize" => 1, "sanitize_type" => "safe"),"txtPassword" => array("validate" => 1, "validation_type" => "required", "reg_exp" => "", "error_message" => "This field is required", "sanitize" => 1, "sanitize_type" => "safe")';
			$sBLcode = 'if ($_REQUEST["hidStatus"]) \r\n{\r\n    $oSign = new Delight_Sign();\r\n    $oForm = new Delight_Form($_POST, $APP->FORMRULES);\r\n    $aForm = iterator_to_array($oForm);				\r\n    $sErrMsg = $oSign->signin($aForm, $oSign->sLoginToken);\r\n}';
			$sQry  =  "INSERT INTO {$sTablePrefix}od_event VALUES('2', 'Validate', '1', '2', '1', '', '$sFormRule', '$sBLcode', '''header-sign.tpl.php'', ''signin.tpl.php'', ''footer-sign.tpl.php''', 'User Sign In', '1', '$sLastLogin')";
	        $oStmt = $this->DB->prepare($sQry);				
			$oStmt->execute();
			
			$sQry  =  "INSERT INTO {$sTablePrefix}od_event VALUES('3', 'Password', '1', '3', '1', '', '', '', '''header-sign.tpl.php'', ''forgot-pwd.tpl.php'', ''footer-sign.tpl.php''', 'Forgot your password?', '1', '$sLastLogin')";
	        $oStmt = $this->DB->prepare($sQry);				
			$oStmt->execute();
			
			$sFormRule = '';
			$sBLcode = 'if ($_REQUEST["hidPwd"]) \r\n{\r\n    $oSign = new Delight_Sign();\r\n    $oSign->getPassword($_REQUEST["txtEmailId"]);\r\n}';
			$sQry  =  "INSERT INTO {$sTablePrefix}od_event VALUES('4', 'retrievePassword', '1', '4', '1', '', '$sFormRule', '$sBLcode', '''header-sign.tpl.php'', ''forgot-pwd.tpl.php'', ''footer-sign.tpl.php''', 'Forgot your password?', '1', '$sLastLogin')";
	        $oStmt = $this->DB->prepare($sQry);				
			$oStmt->execute();
			
			$sFormRule = '';
			$sBLcode = 'session_start();\r\n    session_unset();\r\n    session_destroy();\r\n    header("Location: $_SERVER[PHP_SELF]");\r\n    exit();';
			$sQry  =  "INSERT INTO {$sTablePrefix}od_event VALUES('5', 'SignOut', '1', '5', '1', '', '$sFormRule', '$sBLcode', '''header-sign.tpl.php'', ''signin.tpl.php'', ''footer-sign.tpl.php''', '', '1', '$sLastLogin')";
	        $oStmt = $this->DB->prepare($sQry);				
			$oStmt->execute();
			
			$sQry  =  "INSERT INTO {$sTablePrefix}od_event VALUES('6', 'Default', '2', '6', '1', '', '', '', '''header-main.tpl.php'', ''hello-world.tpl.php'', ''footer-main.tpl.php''', '', '1', '$sLastLogin')";
	        $oStmt = $this->DB->prepare($sQry);				
			$oStmt->execute();
	   }	    
	   return $sMessage;	
	}
}//End of class

?>