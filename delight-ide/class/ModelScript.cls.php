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
class ModelScript
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
	
	function listScriptIncludes() 
	{
		$sPath = '../model/script/';
		$aRowData = $this->dirList($sPath);	
		return $aRowData;
	}
	
	private function dirList($dir)
    {
    	$dir_handle  = opendir($dir);
        $dir_objects = array();
        while ($object = readdir($dir_handle))
        if (!in_array($object, array('.','..')))
        {
            $filename    = $dir . $object;
            $file_object = array(
                                     'name' => $object,
                                     'size' => ceil(filesize($filename)/1024),                                           
                                     'time' => date("d F Y, g:i a", (filemtime($filename)+60*60*5.5))
                                 );
            $dir_objects[] = $file_object;
         }               
         return $dir_objects;
    }
    
    function listEachFile($sSIName)
    {
    	$sFileName = '../model/script/'.$sSIName;	   
		$handle    = fopen($sFileName, "r");
		$sContents  = fread($handle, filesize($sFileName));
		fclose($handle);
		return $sContents;		
    }
    
    /* get List Page Message */
	function getListPageMessage()
	{
		$aMsg[0] = 0;
		if ($_GET['PF'] == '1') $aMsg[1] = "The new Script Include <strong>{$_GET['PC']}</strong> has successfully been added.";
		else if ($_GET['PF'] == '2') $aMsg[1] = "The Script Include <strong>{$_GET['PC']}</strong> has successfully been edited.";
		else if ($_GET['PF'] == '3') $aMsg[1] = "The Script Include <strong>{$_GET['PC']}</strong> has successfully been deleted.";
		else $aMsg[1] = "The list of Script Includes have been presented below. Click on <strong>Edit</strong> icon to edit.";
		return $aMsg;
	}
	
	/* Add Script Includes */
	function addSIName() 
	{
	    if($_POST['hidAddStatus'])
		{
			$sSIName = $_POST['txtSIName'];
	        $sFileName  = '../model/script/'.$sSIName;	
		    if(!file_exists($sFileName)) 
		    {
                $sContent   = '';			   			
			    $aMsg = $this->writeFile($sFileName, $sContent);			
	   		    if($aMsg[0] == 0) 
			    {	
		   		    $sPath = $_SERVER['PHP_SELF'].'?ID=35&PF=1&PC='.$_POST['txtSIName'];	
				    header("Location: $sPath");
				    exit();
			    }	
			    else
			    {
				    $aMsg[0] = 1;
			        $aMsg[1] = "The default content could not be written into the Script Includes file.";
			        return $aMsg;
			    }
		    }
		    else
		    {
		 	    $aMsg[0] = 1;
			    $aMsg[1] = "The Script Include file already exists.";
			    return $aMsg;
		    }
	    }
	    else
	    {
		    $aMsg[0] = 0;
			$aMsg[1] = "Please fill up the following form to create a new Script Includes.";
			return $aMsg;
	    }
	    return $aMsg;
	}
    
	/* Edit Script Includes */
    function editSIFile() 
	{
	    if($_POST['hidEditStatus'])
		{
			$sSIName          = $_POST['txtSIName'];
			if($_POST['hidTextArea']) $sBusinessLogic = $_POST['hidTextArea'];
			else $sBusinessLogic = $_POST['taBusinessLogic'];
			/*
			$sOrigClassName      = $_POST['hidClassName'];		
			if($sOrigClassName != $sSIName)
			{
			   $sOrigFileName  = '../model/script/'.$sOrigClassName.'.cls.php';							
			   rename($sOrigFileName, $sFileName);			
			}
			else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The Script Includes file already exists.";
			    return $aMsg;
			}
			*/
			$sFileName  = '../model/script/'.$sSIName;	
			$aMsg = $this->writeFile($sFileName, $sBusinessLogic);	
		    if($aMsg[0] == 0) 
		   	{	
	   		    $sPath = $_SERVER['PHP_SELF'].'?ID=35&PF=2&PC='.$_POST['txtSIName'];	
				header("Location: $sPath");
				exit();
		   	}	
		   	else return $aMsg;
	   	}
	   	else
	   	{
		   	$aMsg[0] = 0;
			$aMsg[1] = "Please edit Script Includes in the form below. An opensource code editor has been provided too.";
	   	}
	   	return $aMsg;
	}
	
	/* Write the Class File Contents to the disk */
	private function writeFile($sFileName, $sContent)
	{
	    $aMsg[0] = 0;
	    $aMsg[1] = '';
	    if(!$handle = fopen($sFileName, 'w+'))
		{
		    $aMsg[0] = 1;
			$aMsg[1] = "The Script Includes file could not be created.";
			return $aMsg;
		}
        if (is_writable($sFileName)) 
		{	    
		   if (fwrite($handle, $sContent) === FALSE) 
		   {
			   $aMsg[0] = 1;
			   $aMsg[1] = "Cannot write to file ($sFileName).";
			   return $aMsg;
		   }		   
		   fclose($handle);
		   return 3; 					
		} 
		else
		{
			$aMsg[0] = 1;
			$aMsg[1] = "The Script Includes file {$sFileName} is not writable.";
			return $aMsg;
		}
		return $aMsg;
	}
	
	/* Delete Script Includes */
	function deleteSIFile($sFile)
	{
		if($sFile)
		{
			$sFileName  = '../model/script/'.$sFile;
			if(unlink($sFileName))
			{
				$sPath = $_SERVER['PHP_SELF'].'?ID=35&PF=3&PC='.$sFile;	
				header("Location: $sPath");
				exit();
			}
			else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The Script Includes file {$sFile} could not be deleted.";
			    return $aMsg;
			}
		}
		else
		{
			$aMsg[0] = 1;
			$aMsg[1] = "System access error!";
			return $aMsg;
		}
		return $aMsg;
	}
} //END OF CLASS
?>