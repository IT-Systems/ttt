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
class Users
{
	/* CONSTRUCTOR */
	function __construct() 
	{
	    global $DB,$APP,$USER;
		$this->DB   = $DB;
		$this->APP  = $APP;	
		$this->USER = $USER;	
		return true;
	}
	
	/* List users */
	function listPage() 
	{
		$sSqlData = "SELECT userid, firstname, lastname, username, email, lastlogin, userstatus, roleid FROM {$this->APP->TABLEPREFIX}od_user WHERE userid <> ''"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Get Role name of user */
	function getRoleName($iRoleIDs) 
	{
		$aRoleIDs  = explode(',', $iRoleIDs);
		$sRoleName = '';
		foreach($aRoleIDs as $iRoleID)
		{
			$sSqlData = "SELECT rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid = :roleid"; 
		    $oStmt    = $this->DB->prepare($sSqlData);	
		    $oStmt->bindParam(':roleid', $iRoleID);	
	        $oStmt->execute();
	        $aRowData = $oStmt->fetchAll();
	        foreach($aRowData as $aRow)
			{
				$sRoleName .= stripslashes($aRow[rolename]).',';
			}
		}
		$sRoleName = rtrim($sRoleName,',');
		return $sRoleName;				
	}
	
	/* Add User */
	function addUser() 
	{
		if ($_POST['txtUsername'] != 'admin')
		{
			$sSqlData = "SELECT username, email FROM {$this->APP->TABLEPREFIX}od_user WHERE username = :username OR email = :email"; 
			$oStmtData= $this->DB->prepare($sSqlData);
			$oStmtData->bindParam(':username', $_POST['txtUsername']);	
		    $oStmtData->bindParam(':email', $_POST['txtEmail']);
		    $oStmtData->execute();		
			$aRowData = $oStmtData->fetchAll();
			if (count($aRowData) == 0)
			{
				$sLastLogin  = gmdate("Y-m-d H:i:s");
				$sUserToken  = uniqid();
				$iUserStatus = '1';		
				$sQry  =  "INSERT INTO {$this->APP->TABLEPREFIX}od_user SET firstname = :firstname, lastname = :lastname, username = :username, email = :email, password = :password, roleid = :roleid, idverifier = :idverifier, userstatus = :userstatus, lastlogin = :lastlogin";
				$oStmt = $this->DB->prepare($sQry);
				$oStmt->bindParam(':firstname', $_POST['txtFirstName']);
				$oStmt->bindParam(':lastname', $_POST['txtLastName']);
				$oStmt->bindParam(':username', $_POST['txtUsername']);	
				$oStmt->bindParam(':email', $_POST['txtEmail']);	
				$oStmt->bindParam(':password', md5($_POST['txtPassword']));	
				$oStmt->bindParam(':roleid', $_POST['selRole']);
				$oStmt->bindParam(':idverifier', $sUserToken);
				$oStmt->bindParam(':userstatus', $iUserStatus);
				$oStmt->bindParam(':lastlogin', $sLastLogin);
				$oStmt->execute();
				$sPath = $_SERVER['PHP_SELF'].'?ID=63&PF=1&PC='.$_POST['txtUsername'];
			    header("Location: $sPath");
			    exit();
			}
			else
			{
				$aMsg[0] = "The username/ email cannot be created in duplicate. Please choose a new username/ email.";
				$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
				$aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The username cannot be chosen as <strong>admin</strong>. Please choose a new username.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}
	
	/* Edit User */
	function editUser($iRecID) 
	{
	    $sSqlFind = "SELECT username, email FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid";
	    $oStmtFind= $this->DB->prepare($sSqlFind);
		$oStmtFind->bindParam(':username', $_POST['txtUsername']);	
	    $oStmtFind->bindParam(':email', $_POST['txtEmail']);
	    $oStmtFind->bindParam(':userid', $iRecID);
	    $oStmtFind->execute();
		$aExistingUserData = $oStmtFind->fetchAll();
		$sExistingUsername = $aExistingUserData[0][username];
		$sExistingUserEmail= $aExistingUserData[0][email];
		if ($_POST['txtUsername'] != 'admin' || $sExistingUsername == 'admin')
		{
			$sSqlData = "SELECT username, email FROM {$this->APP->TABLEPREFIX}od_user WHERE userid <> :userid AND (username = :username OR email = :email)"; 
			$oStmtData= $this->DB->prepare($sSqlData);
			$oStmtData->bindParam(':username', $_POST['txtUsername']);	
		    $oStmtData->bindParam(':email', $_POST['txtEmail']);
		    $oStmtData->bindParam(':userid', $iRecID);
		    $oStmtData->execute();		
			$aRowData = $oStmtData->fetchAll();
			if (count($aRowData) == 0)
			{
				if($_POST['selRole'])  $sQryUpdate1 = ", roleid = :roleid";	
				else $sQryUpdate1 = "";
				if($_POST['txtPassword'])  $sQryUpdate2 = ", password = :password";	
				else $sQryUpdate2 = "";
				
				$sLastLogin  = gmdate("Y-m-d H:i:s");
				$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_user SET firstname = :firstname, lastname = :lastname, username = :username, email = :email,  lastlogin = :lastlogin{$sQryUpdate1}{$sQryUpdate2} WHERE userid = :userid";
				$oStmt = $this->DB->prepare($sQry);
				$oStmt->bindParam(':firstname', $_POST['txtFirstName']);
				$oStmt->bindParam(':lastname', $_POST['txtLastName']);
				$oStmt->bindParam(':username', $_POST['txtUsername']);	
				$oStmt->bindParam(':email', $_POST['txtEmail']);	
				if($_POST['selRole']) $oStmt->bindParam(':roleid', $_POST['selRole']);
				if($_POST['txtPassword']) $oStmt->bindParam(':password', md5($_POST['txtPassword']));	
				$oStmt->bindParam(':lastlogin', $sLastLogin);
				$oStmt->bindParam(':userid', $iRecID);
				$oStmt->execute();
				$sPath = $_SERVER['PHP_SELF'].'?ID=63&PF=2&PC='.$_POST['txtUsername'];
			    header("Location: $sPath");
			    exit();
			}
			else
			{
				$aMsg[0] = "The username/ email cannot be created in duplicate. Please choose a new username/ email.";
				$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
				$aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The username cannot be chosen as <strong>admin</strong>. Please choose a new username.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}
	
	/* Get details of User */
	function listEachUser($iRecID) 
	{
		$sSqlData = "SELECT userid, firstname, lastname, username, email, lastlogin, userstatus, roleid, password FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
		$oStmt->bindParam(':userid', $iRecID);
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Get all Role details */
	function getRoles() 
	{
		$sSqlData = "SELECT roleid, rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid <> '' ORDER BY roleid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Delete User */
	function deleteUser($iRecID)
	{
		if($aEachUsersDetails[0]['username'] != 'admin')
		{
			$sSqlData = "SELECT username FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid"; 
			$oStmtData= $this->DB->prepare($sSqlData);
			$oStmtData->bindParam(':userid', $iRecID);
		    $oStmtData->execute();		
			$aRowData = $oStmtData->fetchAll();
			if (count($aRowData) == 1)
			{
				$sUsername = $aRowData[0][username];
				$sQry = "DELETE from {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid";
				$oStmt = $this->DB->prepare($sQry);
				$oStmt->bindParam(':userid', $iRecID);	
				$oStmt->execute();
				$sPath = $_SERVER['PHP_SELF'].'?ID=63&PF=3&PC='.$sUsername;
			    header("Location: $sPath");
			    exit();
			}
			else
			{
				$aMsg[0] = "No user has been selected for deletion.";
				$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
				$aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The user <strong>admin</strong> cannot be deleted.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}
	
	/* Change status of user */
    function changeStatus($iStatus, $iUserId)
	{
	    if($iStatus == '1') $iChngStatus = '0';
	    elseif($iStatus == '0') $iChngStatus = '1';
		$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_user SET userstatus  = :userstatus  WHERE userid = :userid";
		$oStmt = $this->DB->prepare($sQry);			
		$oStmt->bindParam(':userstatus', $iChngStatus);
		$oStmt->bindParam(':userid', $iUserId);		
		if($oStmt->execute()) print 1;
		else print 2;	
		return true;
	}   
} //END OF CLASS
?>