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
class View
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
	
	/* Get the File Type Texts */
	function getFileTypeTexts()
	{
		if ($_GET['VFT'] == 'html')
		{
			$sFileTypeTexts = 'View Page Part';
		}
		else if ($_GET['VFT'] == 'js')
		{
			$sFileTypeTexts = 'JavaScript File';
		}
		else if ($_GET['VFT'] == 'css')
		{
			$sFileTypeTexts = 'CSS File';
		}
		else
		{
			exit();
		}
		return $sFileTypeTexts;
	}
	
	/* Get Files Path */
	private function getFilesPath()
	{
		if ($_GET['VFT'] == 'html')
		{
			$sPath = '../view/html/';
		}
		else if ($_GET['VFT'] == 'js')
		{
			$sPath = '../view/js/';
		}
		else if ($_GET['VFT'] == 'css')
		{
			$sPath = '../view/css/';
		}
		else
		{
			exit();
		}
		return $sPath;
	}
	
	/* List View Files */
	function listViewFiles() 
	{
		$sPath = $this->getFilesPath();
		$aRowData = $this->dirList($sPath);
		return $aRowData;
	}
	
	/* Get Directory List */
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
    
    /* Get details of File */
    function listEachFile($sFileName)
    {
    	$sPath = $this->getFilesPath();
    	$sFileName = $sPath.$sFileName;	   
		$handle    = fopen($sFileName, "r");
		$sContents  = fread($handle, filesize($sFileName));
		fclose($handle);
		return $sContents;
    }
    
    /* get List Page Message */
	function getListPageMessage()
	{
		$aMsg[0] = 0;
		if ($_GET['PF'] == '1') $aMsg[1] = "The new file <strong>{$_GET['PC']}</strong> has successfully been added.";
		else if ($_GET['PF'] == '2') $aMsg[1] = "The file <strong>{$_GET['PC']}</strong> has successfully been edited.";
		else if ($_GET['PF'] == '3') $aMsg[1] = "The file <strong>{$_GET['PC']}</strong> has successfully been deleted.";
		else $aMsg[1] = "The list of files have been presented below. Click on <strong>Edit</strong> icon to edit.";
		return $aMsg;
	}
	
	/* Add View File */
	function addViewFile() 
	{
	    if($_POST['hidAddStatus'])
		{
			$sPath = $this->getFilesPath();
	        $sFileName  = $sPath.$_POST['txtFileName'];	
		    if(!file_exists($sFileName)) 
		    {
			    $sContent   = '';			   			
			    $aMsg = $this->writeFile($sFileName, $sContent);			
	   		    if($aMsg[0] == 0) 
			    {	
		   		    $sPath = $_SERVER['PHP_SELF'].'?ID=40&VFT='.$_GET['VFT'].'&PF=1&PC='.$_POST['txtFileName'];	
				    header("Location: $sPath");
				    exit();
			    }	
			    else
			    {
				    $aMsg[0] = 1;
			        $aMsg[1] = "The default content could not be written into the Application Class file.";
			        return $aMsg;
			    }
		    }
		    else
		    {
		 	    $aMsg[0] = 1;
			    $aMsg[1] = "The Application Class file already exists.";
			    return $aMsg;
		    }
	    }
	    else
	    {
		    $aMsg[0] = 0;
			$aMsg[1] = "Please fill up the following form to create a file.";
			return $aMsg;
	    }
	    return $aMsg;
	}
    
	/* Edit Application Class */
    function editViewFile() 
	{
	    if($_POST['hidEditStatus'])
		{
			$sPath = $this->getFilesPath();
			$sFileName          = $_POST['txtFileName'];
			if($_POST['hidTextArea']) $sFileContent = $_POST['hidTextArea'];
			else $sFileContent = $_POST['taCodeEdit'];
			$sFileName  = $sPath.$sFileName;	
			/*
			$sOrigClassName      = $_POST['hidClassName'];		
			if($sOrigClassName != $sClassName)
			{
			   $sOrigFileName  = $sPath.$sOrigClassName;							
			   rename($sOrigFileName, $sFileName);			
			}
			else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The View file already exists.";
			    return $aMsg;
			}
			*/
			$aMsg = $this->writeFile($sFileName, $sFileContent);	
		    if($aMsg[0] == 0) 
		   	{	
	   		    $sPath = $_SERVER['PHP_SELF'].'?ID=40&VFT='.$_GET['VFT'].'&PF=2&PC='.$_POST['txtFileName'];	
				header("Location: $sPath");
				exit();
		   	}	
		   	else return $aMsg;
	   	}
	   	else
	   	{
		   	$aMsg[0] = 0;
			$aMsg[1] = "Please edit file in the form below. An opensource code editor has been provided too.";
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
			$aMsg[1] = "The View file could not be created.";
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
			$aMsg[1] = "The View file {$sFileName} is not writable.";
			return $aMsg;
		}
		return $aMsg;
	}
	
	/* Delete Application Class */
	function deleteViewfile($sFile)
	{
		if($sFile)
		{
			$sPath = $this->getFilesPath();
			$sFileName  = $sPath.$sFile;
			if(unlink($sFileName))
			{
				$sPath = $_SERVER['PHP_SELF'].'?ID=40&VFT='.$_GET['VFT'].'&PF=3&PC='.$sFile;	
				header("Location: $sPath");
				exit();
			}
			else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The View file {$sFile} could not be deleted.";
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