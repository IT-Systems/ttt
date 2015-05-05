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
class Sys
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
	
	/* List Application Settings */
	function listPage() 
	{
		$sSqlData = "SELECT sysid, appname, author, description, baseurl, logstatus, sysstatus FROM {$this->APP->TABLEPREFIX}od_sys WHERE sysid <> ''"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}    
	
	/* Add Application Settings - NOT USED ANY MORE */
	function addSys()
	{
	   //$sQry   =  "INSERT INTO {$this->APP->TABLEPREFIX}od_sys SET appname = :appname, author = :author, description = :description, sysstatus = :sysstatus";
	   //$oStmt  = $this->DB->prepare($sQry);
	   //$oStmt->bindParam(':appname', $_POST['txtAppName']);
	   //$oStmt->bindParam(':author', $_POST['txtAuthor']);
	   //$oStmt->bindParam(':description', $_POST['txtDescription']);
	   //$oStmt->bindParam(':sysstatus', $_POST['selStatus']);
	   //$oStmt->execute();	
	   return true;
	}
	
	/* Get details of Application Setting */
	function listEachSys($iRecID) 
	{
		$sSqlData = "SELECT sysid, appname, author, description, logstatus, sysstatus, baseurl FROM {$this->APP->TABLEPREFIX}od_sys WHERE sysid = :sysid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
		$oStmt->bindParam(':sysid', $iRecID);
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Edit Application Settings */
	function editSys($iRecID)
	{
	   $sQry   =  "UPDATE {$this->APP->TABLEPREFIX}od_sys SET appname = :appname, author = :author, description = :description, baseurl = :baseurl, logstatus = :logstatus, sysstatus = :sysstatus WHERE sysid = :sysid";
	   $oStmt  = $this->DB->prepare($sQry);
	   $oStmt->bindParam(':appname', $_POST['txtAppName']);
	   $oStmt->bindParam(':author', $_POST['txtAuthor']);
	   $oStmt->bindParam(':description', $_POST['taDescription']);
	   $oStmt->bindParam(':baseurl', $_POST['txtBaseURL']);
	   $oStmt->bindParam(':logstatus', $_POST['radLog']);
	   $oStmt->bindParam(':sysstatus', $_POST['radStatus']);
	   $oStmt->bindParam(':sysid', $iRecID);
	   $oStmt->execute();
	   $sPath = $_SERVER['PHP_SELF'].'?ID=100&PF=2';
	   header("Location: $sPath");
	   exit();
	   return true;;	
	}
	
	/* Delete Application Settings - NOT IN USE */
	function deleteSys($iRecID)
	{
		//$sQry = "DELETE from {$this->APP->TABLEPREFIX}od_sys WHERE sysid = :sysid";
		//$oStmt = $this->DB->prepare($sQry);
		//$oStmt->bindParam(':sysid', $iRecID);	
		//$oStmt->execute();	
		return true;	
	}
	
} //END OF CLASS
?>