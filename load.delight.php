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
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$fTimeStart = microtime(true);
/* Include configuration file */
require_once dirname(__FILE__).'/sys.inc.php';
try
{
	$DB = new PDO("{$_DELIGHT['DSN']}", "{$_DELIGHT['DBUSER']}", "{$_DELIGHT['DBPASSWORD']}");	
	$DB->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
    $sQry     = "SET NAMES utf8";
    $oStmt    = $DB->prepare($sQry);
    $oStmt->execute();
}
catch (PDOException $oException) 
{
	 print "Connection error: " . $oException->getMessage();
}
function __autoload($sClassName)
{
	if(file_exists(dirname(__FILE__).'/lib/'.$sClassName . '.cls.php')) require_once (dirname(__FILE__).'/lib/'.$sClassName . '.cls.php');
	elseif(file_exists(dirname(__FILE__).'/model/class/'.$sClassName . '.cls.php')) require_once (dirname(__FILE__).'/model/class/'.$sClassName . '.cls.php');
    else trigger_error("Unable to load class: $sClassName", E_USER_WARNING);
}
$APP  = new Delight_APP();
if($APP->bIsPublic != 1)
{
	$USER = new Delight_USER();
	$APP->initLog($fTimeStart,$USER->USERNAME);
}
else
{
	$APP->initLog($fTimeStart,0);
}
/************************************************
 * Delight Application Class
************************************/
class Delight_APP
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
    private $iRunID;
    private $fTimeStart;
    private $iLogStstus;
    private $iCtrId;
    private $sCtrName;
    
	/* CONSTRUCTOR */
	public function __construct() 
	{
		global $DB, $_DELIGHT;
		$this->DB 	  = $DB;
		$this->TABLEPREFIX = $_DELIGHT['TABLE_PREFIX'];           //Determine Table Prefix
		$this->iCtrId = $_DELIGHT[CTRID];
		$this->sCtrName = $this->getCtrName();
		array_walk($_REQUEST, array('Delight_APP','sanitize'));   //Sanitise  $_REQUEST
		$this->ID = $this->getAppId();						      // Determine Event ID			
		$this->getSysVars(); 				                      // Get System Variables
		$this->checkIfPublic();                                   //Determine Module Access
		$this->getConfigVars();                                   //Determine Config variables		
		return true;
	}

	/* Application Data Sanitization*/
	static function sanitize(&$sVal, $sKey)
	{
		if (!is_array($sVal)) {
			$sVal = stripslashes(trim($sVal));
		}
		return $sVal;
	}
	
	/* Get Controller Name */
	private function getCtrName()
	{
		$sQry     = "SELECT ctrname FROM {$this->TABLEPREFIX}od_controller WHERE ctrid = :ctrid";
	    $oStmt    = $this->DB->prepare($sQry); 
	    $oStmt->bindParam(':ctrid',$this->iCtrId); 
		$oStmt->execute();
		$iNumRows  = $oStmt->rowCount();
		if($iNumRows == 1)
		{
			$aNumRows    	   = $oStmt->fetchAll();
			return $aNumRows[0][0];
		}
	}
        
        /**
         * Palauttaa controllerin ID:n
         * @return int 
         * @author IT-Systems
         */
        public function getCtrid() {
            return (int) $this->iCtrId;
        }
	
	/* Determine Event ID */
	private function getAppId()
	{
		if($_REQUEST['ID'] == '' && $_REQUEST['IDN'] == '') $iAPPID = $this->getDefaultEventId();
		elseif($_REQUEST['ID'])  $iAPPID = $_REQUEST['ID'];
		elseif($_REQUEST['IDN'])
		{
			$iMatch = preg_match("#[^a-z0-9\-]#i", $_REQUEST['IDN']);
			if($iMatch == 0) 
			{
			  $sQry     = "SELECT eventid FROM {$this->TABLEPREFIX}od_event WHERE eventname = :eventname";
			  $oStmt    = $this->DB->prepare($sQry); 
			  $oStmt->bindParam(':eventname',$_REQUEST['IDN']); 
			  $oStmt->execute();
			  $aRows    = $oStmt->fetchAll();
			  if(sizeof($aRows) == 1)
		      {
				  $iAPPID = stripslashes($aRow[0]['eventid']);
			  }
			  else
			  {
				  print 'System Error!';
				  exit();
			  }
			}
		 }
	     else
		 {
		 	header("HTTP/1.0 404 Not Found");
			exit();		 
		 }		 
		 if($iAPPID)
		 {
		      $sQry = "SELECT eventverifier FROM {$this->TABLEPREFIX}od_event WHERE eventid = :eventid";
			  $oStmt = $this->DB->prepare($sQry); 
			  $oStmt->bindParam(':eventid', $iAPPID); 
			  $oStmt->execute();
			  $aRows = $oStmt->fetchAll();
		      foreach($aRows as $aRow)
			  {
				  $sAPPEventVerifier = stripslashes($aRow['eventverifier']);
			  }
			  if($sAPPEventVerifier == '') return  $iAPPID;
			  else
			  {
			      if (eval("return($sAPPEventVerifier);")) return  $iAPPID;
			      else
				  {
					header("HTTP/1.0 404 Not Found");
					exit();		 
				  }
			  }			 
		 }
		 else return  false;	 
	}
	
	/* Get System Variables */
	private function getSysVars()
	{		
		$sQry = "SELECT sysid,appname,author,description,baseurl,logstatus,sysstatus  FROM {$this->TABLEPREFIX}od_sys";
		$oStmt = $this->DB->prepare($sQry);
		$oStmt->execute();
		$aRow = $oStmt->fetchAll();
		if(sizeof($aRow) == 1)
		{
			$this->BASEURL    = stripslashes($aRow[0]['baseurl']);
			if($_SERVER[QUERY_STRING]) $this->URL = $APP->BASEURL.'/'.$this->sCtrName.'?'.$_SERVER[QUERY_STRING];
			else
			{
				$iDefault      = $this->getDefaultEventId();
			    $this->URL     = $this->BASEURL.'/'.$this->sCtrName.'?ID='.$iDefault;
			}
			$this->iLogStatus = stripslashes($aRow[0]['logstatus']);
			if (stripslashes($aRow[0]['sysstatus']) == 0)
			{
				print stripslashes($aRow[0]['appname']).' has been deactivated.';
				exit();
			}
		}
		else
		{
			print 'System Error!';
			exit();
		}
		return true;
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
	
	/* Determine Default Event ID */
	private function getDefaultEventId()
	{
		$sQry     = "SELECT defaulteventid FROM {$this->TABLEPREFIX}od_controller WHERE ctrid = :ctrid";
		$oStmt    = $this->DB->prepare($sQry); 
	    $oStmt->bindParam(':ctrid', $this->iCtrId); 
	    $oStmt->execute();
	    $aRows    = $oStmt->fetchAll();
	    foreach($aRows as $aRow)
	  	{
		 	$iDefault = stripslashes($aRow['defaulteventid']);
	  	}
	    return $iDefault;	
	}
	
	/*Determine Module Access*/
	private function checkIfPublic()
	{
       $sQry     = "SELECT ispublic FROM {$this->TABLEPREFIX}od_controller WHERE ctrid = :ctrid";
       $oStmt    = $this->DB->prepare($sQry); 
	   $oStmt->bindParam(':ctrid',$this->iCtrId); 
	   $oStmt->execute();
	   $aRows    = $oStmt->fetchAll();
	   foreach($aRows as $aRow)
	      {
			 $this->bIsPublic = stripslashes($aRow['ispublic']);
		  }
	  return $this->bIsPublic;   
	}
	
	/* Initialise Log */
	function initLog($fTimeStart,$sUsername)
	{
		if ($this->iLogStstus == 1)
		{
			$this->fTimeStart = $fTimeStart;
			$sAccessTime = date("d F Y, g:i a");
			$sAccessIP   = $_SERVER[REMOTE_ADDR];
			$sLogFileName = dirname(__FILE__).'/log/'.date("Y-n-j").'.txt';
			if(!$handle = fopen($sLogFileName, 'a+'))
			{
			    print 'System Error!';
			    exit();
			}
			if ($sUsername == '0') $sLogContent = $sAccessTime.' - Application accessed publicly through URL '.$this->URL.' from IP '.$sAccessIP.".\n";
			else $sLogContent = $sAccessTime.' - Application accessed by user '.$sUsername.' through URL '.$this->URL.' from IP '.$sAccessIP.".\n";
			if (is_writable($sLogFileName)) 
			{	    
			   if (fwrite($handle, $sLogContent) === FALSE) 
			   {
				   print 'System Error!';
			       exit();
			   }
			} 
			else
			{
				print 'System Error!';
			    exit();
			}
			fclose($handle);
		}
		return true;
	}
	
	/* Update Log */
	function updateLog()
	{
		if ($this->iLogStstus == 1)
		{
			$fTimeEnd = microtime(true);
	        $fElapsedTime = $fTimeEnd - $this->fTimeStart;
	        $sDeliveryTime = date("d F Y, g:i a");
			$sLogFileName = dirname(__FILE__).'/log/'.date("Y-n-j").'.txt';
			if(!$handle = fopen($sLogFileName, 'a+'))
			{
			    print 'System Error!';
			    exit();
			}
			$sLogContent = $sDeliveryTime.' - Application executed in '.$fElapsedTime." sec.\n";
			if (is_writable($sLogFileName)) 
			{	    
			   if (fwrite($handle, $sLogContent) === FALSE) 
			   {
				   print 'System Error!';
			       exit();
			   }
			} 
			else
			{
				print 'System Error!';
			    exit();
			}
			fclose($handle);
		}
		return true;
	}
}//End of class Delight_APP

