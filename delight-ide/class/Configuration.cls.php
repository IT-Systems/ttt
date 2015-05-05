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
class Configuration
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
		
	/* List the Configuration Variables on Sort Order Page */
    function getConfigSortList() 
	{
		$sSqlData = "SELECT configid, configname, description, configvalue, sortorder FROM {$this->APP->TABLEPREFIX}od_config WHERE configid <> '' ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Add Configuration Vraiable */
	function addConfiguration() 
	{	
		$sSqlData = "SELECT configname FROM {$this->APP->TABLEPREFIX}od_config WHERE configname = '$_POST[txtVariable]'"; 
		$oStmtData= $this->DB->prepare($sSqlData);		
	    $oStmtData->execute();		
		$aRowData = $oStmtData->fetchAll();
		if (count($aRowData) == 0)
		{
			$sQry  = "INSERT INTO {$this->APP->TABLEPREFIX}od_config (configname, description, configvalue, sortorder) VALUES (:configname, :description, :configvalue,'0')";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->bindParam(':configname', $_POST['txtVariable']);
			$oStmt->bindParam(':description', $_POST['txtDescription']);
			$oStmt->bindParam(':configvalue', $_POST['txtValue']);
			if($oStmt->execute())
			{
				$iLastConfigVarID = $this->DB->lastInsertID();
				$sQry  = "UPDATE {$this->APP->TABLEPREFIX}od_config SET sortorder = :sortorder";
				$oStmtUpdate = $this->DB->prepare($sQry);
				$oStmtUpdate->bindParam(':sortorder', $iLastConfigVarID);
				if($oStmtUpdate->execute())
			    {
				    $sPath = $_SERVER['PHP_SELF'].'?ID=50&PF=1';
				    header("Location: $sPath");
				    exit();
			    }
				else
				{
					$aMsg[0] = "Database Error! The IDE failed to execute your request.";
					$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			        $aMsg[2] = "ui-icon ui-icon-alert";
				}
			}
			else
			{
				$aMsg[0] = "Database Error! The IDE failed to execute your request.";
				$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			    $aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The variable cannot be created in duplicate. Please choose a new variable name.";
			$aMsg[1] = "ui-state-error ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}
	
	/* Get details of a Configuration Vraiable */
	function listEachConfiguration($iRecID) 
	{
		$sSqlData = "SELECT configid, configname, description, configvalue FROM {$this->APP->TABLEPREFIX}od_config WHERE configid = :configid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
		$oStmt->bindParam(':configid', $iRecID);
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Edit configuration variable */
	function editConfiguration($iRecID) 
	{   
		$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_config SET description = :description, configvalue = :configvalue WHERE configid = :configid";
		$oStmt = $this->DB->prepare($sQry);
		$oStmt->bindParam(':description', $_POST['txtDescription']);
		$oStmt->bindParam(':configvalue', $_POST['txtValue']);
		$oStmt->bindParam(':configid', $iRecID);
		$oStmt->execute();
		$sPath = $_SERVER['PHP_SELF'].'?ID=50&PF=2&PC='.$_POST['txtVariable'];
		header("Location: $sPath");
		exit();
		return true;		
	}
	
	/* Delete configuration variable */
	function deleteConfiguration($iRecID)
	{
		$sSqlData = "SELECT configname FROM {$this->APP->TABLEPREFIX}od_config WHERE configid = :configid"; 
		$oStmtData= $this->DB->prepare($sSqlData);
		$oStmtData->bindParam(':configid', $iRecID);
	    $oStmtData->execute();		
		$aRowData = $oStmtData->fetchAll();
		if (count($aRowData) == 1)
		{
			$sVariableName = $aRowData[0][configname];
			$sQry = "DELETE from {$this->APP->TABLEPREFIX}od_config WHERE configid = :configid";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->bindParam(':configid', $iRecID);	
			$oStmt->execute();
			$sPath = $_SERVER['PHP_SELF'].'?ID=50&PF=3&PC='.$sVariableName;
			header("Location: $sPath");
			exit();
		}
		return true;
	}
	
	/* Sort the Display Order of Configuration Variables */
    function sortConfiguration()
    {
	    if(isset($_POST['names']) && sizeof($_POST['names'])>0)
	    {	        
	        for($i=0; $i < sizeof($_POST['names']); $i++) 
	        {	           
	            $aConfigList[] = $_POST['names'][$i]; 
	        }
        }
        $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_config SET sortorder = :sortorder WHERE configid = :configid";
		$oStmt = $this->DB->prepare($sQry);
	    for($x = 0; $x < count($aConfigList); $x++)
	    {
	    	$iNewOrderNo = $x + 1;
		    $oStmt->bindParam(':sortorder', $iNewOrderNo);
		    $oStmt->bindParam(':configid', $aConfigList[$x]);		    
		    $oStmt->execute();
	    }
	    $sPath = $_SERVER['PHP_SELF'].'?ID=50&PF=4';
		header("Location: $sPath");
		exit();
   	 	return true;
	 }
} //END OF CLASS
?>