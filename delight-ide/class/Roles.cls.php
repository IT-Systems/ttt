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
class Roles
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
	
	/* List of User Roles */
	function listPage() 
	{
		$sSqlData = "SELECT roleid, rolename, defaultctrid, defaulteventid FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid <> '' ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	} 
	
	/* Add User Role */
    function addRole() 
	{	
		if ($_POST['txtRoleName'] != 'Administrator')
		{
			$sSqlData = "SELECT rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE rolename = :rolename"; 
			$oStmtData= $this->DB->prepare($sSqlData);
			$oStmtData->bindParam(':rolename', $_POST['txtRoleName']);
		    $oStmtData->execute();		
			$aRowData = $oStmtData->fetchAll();
			if (count($aRowData) == 0)
			{
				$sQry  =  "INSERT INTO {$this->APP->TABLEPREFIX}od_role SET rolename = :rolename, defaultctrid = :defaultctrid, defaulteventid = :defaulteventid";
				$oStmt = $this->DB->prepare($sQry);
				$oStmt->bindParam(':rolename', $_POST['txtRoleName']);
				$oStmt->bindParam(':defaultctrid', $_POST['selController']);
				$oStmt->bindParam(':defaulteventid', $_POST['selEvent']);
				$oStmt->execute();
				$sPath = $_SERVER['PHP_SELF'].'?ID=70&PF=1&PC='.$_POST['txtRoleName'];
			    header("Location: $sPath");
				exit();
			}
			else
			{
				$aMsg[0] = "The role name cannot be created in duplicate. Please choose a new role name.";
				$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
				$aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The role name cannot be chosen as <strong>Administrator</strong>. Please choose a new role name.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}  
	
	/* Get details of an User Role */
	function listEachRole($iRecID) 
	{
		$sSqlData = "SELECT roleid, rolename, defaultctrid, defaulteventid FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid = :roleid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
		$oStmt->bindParam(':roleid', $iRecID);
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Edit an user role */
	function editRole($iRecID) 
	{   
		$sSqlFind = "SELECT rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid = :roleid";
	    $oStmtFind= $this->DB->prepare($sSqlFind);
	    $oStmtFind->bindParam(':roleid', $iRecID);
	    $oStmtFind->execute();
		$aExistingRoleData = $oStmtFind->fetchAll();
		$sExistingRoleName = $aExistingRoleData[0][rolename];
		if ($_POST['txtRoleName'] != 'Administrator' || $sExistingRoleName == 'Administrator')
		{
			$sSqlData = "SELECT rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid <> :roleid AND rolename = :rolename"; 
			$oStmtData= $this->DB->prepare($sSqlData);
			$oStmtData->bindParam(':rolename', $_POST['txtRoleName']);
		    $oStmtData->bindParam(':roleid', $iRecID);
		    $oStmtData->execute();		
			$aRowData = $oStmtData->fetchAll();
			if (count($aRowData) == 0)
			{
				$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_role SET rolename = :rolename, defaultctrid = :defaultctrid, defaulteventid = :defaulteventid WHERE roleid = :roleid";
				$oStmt = $this->DB->prepare($sQry);
				$oStmt->bindParam(':rolename', $_POST['txtRoleName']);
				$oStmt->bindParam(':defaultctrid', $_POST['selController']);
				$oStmt->bindParam(':defaulteventid', $_POST['selEvent']);
				$oStmt->bindParam(':roleid', $iRecID);
				$oStmt->execute();
				$sPath = $_SERVER['PHP_SELF'].'?ID=70&PF=2&PC='.$_POST['txtRoleName'];
			    header("Location: $sPath");
			    exit();
			}
			else
			{
				$aMsg[0] = "The role name cannot be created in duplicate. Please choose a new role name.";
				$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
				$aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The role name cannot be chosen as <strong>Administrator</strong>. Please choose a new role name.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}
	
	/* Delete user roles */
	function deleteRole($iRecID)
	{
		$aRoleDetails   = $this->listEachRole($iRecID);
		if($aRoleDetails[0]['rolename'] != 'Administrator')
		{
			$sSqlData = "SELECT rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid = :roleid"; 
			$oStmtData= $this->DB->prepare($sSqlData);
			$oStmtData->bindParam(':roleid', $iRecID);
		    $oStmtData->execute();		
			$aRowData = $oStmtData->fetchAll();
			if (count($aRowData) == 1)
			{
				$sRoleName = $aRowData[0][rolename];
				$sQry = "DELETE from {$this->APP->TABLEPREFIX}od_role WHERE roleid = :roleid";
				$oStmt = $this->DB->prepare($sQry);
				$oStmt->bindParam(':roleid', $iRecID);	
				$oStmt->execute();
				$sPath = $_SERVER['PHP_SELF'].'?ID=70&PF=3&PC='.$sRoleName;
			    header("Location: $sPath");
			    exit();
			}
			else
			{
				$aMsg[0] = "No role has been selected for deletion.";
				$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
				$aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The role <strong>Administrator</strong> cannot be deleted.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}	
	
	/* Get list of events */
	function getEvents() 
	{
		$sSqlData = "SELECT eventid, eventname FROM {$this->APP->TABLEPREFIX}od_event WHERE eventid <> '' ORDER BY eventid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Get list of controllers */
	function getControllers() 
	{
		$sSqlData = "SELECT ctrid, ctrname FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid <> '' ORDER BY ctrid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Sort Display Order of User Roles */
    function sortRoles()
    {
	    if(isset($_POST['names']) && sizeof($_POST['names'])>0)
	    {	        
	        for($i=0; $i < sizeof($_POST['names']); $i++) 
	        {	           
	            $aRolesList[] = $_POST['names'][$i]; 
	        }
	    }
	    $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_role SET sortorder = :sortorder WHERE roleid = :roleid";
		$oStmt = $this->DB->prepare($sQry);
	    for($x = 0; $x < count($aRolesList); $x++)
	    {
	    	$iNewOrderNo = $x+1;
		    $oStmt->bindParam(':sortorder', $iNewOrderNo);
		    $oStmt->bindParam(':roleid', $aRolesList[$x]);		    
		    $oStmt->execute();	    		    	
	    }
	    $sPath = $_SERVER['PHP_SELF'].'?ID=70&PF=4';
		header("Location: $sPath");
		exit();
   	 	return true;
	 }
	 
	 /* Get List of User Roles sorted by display order */
     function getRolesSortList() 
	 {
		 $sSqlData = "SELECT roleid, rolename, sortorder FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid <> '' ORDER BY sortorder"; 
		 $oStmt    = $this->DB->prepare($sSqlData);		
	     $oStmt->execute();		
		 $aRowData = $oStmt->fetchAll();
		 return $aRowData;		
	 }
	 
	 /* Get Default Event for a User Role */
	 function getDefaultEvent($iDefaultEvent) 
	 {
		 $sSqlData = "SELECT eventid, eventname FROM {$this->APP->TABLEPREFIX}od_event WHERE eventid = :eventid"; 
		 $oStmt    = $this->DB->prepare($sSqlData);	
		 $oStmt->bindParam(':eventid', $iDefaultEvent);	
	     $oStmt->execute();
	     $aRowData = $oStmt->fetchAll();
	     foreach($aRowData as $aRow)
		 {
		     $sDefaultEvent = stripslashes($aRow[eventname]);
		 }
		 return $sDefaultEvent;    
	 }
	
	 /* Get default controller */
	 function getDefaultCtr($iDefaultCtr) 
	 {
		 $sSqlData = "SELECT ctrid, ctrname FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid"; 
		 $oStmt    = $this->DB->prepare($sSqlData);	
		 $oStmt->bindParam(':ctrid', $iDefaultCtr);	
	     $oStmt->execute();
	     $aRowData = $oStmt->fetchAll();
	     if (count($aRowData) == 1)
	     {
		     $sDefaultCtr = stripslashes($aRowData[0][ctrname]);
	     }
	     else
	     {
		     $sDefaultCtr = 'SYSTEM ERROR! CONTROLLER NOT FOUND';
	     }
		 return $sDefaultCtr;    
	 }	
	 
	 /* Get list of events for a controller - required for displaying respective events on Add/Edit page of Roles */
     function listEventPage($iCtrID) 
	 {
		 $sSqlData = "SELECT eventid, eventname, roles, estatus FROM {$this->APP->TABLEPREFIX}od_event WHERE ctrid = :ctrid ORDER BY sortorder"; 
	 	 $oStmt    = $this->DB->prepare($sSqlData);	
		 $oStmt->bindParam(':ctrid', $iCtrID);		
	     $oStmt->execute();		
		 $aRowData = $oStmt->fetchAll();
		 return $aRowData;		
	 }
} //END OF CLASS
?>