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
class Pagevars
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
	
	/* List of view page variables */
	function listPage() 
	{
		$sSqlData = "SELECT pagevarid, pagevarkey FROM {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarid <> ''"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	} 
	
	/* Add View Page Variable */
    function addPagevars() 
	{	
		$sSqlData = "SELECT pagevarkey FROM {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarkey = '$_POST[txtPagevarKey]'"; 
		$oStmtData= $this->DB->prepare($sSqlData);		
	    $oStmtData->execute();		
		$aRowData = $oStmtData->fetchAll();
		if (count($aRowData) == 0)
		{
			$sQry  =  "INSERT INTO {$this->APP->TABLEPREFIX}od_pagevars SET pagevarkey = :pagevarkey";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->bindParam(':pagevarkey', $_POST['txtPagevarKey']);
			if($oStmt->execute())
			{
				$sPath = $_SERVER['PHP_SELF'].'?ID=90&PF=1';
				header("Location: $sPath");
				exit();
			}
			else
			{
				$aMsg[0] = "Database Error! The IDE failed to execute your request.";
				$aMsg[1] = "ui-state-error ui-corner-all";
				$aMsg[2] = "ui-icon ui-icon-alert";
			}
		}
		else
		{
			$aMsg[0] = "The variable cannot be created in duplicate. Please choose a new variable name.";
			$aMsg[1] = "ui-state-error ui-corner-all";
			$aMsg[2] = "ui-icon ui-icon-alert";
		}
		return $aMsg;
	}  
	
	/* Edit View Page Variable */
	public function editPagevars($iRecID) 
	{   
		$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_pagevars SET pagevarkey = :pagevarkey WHERE pagevarid = :pagevarid";
		$oStmt = $this->DB->prepare($sQry);
		$oStmt->bindParam(':pagevarkey', $_POST['txtPagevarKey']);
		$oStmt->bindParam(':pagevarid', $iRecID);
		$oStmt->execute();	
		return true;		
	}
	
	/* Get details of a View Page Variable */
	public function listEachPagevars($iRecID) 
	{
		$sSqlData = "SELECT pagevarid, pagevarkey FROM {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarid = :pagevarid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
		$oStmt->bindParam(':pagevarid', $iRecID);
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Delete a view page variable */
	function deletePagevars($iRecID)
	{
		$sSqlData = "SELECT pagevarkey FROM {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarid = :pagevarid"; 
		$oStmtData= $this->DB->prepare($sSqlData);
		$oStmtData->bindParam(':pagevarid', $iRecID);
	    $oStmtData->execute();		
		$aRowData = $oStmtData->fetchAll();
		if (count($aRowData) == 1)
		{
			$sVariableKey = $aRowData[0][pagevarkey];
			$sQry = "DELETE from {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarid = :pagevarid";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->bindParam(':pagevarid', $iRecID);	
			$oStmt->execute();
			$sPath = $_SERVER['PHP_SELF'].'?ID=90&PF=3&PC='.$sVariableKey;
			header("Location: $sPath");
			exit();
		}
		return true;	
	}	
} //END OF CLASS
?>