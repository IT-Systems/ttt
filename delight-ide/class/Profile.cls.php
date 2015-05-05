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
class Profile
{
    public $ID;	
	public $USERNAME;
	public $EMAIL;
	public $FULLNAME;
	public $LASTLOGIN;
	public $IDVERIFIER;
	public $iRole;
	public $sRequestUrl;
	
	/* CONSTRUCTOR */
	function __construct() 
	{
	    global $DB,$APP,$USER;
		$this->DB  = $DB;
		$this->APP = $APP;
		$this->USER = $USER;
		return true;
	}
	
	/* Change Password */
	public function changePassword($iUserId)
	{
	    $sSqlData = "SELECT password FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid";
		$oStmt = $this->DB->prepare($sSqlData);
		$oStmt->bindParam(':userid', $iUserId);
		$oStmt->execute();
		$aRowData = $oStmt->fetchAll();
		$sCurrentPass = $aRowData[0]['password'];
		if($sCurrentPass != md5($_POST['txtCurrentPassword']))
		{
			$aMsg[0] = "The current password you have entered is not correct. Please enter the correct current password.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		else if($_POST['txtNewPassword'] != $_POST['txtRetypePassword'])
		{
			$aMsg[0] = "New Password and Retype New Password do not match.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		else
		{
		    $sQry  		  = "UPDATE {$this->APP->TABLEPREFIX}od_user SET password = :password WHERE userid = :userid";
		    $oStmt    	  = $this->DB->prepare($sQry); 
			$oStmt->bindParam(':password',md5($_POST['txtNewPassword']));
			$oStmt->bindParam(':userid',$iUserId); 
			if($oStmt->execute())
			{
				$sPath = $_SERVER['PHP_SELF'].'?'.$_SERVER[QUERY_STRING].'&PF=1';	
				header("Location: $sPath");
				exit();
			}
		}
		return $aMsg;
	}
} //END OF CLASS
?>