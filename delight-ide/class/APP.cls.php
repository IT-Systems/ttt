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
class APP
{
    public $ID;
	public $EVENTVERIFIER;
	public $FORMRULES;
	public $VIEWPARTS;
    public $PAGEVARS;
    public $BASEURL;
    public $URL;
    public $CONFIG;
    public $SYS;
    public $bIsPublic;
    public $aRoles;
    public $TABLEPREFIX;
    
	/* CONSTRUCTOR */
	function __construct() 
	{
	    global $DB,$_DELIGHT;
		$this->DB 	  = $DB;
		array_walk($_REQUEST, array('APP','sanitize'));   //Sanitise  $_REQUEST		
		$this->TABLEPREFIX = $_DELIGHT['TABLE_PREFIX'];       //Determine Table Prefix
		$this->DAL = $_DELIGHT['DAL'];                        //Determine Table Prefix
		$this->ID = $this->getAppId();						  //Determine Event ID			
		$this->getBaseUrl(); 				                  //Determine Base Url
		$this->getAppUrl();	                                  //Determine Application Url			
		$this->getConfigVars();                               //Determine Config variables	
		return true;
	}
		
	/* Application Data Sanitization*/
	static function sanitize(&$sVal, $sKey)
	{
		$sVal = stripslashes(trim($sVal));
		return $sVal;
	}	
	
	/* Determine Base Url*/
	private function getBaseUrl()
	{
	    $sQry = "SELECT sysid,baseurl  FROM {$this->TABLEPREFIX}od_sys";
		$oStmt = $this->DB->prepare($sQry);
		$oStmt->execute();
		$aRows = $oStmt->fetchAll();		
		foreach($aRows as $aRow)
		{
			$sBaseUrl    = stripslashes($aRow['baseurl']);
		}
		$this->BASEURL = $sBaseUrl;	
		return $this->BASEURL;
	}
	
	/* Determine Application Url*/
	private function getAppUrl()
	{
		if($_SERVER[QUERY_STRING]) $this->URL     = 'http://'.$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF].'?'.$_SERVER[QUERY_STRING];
		else 
		{
			$iDefault      = 1;
			$this->URL     = 'http://'.$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF].'?ID='.$iDefault;
		}
		return $this->URL;
	}
	
	private function getConfigVars()
	{
		$sQry = "SELECT configid,configname,configvalue FROM {$this->TABLEPREFIX}od_config WHERE configid <> ''";
		$oStmt = $this->DB->prepare($sQry);
		$oStmt->execute();
		$aRows = $oStmt->fetchAll();		
		foreach($aRows as $aRow)
		{
			$sConfigName                = stripslashes($aRow['configname']);
		    $this->CONFIG[$sConfigName] = stripslashes($aRow['configvalue']);
		}	
	}	
	
	/* Determine Event ID*/
	private function getAppId()
	{
		if($_REQUEST['ID'] == '' && $_REQUEST['IDN'] == '') $this->ID  = 1;
		elseif($_REQUEST['ID']) $this->ID  = $_REQUEST['ID'];
		elseif($_REQUEST['IDN'])
		{
			$iMatch = preg_match("#[^a-z0-9\-]#i", $_REQUEST['IDN']);
			if($iMatch == 0) 
			{
			  $sQry     = "SELECT eventid FROM {$this->TABLEPREFIX}od_event WHERE eventname = ?";
			  $oStmt    = $this->DB->prepare($sQry);
			  if ($_DELIGHT["DAL"] == 'PDO')
			  {
				  $oStmt->bindParam(1, $sEventName);
			  }
			  else if ($_DELIGHT["DAL"] == 'MYSQLI')
		      {
				  $oStmt->bind_param("s", $sEventName);
			  }
			  else
			  {
				  print 'No DAL selected';
				  exit();
			  }
			  $oStmt->execute();
			  $aRows    = $oStmt->fetchAll();
			  foreach($aRows as $aRow)
			  {
				 $this->ID = stripslashes($aRow['eventid']);
			  }
			}
		 }
		 else
		 {
		 	header("HTTP/1.0 404 Not Found");
			exit();		 
		 }	
		 return $this->ID;
	}
} //END OF CLASS
?>