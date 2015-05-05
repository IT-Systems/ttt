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
class IDE_Sign
{
	public $sLoginToken;

	/* CONSTRUCTOR */
	public function __construct() 
	{		
		global $DB,$APP,$USER;
		$this->DB  = $DB;
		$this->APP = $APP;
		$sToken                  = md5(rand());
		$_SESSION['SigninToken'] = $sToken;	
		$this->sLoginToken       = $sToken;         
		return true;
	}
	
	/* Validate */
	public function signin($aForm, $sToken)
	{	
	    $iStatus   = 1;
		if(!($_SESSION['SigninToken'] == $sToken))
		{
			header("Location: ".$this->APP->BASEURL."/delight-ide/sign.php");
			exit();
		}		
		$sUsername     = $aForm['txtUsername']['sanitized_field_value'];
		$sPassword     = md5($aForm['txtPassword']['sanitized_field_value']);
		$sQryUser      = "SELECT userid, idverifier, username, roleid FROM {$this->APP->TABLEPREFIX}od_user WHERE username = ? AND password = ? AND userstatus = '1'";
		$oStmt    	   = $this->DB->prepare($sQryUser);
		$oStmt->bindParam(1, $sUsername);
		$oStmt->bindParam(2, $sPassword);
		$oStmt->execute();		
		$iRecordNumber  = $oStmt->rowCount();
		if($iRecordNumber == 1)
		{
			$aRowsUser  	= $oStmt->fetchAll();
			foreach($aRowsUser as $aRow)
			{
				$iSignInUserID       = stripslashes($aRow[userid]);
				$iSignInUsername     = stripslashes($aRow[username]);
				$iSignInRoleId       = stripslashes($aRow[roleid]);
				if($iSignInUserID != 1 || $iSignInUsername != 'admin' || $iSignInRoleId != 1) $bAllowAccess  = false; 
				else
				{
				     $sSignInVerifier     = stripslashes($aRow[idverifier]);
				     $bAllowAccess  = true;
				}
			}			
		}
		else $bAllowAccess  = false;
		
		if (!$bAllowAccess) 
		{
			$sErrMsg = 'Username/ Password NOT correct!'; 	
			return $sErrMsg;
		}
		else
		{
			$sErrMsg = '';
			$_SESSION['USERID']     = $iSignInUserID;
			$_SESSION['IDVERIFIER'] = $sSignInVerifier;
			if ($_SESSION['REQUESTURL'])
			{				
			   header ("Location: ".$_SESSION[REQUESTURL]);
			   exit();
			}
			else
			{
				header ("Location: ".$this->APP->BASEURL."/delight-ide/index.php");
				exit();
			}
		}		
	}

	/* Forgot your password */
    public function getPassword($sForgotPwdSec,$sAppName)
    {
	    global $sNoReplyEmail;
	    $sWebsiteURL     = $this->APP->BASEURL;
	    $sMsg            = '';
	    $sSqlPwdDetails  = "SELECT userid,firstname,username FROM {$this->APP->TABLEPREFIX}od_user WHERE email = :email";
		$oStmt    	   	 = $this->DB->prepare($sSqlPwdDetails); 
		$oStmt->bindParam(':email',$sForgotPwdSec); 
		$oStmt->execute();
		$aForgotPwdSec   = $oStmt->fetchAll();
	    $iRecordNumber   = $oStmt->rowCount();
	    foreach($aForgotPwdSec as $aRowPwd)
		{
	    	$sFirstName       = stripslashes(trim($aRowPwd['firstname']));
	    	$iId              = stripslashes(trim($aRowPwd['userid']));
	    	$sUsername        = stripslashes(trim($aRowPwd['username']));
	    	$sPwd             = $this->createPassword();
	    	$sUpdatePassword  = $this->updatePassword($sPwd,$iId);
		}	
		if($iRecordNumber == 0) {$iMsg = 1;return $iMsg;}
		else 
		{		    
			// Include file to send the message to the User
			//include $this->APP->BASEURL.'/delight/ide/html/forgot-pwd-msg.tpl.php';
			$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
			$sHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$sHeaders .= 'From: Administrator<Administrator>' . "\r\n";
			$sHeaders .= 'Reply-To: ' . $sNoReplyEmail . "\r\n";
			$sSubject = 'Your Password Has Been Reset for Opendelight IDE at Application '.$sAppName;
			$sMessage  = '<html>
			<body>
			<p><strong>Dear '.$sFirstName.'</strong>,</p>
			<p>Your password has been reset for <strong>Opendelight IDE</strong> at application <strong>'.$sAppName.'</strong>. Please use the following details to signin:</p>
			<p>URL: <a href="'.$sWebsiteURL.'/delight-ide/sign.php" title="'.$sWebsiteURL.'/delight-ide/sign.php">'.$sWebsiteURL.'/delight-ide/sign.php</a><br/>
			<strong>Username:</strong> '.$sUsername.'<br />
			<strong>Password:</strong> '.$sPwd.'</p>
			<p><strong>Opendelight IDE System</strong></p>
			</body>
			</html>';
			if(mail($sForgotPwdSec, $sSubject, $sMessage, $sHeaders))
			{
			    $iMsg = 2;
				//$sMsg  = 'An email has been sent with your sign in details. Please <a href='.$sWebsiteURL.'/delight-ide/sign.php title="Click here">click here</a> to Sign In with the sent details.';
				return $iMsg;
			}			
	    }		    	
	 }
	
	/* Function to create a new password */
	private function createPassword($len = 6)
    {        
    	$chars = uniqid();
	    $s = "";
	    for ($i = 0; $i < $len; $i++) {
	        $int         = rand(0, strlen($chars)-1);
	        $rand_letter = $chars[$int];
	        $s           = $s . $rand_letter;
	    }
	    return $s;
	}	

	/* Function to update password for the user*/
	private function updatePassword($sPassword,$iId)
    {        
        $sNewPassword = md5($sPassword);
        $sQry  		  = "UPDATE {$this->APP->TABLEPREFIX}od_user SET password = :password WHERE userid = :userid";
	    $oStmt    	  = $this->DB->prepare($sQry); 
		$oStmt->bindParam(':password',$sNewPassword);
		$oStmt->bindParam(':userid',$iId); 
		$oStmt->execute();
    	return true;
	}
}//End of class

?>