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
class Controllers
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
	
	/* List of controllers */
	function listPage() 
	{
		$sSqlData = "SELECT ctrid, ctrname, defaulteventid, ispublic, ctrstatus FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid <> '' ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
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
	  
	/* WRITE CONTROLLER */
	private function writeController($sCtrlName)
	{
	    $bPos = strpos($sCtrlName,'\\');
		if($bPos === false)
		{
			$aFiles = explode('/',$sCtrlName);
		    if ($aFiles[0] == '')
		    {
			    $aMsg[0] = 1;
			    $aMsg[1] = "Cannot have slash in the begining of controller file with path";
			    return $aMsg;
		    }
		    if (sizeof($aFiles) > 1)
		    {
			    $iTotalFolders = sizeof($aFiles) - 1;
			    $sFolderName   = '../';
			    for ($iFolder = 0; $iFolder < $iTotalFolders; $iFolder++)
			    {
				    $sFolderName .= $aFiles[$iFolder];
				    if (!file_exists($sFolderName)) mkdir($sFolderName, 0777);
				    $sFolderName .= '/';
			    }
			    $sFileName = $sFolderName . $aFiles[$iTotalFolders];
		    }
		    else
		    {
			    $sFileName = '../'.$aFiles[0];
		    }
		    if (!file_exists($sFileName))
		 	{
				if(!$handle = fopen($sFileName, 'w+'))
				{
			        $aMsg[0] = 1;
				    $aMsg[1] = "The file is not writable to disk";
				    return $aMsg;
				}
		    }
		    else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The file already exists.";
			    return $aMsg;
			}
		    $sSqlData = "SELECT ctrid FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrname = :ctrname"; 
			$oStmt    = $this->DB->prepare($sSqlData);		
			$oStmt->bindParam(':ctrname', $sCtrlName);
		    $oStmt->execute();
		    $aRowData = $oStmt->fetchAll();	
		    $iCtrID   = stripslashes($aRowData[0][ctrid]);
			
			if (is_writable($sFileName)) 
			{
						
			    $sContent  = '<?php
session_start(); 
$_DELIGHT[CTRID]  = '.$iCtrID.';
include "'.$sCnt.'load.delight.php";
/* Move the control to the respective pages according to the Event ID */
switch($APP->ID)
{
   default:    		  		
   break;  	
} //End of switch statement
include "'.$sCnt.'load.view.php";
?>
';   
				    if (fwrite($handle, $sContent) === FALSE) 
				    {			   
				        $aMsg[0] = 1;
				        $aMsg[1] = "The file is not writable to disk";
				        return $aMsg;
				    }		   
				    fclose($handle);
				} 
				else 
				{
				    $aMsg[0] = 1;
				    $aMsg[1] = "The file is not writable to disk";
				    return $aMsg;
				}
			}
			else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "Please do not use backslashes as directory separator; rather use forward slash";
		    }
			return $aMsg;
		}
	  
	  /* Add Controller */
	  function addController() 
	  {
		  if($_POST['hidAddStatus'])
		  {
			  $sSqlCtrFind = "SELECT ctrname FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrname = :ctrname"; 
			  $oStmtCtrFind    = $this->DB->prepare($sSqlCtrFind);		
			  $oStmtCtrFind->bindParam(':ctrname', $_POST['txtName']);
		      $oStmtCtrFind->execute();
			  $aCtrFindRowData = $oStmtCtrFind->fetchAll();
			  if (sizeof($aCtrFindRowData) == 0)
			  {
				  $iCtrStatus = 1;	
				  $sSqlData = "SELECT sortorder FROM {$this->APP->TABLEPREFIX}od_controller ORDER BY sortorder DESC";
				  $oStmt = $this->DB->prepare($sSqlData);
				  $oStmt->execute();
				  $aRowData = $oStmt->fetchAll();
				  $iSortOrder = stripslashes($aRowData[0]['sortorder']) + 1;
				  if($_POST['selController'] && $_POST['radPublic'] == '0') $iSignCtrlID = $_POST['selController'];
			      else $iSignCtrlID = '0';
				  $sQry       =  "INSERT INTO {$this->APP->TABLEPREFIX}od_controller SET ctrname = :ctrname, ispublic = :ispublic, ctrstatus = :ctrstatus, sortorder = :sortorder, signinctrid = :signinctrid";
				  $oStmt      = $this->DB->prepare($sQry);
				  $oStmt->bindParam(':ctrname', $_POST['txtName']);
				  $oStmt->bindParam(':ispublic', $_POST['radPublic']);
				  $oStmt->bindParam(':ctrstatus', $iCtrStatus);	
				  $oStmt->bindParam(':sortorder', $iSortOrder);
				  $oStmt->bindParam(':signinctrid', $iSignCtrlID);
				  if($oStmt->execute())
				  {
					  $aMsg = $this->writeController($_POST['txtName']);
					  if ($aMsg[0] != 1)
					  {
						  $sPath = $_SERVER['PHP_SELF'].'?ID=20&PF=1';	
					      header("Location: $sPath");
					      exit();
				      }
				  }
				  else
				  {
					  $aMsg[0] = 1;
					  $aMsg[1] = "New Controller could not be added.";
				  }
			  }
			  else
			  {
				  $aMsg[0] = 1;
				  $aMsg[1] = "The controller with the same name exists. Please select a different file name.";
			  }
		  }
		  else
		  {
			  $aMsg[0] = 0;
			  $aMsg[1] = "Please fill up the following form to create a new controller.";
		  }
		  return $aMsg;
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
	  
	/* WRITE EDIT CONTROLLER */
	private function writeEditController($sExistingCtrlName, $sCtrlName, $iRecID)
	{
		//else unlink the existing file and create a new file and update content
		unlink ('../'.$sExistingCtrlName);
		$aFiles = explode('/',$sExistingCtrlName);
		if (sizeof($aFiles) > 1)
		{
			//unlink folders
			$iMaxFolderIndex = sizeof($aFiles) - 2;
			$sFolderName   = '../';
		    for ($iFolder = $iMaxFolderIndex; $iFolder >= 0; $iFolder--)
		    {
			    $sFolderToDelete = '..';
			    for ($iPath = 0; $iPath <= $iMaxFolderIndex; $iPath++)
			    {
				    $sFolderToDelete .= $sFolderToDelete.'/'.$aFiles[$iPath];
			    }
			    if (!file_exists($sFolderToDelete) && $sFolderToDelete != '..' && $sFolderToDelete != '../')
			    {
				    rmdir($sFolderToDelete);
			    }
			    else
			    {
				    $aMsg[0] = 1;
		            $aMsg[1] = "The directory <strong>{$sFolderToDelete}</strong> could not be deleted.";
		            return $aMsg;
			    }
		    }
		}
		$bPos = strpos($sCtrlName,'\\');
		if($bPos === false)
		{
			$aFiles = array();
			$aFiles = explode('/',$sCtrlName);
		    if ($aFiles[0] == '')
		    {
			    $aMsg[0] = 1;
			    $aMsg[1] = "Cannot have slash in the begining of controller file with path";
			    return $aMsg;
		    }
		    if (sizeof($aFiles) > 1)
		    {
			    $iTotalFolders = sizeof($aFiles) - 1;
			    $sFolderName   = '../';
			    for ($iFolder = 0; $iFolder < $iTotalFolders; $iFolder++)
			    {
				    $sFolderName .= $aFiles[$iFolder];
				    if (!file_exists($sFolderName)) mkdir($sFolderName, 0777);
				    $sFolderName .= '/';
			    }
			    $sFileName = $sFolderName . $aFiles[$iTotalFolders];
		    }
		    else
		    {
			    $sFileName = '../'.$aFiles[0];
		    }
		    if (!file_exists($sFileName))
		 	{
				if(!$handle = fopen($sFileName, 'w+'))
				{
			        $aMsg[0] = 1;
				    $aMsg[1] = "The file is not writable to disk";
				    return $aMsg;
				}
		    }
		    else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The file already exists.";
			    return $aMsg;
			}
			$sSqlData = "SELECT ctrid FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrname = :ctrname"; 
			$oStmt    = $this->DB->prepare($sSqlData);		
			$oStmt->bindParam(':ctrname', $sCtrlName);
		    $oStmt->execute();
		    $aRowData = $oStmt->fetchAll();	
		    $iCtrID   = stripslashes($aRowData[0][ctrid]);	    
			
			$sSqlData = "SELECT eventid, eventname, formrules, blcode, viewparts, pagevars FROM {$this->APP->TABLEPREFIX}od_event WHERE ctrid = :ctrid ORDER BY sortorder"; 
			$oStmt    = $this->DB->prepare($sSqlData);		
			$oStmt->bindParam(':ctrid', $iCtrID);
			$oStmt->execute();
			$aRows = $oStmt->fetchAll();    
		    
		    $sSqlData = "SELECT pagevarkey FROM {$this->APP->TABLEPREFIX}od_pagevars WHERE pagevarid <> '' ORDER BY pagevarid"; 
			$oStmt    = $this->DB->prepare($sSqlData);		
		    $oStmt->execute();
		    $aRowPageVars  = $oStmt->fetchAll();
			if (is_writable($sFileName)) 
			{
				$sContent  = '<?php
session_start(); 
$_DELIGHT[CTRID]  = '.$iRecID.';
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
		    $sContent  .= "\n";			
		}
		$sContent  .= '} //End of switch statement
include "'.$sCnt.'load.view.php";
?>
';  			
		   if (fwrite($handle, $sContent) === FALSE) 
		   {			   
		       $aMsg[0] = 1;
		       $aMsg[1] = "Cannot write to file ($sFileName)";
		   }		   
		   fclose($handle);
		} 
		else 
		{
		    $aMsg[0] = 1;
		    $aMsg[1] = "The file $sFileName is not writable";
		}
		return $aMsg;
		}
		else
		{
			$aMsg[0] = 1;
		    $aMsg[1] = "Please do not use backslashes as directory separator; rather use forward slash";
	    }
		return $aMsg;
	}
	
	  /* Edit Controller */
	  function editController($iRecID) 
	  {
	      if($_POST['hidEditStatus'])
		  {
			  $sSqlData = "SELECT ctrname FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid"; 
			  $oStmt    = $this->DB->prepare($sSqlData);		
			  $oStmt->bindParam(':ctrid', $iRecID);
		      $oStmt->execute();		
			  $aExisRowData = $oStmt->fetchAll();
			  $sExistingCtrlName = stripslashes($aExisRowData[0]['ctrname']);
			  if (strcmp($sExistingCtrlName, $_POST['txtName']) != 0)
			  {
				  $aMsg = $this->writeEditController($sExistingCtrlName, $_POST['txtName'], $iRecID);
				  if ($aMsg[0] == 1) return $aMsg;
				  $sQryName  = "UPDATE {$this->APP->TABLEPREFIX}od_controller SET ctrname = :ctrname WHERE ctrid = :ctrid";
				  $oStmtName = $this->DB->prepare($sQryName);
				  $oStmtName->bindParam(':ctrname', $_POST['txtName']);
				  $oStmtName->bindParam(':ctrid', $iRecID);
				  $oStmtName->execute();
			  }
		      // Update other data to table
		      if($_POST['selEvent']) $sDefaultEvent = $_POST['selEvent'];
		      else $sDefaultEvent = '0';
		    
		      if($_POST['selController'] && $_POST['radPublic'] == '0') $iSignCtrlID = $_POST['selController'];
		      else $iSignCtrlID = '0';
		    
			  $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_controller SET ispublic = :ispublic, ctrstatus = :ctrstatus, defaulteventid = :defaulteventid, signinctrid = :signinctrid WHERE ctrid = :ctrid";
			  $oStmt = $this->DB->prepare($sQry);
			  $oStmt->bindParam(':ispublic', $_POST['radPublic']);	
			  $oStmt->bindParam(':ctrstatus', $_POST['selStatus']);
			  $oStmt->bindParam(':ctrid', $iRecID);
			  $oStmt->bindParam(':defaulteventid', $sDefaultEvent);
			  $oStmt->bindParam(':signinctrid', $iSignCtrlID);
			  $oStmt->execute();
			  $sPath = $_SERVER['PHP_SELF'].'?ID=20&PF=2';	
			  header("Location: $sPath");
			  exit();
		  }
		  else
		  {
			  $aMsg[0] = 0;
			  $aMsg[1] = "Please edit the details of the controller file in the form below. Be careful as it may affect the functioning of the application.";
		  }
		  return $aMsg;		
	  }
	
	/* Delete Controller */
	function deleteController($iRecID)
	{
	    if($iRecID)
		{
			$sSqlData = "SELECT ctrname FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid"; 
			$oStmt    = $this->DB->prepare($sSqlData);		
			$oStmt->bindParam(':ctrid', $iRecID);
		    $oStmt->execute();
		    $aRowData = $oStmt->fetchAll();	
		    $sCtrName   = stripslashes($aRowData[0][ctrname]);
			unlink ('../'.$sCtrName); // Unlink file and not directories in path
			$sQry = "DELETE from {$this->APP->TABLEPREFIX}od_controller WHERE ctrid = :ctrid";
			$oStmt = $this->DB->prepare($sQry);
			$oStmt->bindParam(':ctrid', $iRecID);	
			$oStmt->execute();
			// Delete all events associated with Controller
			$sQryEvents = "DELETE from {$this->APP->TABLEPREFIX}od_event WHERE ctrid = :ctrid";
			$oStmtEvents = $this->DB->prepare($sQryEvents);
			$oStmtEvents->bindParam(':ctrid', $iRecID);	
			$oStmtEvents->execute();
			// Now redirect to List page
			$sPath = $_SERVER['PHP_SELF'].'?ID=20&PF=3&PC='.$sCtrName;	
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
	
	/* Get list of Controllers - sorted */
	function getCtrlSortList() 
	{
		$sSqlData = "SELECT ctrid, ctrname, sortorder FROM {$this->APP->TABLEPREFIX}od_controller WHERE ctrid <> '' ORDER BY sortorder"; 
		$oStmt    = $this->DB->prepare($sSqlData);		
	    $oStmt->execute();		
		$aRowData = $oStmt->fetchAll();
		return $aRowData;		
	}
	
	/* Sort Controllers */
    function sortController()
    {
	    if($_POST['hidArrStatus'])
	    {
		    if(isset($_POST['names']) && sizeof($_POST['names']) > 0)
		    {	        
		        for($i = 0; $i < sizeof($_POST['names']); $i++) 
		        {	           
		            $aCtrlList[] = $_POST['names'][$i]; 
		        }
		    }
		    $sQry  =  "UPDATE {$this->APP->TABLEPREFIX}od_controller SET sortorder = :sortorder WHERE ctrid = :ctrid";
			$oStmt = $this->DB->prepare($sQry);
		    for($x = 0; $x < count($aCtrlList); $x++)
		    {
		    	$iNewOrderNo = $x+1;
			    $oStmt->bindParam(':sortorder', $iNewOrderNo);
			    $oStmt->bindParam(':ctrid', $aCtrlList[$x]);		    
			    $oStmt->execute();	    		    	
		    }
		    $sPath = $_SERVER['PHP_SELF'].'?ID=20&PF=4';	
			header("Location: $sPath");
			exit();
	    }
	    else
	    {
		    $aMsg[0] = 0;
			$aMsg[1] = 'Please use up and down arrows to sort the display order of Controllers. This has no other utility except having a display order on IDE.';
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