/************************************************
 * Delight User Class
************************************/
class Delight_USER
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
	public function __construct() 
	{		
		global $DB, $APP, $_DELIGHT;
		$this->DB  = $DB;
		$this->APP = $APP;
		$this->iCtrId       = $_DELIGHT[CTRID];
		$this->ID           = (empty($_SESSION['USERID'])) ? '' : $_SESSION['USERID'];
		$this->IDVERIFIER   = (empty($_SESSION['IDVERIFIER'])) ? '' : $_SESSION['IDVERIFIER'];		
		$this->sRequestUrl  = $APP->URL;
		if($this->validate()) return true;
	}
	
	private function validate()
	{
	   if ($this->ID == '' || $this->IDVERIFIER == '')
	   {
		   $bAllowAccess  = false;
	   }
	   else
	   {
		   $_SESSION['REQUESTURL'] == '';
		   $sQry		   = "SELECT firstname, lastname, username, email, roleid, lastlogin FROM {$this->APP->TABLEPREFIX}od_user WHERE userid = :userid AND userstatus = '1' AND idverifier = :idverifier";
		   $oStmt 		   = $this->DB->prepare($sQry); 
		   $oStmt->bindParam(':userid',$this->ID);
		   $oStmt->bindParam(':idverifier',$this->IDVERIFIER);
		   $oStmt->execute();
		   $iRecordNumber  = $oStmt->rowCount();
		   if($iRecordNumber == 1)
		   {
				$aRows    	   = $oStmt->fetchAll();
				foreach($aRows as $aRow)
				{
				    $this->iRole      = stripslashes($aRow['roleid']);			    
				    $sQry  = "SELECT roles FROM {$this->APP->TABLEPREFIX}od_event WHERE eventid = :eventid";
			   	    $oStmt = $this->DB->prepare($sQry); 
					$oStmt->bindParam(':eventid', $this->APP->ID);
					$oStmt->execute();
					$iNumRows  = $oStmt->rowCount();
					if($iNumRows == 1)
				    {
				        $aNumRows    	   = $oStmt->fetchAll();
				        foreach($aNumRows as $aNumRow)
						{
						    $sRoles   = $aNumRow['roles'];
						}
						$aRoles = explode(',', $sRoles);
					    if (!in_array($this->iRole, $aRoles)) $bAllowAccess  = false;
		                else
		                {
		                    $this->USERNAME   = stripslashes($aRow['username']);
		                    $this->EMAIL      = stripslashes($aRow['email']);
						    $this->FULLNAME   = stripslashes($aRow['firstname']).' '.stripslashes($aRow['lastname']);
						    $this->LASTLOGIN  = stripslashes($aRow['lastlogin']);
		                    $bAllowAccess  = true;
		                }	
				    }
				    else $bAllowAccess  = false; 							
				}				   
			}
			else $bAllowAccess  = false;
		}
		if($bAllowAccess) return true;
		else
		{
			$_SESSION['REQUESTURL'] = $this->sRequestUrl;
			$sQry  = "SELECT signinctrid FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid";
		   	$oStmt = $this->DB->prepare($sQry); 
			$oStmt->bindParam(':ctrid', $this->iCtrId);
			$oStmt->execute();
			$aRows = $oStmt->fetchAll();
			foreach($aRows as $aRow)
			{
			    $iSignInCtrlID   = $aRow['signinctrid'];
			}			
			$sQry  = "SELECT ctrname FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid";
		   	$oStmt = $this->DB->prepare($sQry); 
			$oStmt->bindParam(':ctrid', $iSignInCtrlID);
			$oStmt->execute();
			$aRows = $oStmt->fetchAll();
		    foreach($aRows as $aRow)
			{
			    $sSignInCtrlName   = stripslashes($aRow['ctrname']);
			}
			$sRedirectPath = $this->APP->BASEURL.'/'.$sSignInCtrlName;
			header("Location: $sRedirectPath");
			exit();
		}
	}
}//End of Delight_USER class
?>