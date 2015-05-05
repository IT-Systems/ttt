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
class Event
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
	
	/* Find default event */
	function getDefaultEvent($iDefaultEvent) 
	{
		$sSqlData = "SELECT eventid, eventname FROM {$this->APP->TABLEPREFIX}od_event WHERE eventid = :eventid"; 
		$oStmt    = $this->DB->prepare($sSqlData);	
		$oStmt->bindParam(':eventid', $iDefaultEvent);	
	    $oStmt->execute();
	    $aRowData = $oStmt->fetchAll();
	    foreach($aRowData as $aRow)
		{
		    $sDefaultEvent = stripslashes($aRow[eventname]).' ( ID = '.stripslashes($aRow[eventid]).')';
		}
		return $sDefaultEvent;    
	}
	
	/* List events for a controller */
	function listEventPage($iCtrID) 
	{
		$sSqlData = "SELECT eventid, eventname, roles, estatus FROM {$this->APP->TABLEPREFIX}od_event WHERE ctrid = :ctrid ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);	
		$oStmt->bindParam(':ctrid', $iCtrID);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Find role name */
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
				$sRoleName .= stripslashes($aRow[rolename]).', ';
			}
		}
		$sRoleName = rtrim($sRoleName,', ');
		return $sRoleName;				
	}
	
	/* Add event */
	function addEvent()
	{
		if($_POST['hidAddStatus'])
		{
			$sSqlEventName = "SELECT eventname FROM {$this->APP->TABLEPREFIX}od_event WHERE eventname = :eventname AND ctrid = :ctrid";
			$oStmtEventName = $this->DB->prepare($sSqlEventName);
			$oStmtEventName->bindParam(':eventname', $_POST['txtEventName']);
			$oStmtEventName->bindParam(':ctrid', $_POST['hidCtrlID']);
			$oStmtEventName->execute();
			$aEventNameData = $oStmtEventName->fetchAll();
			if (sizeof($aEventNameData) > 0)
			{
				$aMsg[0] = 1;
				$aMsg[1] = "The event name must be unique for a particular controller.";
				return $aMsg;
			}
			$iEventStatus = 1;	
			$iNumRoles = $_POST['hidNumRoles'];
			$sRoles = '1,';
			for($k=1;$k<=$iNumRoles;$k++)
			{
			    $sPostRole = 'chkRole'.$k;
				if($_POST[$sPostRole]) $sRoles .= $_POST[$sPostRole].',';
			}
			$sRoles = rtrim($sRoles,',');
			
			$iNumPagevars = $_POST['hidNumPagevars'];
			$sPagevars = '';
			for($m=1;$m<=$iNumPagevars;$m++)
			{
			    $sPostPagevars = 'txtPagevars'.$m;
			    if($sPostPagevars == '') $sPagevars = '^';
				$sPagevars    .= $_POST[$sPostPagevars].'^';
			}
			$sPagevars = rtrim($sPagevars,'^');
			$sSqlData = "SELECT sortorder FROM {$this->APP->TABLEPREFIX}od_event ORDER BY sortorder DESC";
			$oStmt = $this->DB->prepare($sSqlData);
			$oStmt->execute();
			$aRowData = $oStmt->fetchAll();
			$iSortOrder = stripslashes($aRowData[0]['sortorder']) + 1;
			
			if($_POST['taBusinessLogic']) $sBusinessLogic = $_POST['taBusinessLogic'];
			else  $sBusinessLogic = $_POST['hidTextArea'];
			
			$sQry   =  "INSERT INTO {$this->APP->TABLEPREFIX}od_event SET eventname = :eventname, estatus = :estatus, roles = :roles, ctrid = :ctrid, formrules = :formrules, blcode = :blcode, viewparts =:viewparts, pagevars = :pagevars, sortorder = :sortorder, eventverifier = :eventverifier";
			$oStmt  = $this->DB->prepare($sQry);
			$oStmt->bindParam(':eventname', $_POST['txtEventName']);
			$oStmt->bindParam(':estatus', $iEventStatus);	
			$oStmt->bindParam(':roles', $sRoles);	
			$oStmt->bindParam(':ctrid', $_POST['hidCtrlID']);
			$oStmt->bindParam(':formrules', $_POST['txtFormrules']);	
			$oStmt->bindParam(':blcode', $sBusinessLogic);
			$oStmt->bindParam(':viewparts', $_POST['txtViewParts']);
			$oStmt->bindParam(':pagevars', $sPagevars);	
			$oStmt->bindParam(':sortorder', $iSortOrder);	
			$oStmt->bindParam(':eventverifier', $_POST['txtEventVerifier']);						    
			if($oStmt->execute()) $sMessage = $this->writeEvent($_POST['hidCtrlID']);
			$iInsertedId  = $this->DB->lastInsertId();
			if($_POST['chkDefault']) 
			{
			  $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_controller SET defaulteventid = :defaulteventid WHERE ctrid = :ctrid";
			  $oStmt = $this->DB->prepare($sQry);
			  $oStmt->bindParam(':defaulteventid', $iInsertedId);
			  $oStmt->bindParam(':ctrid', $_POST['hidCtrlID']);	
			  $oStmt->execute();
			}
			$sPath = $_SERVER['PHP_SELF'].'?ID=21&CtrID='.$_POST['hidCtrlID'].'&PF=1&PC='.$_POST['txtEventName'];	
			header("Location: $sPath");
			exit();
		}
		else
		{
			$aMsg[0] = 0;
			$aMsg[1] = "Please fill up the following form to create a new event.";
		}
		return $aMsg;
	 }
	 
	 /* Get role details for role ID */
	 function getRoles() 
	 {
	     $sSqlData = "SELECT roleid, rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid <> '' ORDER BY roleid"; 
		 $oStmt    = $this->DB->prepare($sSqlData);		
	     $oStmt->execute();		
		 $aRowData = $oStmt->fetchAll();
		 return $aRowData;		
	 }
	 
	 /* Get Page variables */
	 function getPagevars() 
	 {
		 $sSqlData = "SELECT pagevarid, pagevarkey FROM {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarid <> ''"; 
		 $oStmt    = $this->DB->prepare($sSqlData);		
	     $oStmt->execute();		
	 	 $aRowData = $oStmt->fetchAll();
		 return $aRowData;		
	  }
	  
	  /* Edit event */
	  function editEvent($iRecID) 
	  {
	      if($_POST['hidEditStatus'])
	      {
		      $iNumRoles = $_POST['hidNumRoles'];
			  $sRoles = '1,';
			  for($k=1; $k<=$iNumRoles; $k++)
			  {
			      $sPostRole = 'chkRole'.$k;
				  if($_POST[$sPostRole]) $sRoles .= $_POST[$sPostRole].',';
			  }
			  $sRoles = rtrim($sRoles,',');
			
			  $iNumPagevars = $_POST['hidNumPagevars'];
			  $sPagevars = '';
			  for($m=1; $m<=$iNumPagevars; $m++)
			  {
			      $sPostPagevars = 'txtPagevars'.$m;
			      if($sPostPagevars == '') $sPagevars = '^';
				  $sPagevars    .= $_POST[$sPostPagevars].'^';
			  }
			  $sPagevars  = rtrim($sPagevars,'^');
	
			  if($_POST['hidTextArea']) $sBusinessLogic = $_POST['hidTextArea'];
			  else $sBusinessLogic = $_POST['taBusinessLogic'];
			
			  $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_event SET eventname = :eventname, roles = :roles, formrules = :formrules, blcode = :blcode, viewparts =:viewparts, pagevars = :pagevars , eventverifier = :eventverifier WHERE eventid = :eventid";
			  $oStmt = $this->DB->prepare($sQry);
			  $oStmt->bindParam(':eventname', $_POST['txtEventName']);	
			  $oStmt->bindParam(':roles', $sRoles);
			  $oStmt->bindParam(':formrules', $_POST['txtFormrules']);	
			  $oStmt->bindParam(':blcode', $sBusinessLogic);
			  $oStmt->bindParam(':viewparts', $_POST['txtViewParts']);	
			  $oStmt->bindParam(':pagevars', $sPagevars);
			  $oStmt->bindParam(':eventverifier', $_POST['txtEventVerifier']);
			  $oStmt->bindParam(':eventid', $iRecID);
			
			  if($oStmt->execute()) $sMessage = $this->writeEvent($_POST['hidCtrlID']);
			  if($_POST['chkDefault']) 
			  {
			      $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_controller SET defaulteventid = :defaulteventid WHERE ctrid = :ctrid";
			      $oStmt = $this->DB->prepare($sQry);
			      $oStmt->bindParam(':defaulteventid', $iRecID);
			      $oStmt->bindParam(':ctrid', $_POST['hidCtrlID']);	
			      $oStmt->execute();
			  }
			  $sPath = $_SERVER['PHP_SELF'].'?ID=21&CtrID='.$_POST['hidCtrlID'].'&PF=2&PC='.$_POST['txtEventName'];	
			  header("Location: $sPath");
			  exit();
		  }
		  else
		  {
			  $aMsg[0] = 0;
			  $aMsg[1] = "Please edit details of the event in the form below.";
		  }
		  return $aMsg;		
	  }
	  
	  /* Delete event */
	  function deleteEvent($iRecID)
	  {
		  if($iRecID)
		  {
			  $sSqlData = "SELECT ctrid, eventname FROM {$this->APP->TABLEPREFIX}od_event WHERE eventid = :eventid"; 
		      $oStmt    = $this->DB->prepare($sSqlData);		
			  $oStmt->bindParam(':eventid', $iRecID);
		      $oStmt->execute();
		      $aRowData = $oStmt->fetchAll();	
		      $iCtrID   = stripslashes($aRowData[0][ctrid]);
		      $sEventName = stripslashes($aRowData[0][eventname]);        
			  $sQry = "DELETE from {$this->APP->TABLEPREFIX}od_event WHERE eventid = :eventid";
			  $oStmt = $this->DB->prepare($sQry);
			  $oStmt->bindParam(':eventid', $iRecID);	
			  if($oStmt->execute()) $aMsg = $this->writeEvent($iCtrID);
			  $sPath = $_SERVER['PHP_SELF'].'?ID=21&CtrID='.$_GET['CtrID'].'&PF=3&PC='.$sEventName;	
			  header("Location: $sPath");
			  exit();
		  }
		  else
		  {
			  $aMsg[0] = 1;
			  $aMsg[1] = 'Wrong system access!';
		  }
		  return $aMsg;	
	  }
	  
	  /* Get controller details */
	  function getController($iCtrID) 
	  {
		  $sSqlData = "SELECT ctrname,defaulteventid FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid"; 
	      $oStmt    = $this->DB->prepare($sSqlData);	
		  $oStmt->bindParam(':ctrid', $iCtrID);		
	      $oStmt->execute();		
		  $aRowData = $oStmt->fetchAll();
		  return $aRowData;		
	  }
	  
	  /* Get events for a controller */
      function getControllerEvents($iCtrID) 
	  {
		  $sSqlData = "SELECT eventid, eventname FROM {$this->APP->TABLEPREFIX}od_event WHERE ctrid = :ctrid"; 
		  $oStmt    = $this->DB->prepare($sSqlData);	
		  $oStmt->bindParam(':ctrid', $iCtrID);	
	      $oStmt->execute();		
		  $aRowData = $oStmt->fetchAll();
		  return $aRowData;		
	  }
	  
	  /* details of an event */
	  function listEachEvent($iRecID) 
	  {
		  $sSqlData = "SELECT eventid, eventname, ctrid, viewparts, pagevars, formrules, blcode, estatus, roles, eventverifier FROM {$this->APP->TABLEPREFIX}od_event WHERE eventid = :eventid"; 
		  $oStmt    = $this->DB->prepare($sSqlData);		
		  $oStmt->bindParam(':eventid', $iRecID);
	      $oStmt->execute();		
		  $aRowData = $oStmt->fetchAll();
		  return $aRowData;		
	  }
	  
	  /* Get event details */
	  function getEvents() 
	  {
		  $sSqlData = "SELECT eventid, eventname FROM {$this->APP->TABLEPREFIX}od_event WHERE eventid <> '' ORDER BY eventid"; 
		  $oStmt    = $this->DB->prepare($sSqlData);		
	      $oStmt->execute();		
		  $aRowData = $oStmt->fetchAll();
		  return $aRowData;		
	  }
	  

	
	  /* Get details of a controller */
	  function listEachController($iRecID) 
	  {
		  $sSqlData = "SELECT ctrid, ctrname, defaulteventid, ispublic, ctrstatus, signinctrid FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid"; 
		  $oStmt    = $this->DB->prepare($sSqlData);		
		  $oStmt->bindParam(':ctrid', $iRecID);
	      $oStmt->execute();		
		  $aRowData = $oStmt->fetchAll();
		  return $aRowData;		
	  }
	
	/* Get list of Controllers - sorted */
	function getCtrlSortList() 
	{
		$sSqlData = "SELECT ctrid, ctrname, sortorder FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid <> '' ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Sort events */
 	function sortEvent()
    {
	    if($_POST['hidArrStatus'])
	    {
		    if(isset($_POST['names']) && sizeof($_POST['names'])>0)
		    {	        
		        for($i=0; $i < sizeof($_POST['names']); $i++) 
		        {	           
		            $aEventList[] = $_POST['names'][$i]; 
		        }
		    }
		    $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_event SET sortorder = :sortorder WHERE eventid = :eventid";
			$oStmt = $this->DB->prepare($sQry);
		    for($x = 0; $x < count($aEventList); $x++)
		    {
		    	$iNewOrderNo = $x+1;
			    $oStmt->bindParam(':sortorder', $iNewOrderNo);
			    $oStmt->bindParam(':eventid', $aEventList[$x]);		    
			    $oStmt->execute();	    		    	
		    }
		    $sPath = $_SERVER['PHP_SELF'].'?ID=21&CtrID='.$_GET['CtrID'].'&PF=4';	
			header("Location: $sPath");
			exit();
	    }
	    else
	    {
		    $aMsg[0] = 0;
			$aMsg[1] = 'Please use up and down arrows to sort the display order of events. This has no other utility except having a display order on IDE.';
	    }
   	 	return $aMsg;
	 }	 
	
	/* Get event sort list */
    function getEventSortList($iCtrID) 
	{
		$sSqlData = "SELECT eventid, eventname, sortorder FROM {$this->APP->TABLEPREFIX}od_event WHERE ctrid = :ctrid ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);	
		$oStmt->bindParam(':ctrid', $iCtrID);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* WRITE EVENT */
	private function writeEvent($iCtrID)
	{
		$sSqlData = "SELECT eventid, eventname, formrules, blcode, viewparts, pagevars FROM {$this->APP->TABLEPREFIX}od_event WHERE ctrid = :ctrid ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
		$oStmt->bindParam(':ctrid', $iCtrID);
		$oStmt->execute();
		$aRows = $oStmt->fetchAll();	
		
		$sSqlData = "SELECT ctrname, ispublic FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
		$oStmt->bindParam(':ctrid', $iCtrID);
	    $oStmt->execute();
	    $aRowData  = $oStmt->fetchAll();	
	    $sCtrlName = stripslashes($aRowData[0][ctrname]);
	       
	    $sSqlData = "SELECT pagevarkey FROM {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarid <> '' ORDER BY pagevarid"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();
	    $aRowPageVars  = $oStmt->fetchAll();
		
	    $sFileName = '../'.$sCtrlName;		
	    if(!file_exists($sFileName)) 
		{
			if(!$handle = fopen($sFileName, 'w+'))
			{
		        $aMsg[0] = 1;
				$aMsg[1] = 'Error Log File '.$sFileName.' Could Not Opened!';
				return $aMsg;
			}
	    }
	    else
		{
		   if (!$handle = fopen($sFileName, 'w+')) 
		   {
		        $aMsg[0] = 1;
				$aMsg[1] = "Cannot open file ($sFileName)";
				return $aMsg;
			}
		}   
		
		if (is_writable($sFileName)) 
		{
		$iCnt = substr_count($sCtrlName, '/');
		if($iCnt == 0) $sCnt = './';
		else
		{
			for($k=0; $k < $iCnt; $k++)
			{
				$sCnt .='../';
			}
		}			
		$sContent  = '<?php
session_start(); 
$_DELIGHT[CTRID]  = '.$iCtrID.';
include "'.$sCnt.'load.delight.php";
/* Move the control to the respective pages according to the Event ID */
switch($APP->ID)
{ ';   		
		foreach($aRows as $aRow)
		{
		    $sContent  .= "\n";
			$sContent  .= '    case '.stripslashes($aRow[eventid]).': //Case for eventname "'.stripslashes($aRow[eventname]).'"'."\n";
			if($aRow[formrules] != '') $sContent  .= '    $APP->FORMRULES = array('.stripslashes($aRow[formrules]).');'."\n";
            if($aRow[blcode] != '') $sContent  .= '    '.stripslashes($aRow[blcode])."\n";
            if($aRow[viewparts] != '') $sContent  .= '    $APP->VIEWPARTS = array('.stripslashes($aRow[viewparts]).');'."\n";
            if($aRow[pagevars] != '')
            {
	            $aPageVarVal = explode('^', stripslashes($aRow[pagevars]));
	            $k = 0;
	            foreach($aRowPageVars as $aRowPageVar)
			    {
					 if($aPageVarVal[$k] != '') $sContent  .= '    $APP->PAGEVARS['.stripslashes($aRowPageVar[pagevarkey]).'] = "'.$aPageVarVal[$k].'";'."\n";
					 $k++;
			    }
            }		    
		    $sContent  .= '    break;';
		    $sContent  .= "\n\n";			
		}
		$sContent  .= '    default:';
		$sContent  .= "\n";
		$sContent  .= '    break;';
		$sContent  .= "\n";
		$sContent  .= '} //End of switch statement
include "'.$sCnt.'load.view.php";
?>
';  			
		    if (fwrite($handle, $sContent) === FALSE) 
		    {
			    $aMsg[0] = 1;
			    $aMsg[1] = "Cannot write to file ($sFileName)";
			    return $aMsg;
			}		   
		    fclose($handle);
		} 
		else 
		{
		   $aMsg[0] = 1;
		   $aMsg[1] = "The file $sFileName is not writable";
		   return $aMsg;
		}
		return $aMsg;	
	}
	
	/* Get Public Controllers */
    function getPublicControllers($iRecID)
	{
	   $iPublic  = 1;
	   $sSqlData = "SELECT ctrid,ctrname FROM {$this->APP->TABLEPREFIX}od_controller WHERE ispublic = :ispublic AND ctrid <> :ctrid"; 
	   $oStmt    = $this->DB->prepare($sSqlData);	
	   $oStmt->bindParam(':ispublic', $iPublic);
	   $oStmt->bindParam(':ctrid', $iRecID);
	   $oStmt->execute();
	   $aRowData = $oStmt->fetchAll();
	   return $aRowData;
	}	
	
	function changeStatus($iStatus, $iCtrlId)
	{
	    if($iStatus == 1) $iChngStatus = 0;
	    elseif($iStatus == 0) $iChngStatus = 1;
		$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_controller SET ctrstatus = :ctrstatus WHERE ctrid = :ctrid";
		$oStmt = $this->DB->prepare($sQry);			
		$oStmt->bindParam(':ctrstatus', $iChngStatus);
		$oStmt->bindParam(':ctrid', $iCtrlId);		
		if($oStmt->execute()) return true;	
		return false;
	}
	
    function changePublic($iPublic, $iCtrlId)
	{
	    if($iPublic == 1) $iChngPublic = 0;
	    elseif($iPublic == 0) $iChngPublic = 1;
		$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_controller SET ispublic = :ispublic WHERE ctrid = :ctrid";
		$oStmt = $this->DB->prepare($sQry);			
		$oStmt->bindParam(':ispublic', $iChngPublic);
		$oStmt->bindParam(':ctrid', $iCtrlId);		
		if($oStmt->execute()) return true;	
		return false;
	}
	
    function changeEventStatus($iStatus, $iEventId)
	{
	    if($iStatus == 1) $iChngStatus = 0;
	    elseif($iStatus == 0) $iChngStatus = 1;
		$sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_event SET estatus = :estatus WHERE eventid = :eventid";
		$oStmt = $this->DB->prepare($sQry);			
		$oStmt->bindParam(':estatus', $iChngStatus);
		$oStmt->bindParam(':eventid', $iEventId);		
		if($oStmt->execute()) return true;	
		return false;
	}
} //END OF CLASS
?>