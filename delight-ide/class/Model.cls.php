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
class Model
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
	
	function listAppClass() 
	{
		$sPath = '../model/class/';
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
    
    function listEachClass($sClassName)
    {
    	$sFileName = '../model/class/'.$sClassName.'.cls.php';	   
		$handle    = fopen($sFileName, "r");
		$sContents  = fread($handle, filesize($sFileName));
		fclose($handle);
		return $sContents;		
    }
    
    /* get List Page Message */
	function getListPageMessage()
	{
		$aMsg[0] = 0;
		if ($_GET['PF'] == '1') $aMsg[1] = "The new application class <strong>{$_GET['PC']}</strong> has successfully been added.";
		else if ($_GET['PF'] == '2') $aMsg[1] = "The application class <strong>{$_GET['PC']}</strong> has successfully been edited.";
		else if ($_GET['PF'] == '3') $aMsg[1] = "The application class <strong>{$_GET['PC']}</strong> has successfully been deleted.";
		else $aMsg[1] = "The list of application classes have been presented below. Click on <strong>Edit</strong> icon to edit.";
		return $aMsg;
	}
	
	/* Add Application Class */
	function addClassName() 
	{
	    if($_POST['hidAddStatus'])
		{
			$sClassName = $_POST['txtClassName'];
	        $sFileName  = '../model/class/'.$sClassName.'.cls.php';	
		    if(!file_exists($sFileName)) 
		    {
$sContent   = '<?php 
class '.$sClassName.'
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
} //End Of Class Statement
?>
';			   			
			    $aMsg = $this->writeClass($sFileName, $sContent);			
	   		    if($aMsg[0] == 0) 
			    {	
		   		    $sPath = $_SERVER['PHP_SELF'].'?ID=30&PF=1&PC='.$_POST['txtClassName'];	
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
			$aMsg[1] = "Please fill up the following form to create a new application class.";
			return $aMsg;
	    }
	    return $aMsg;
	}
    
	/* Edit Application Class */
    function editAppClass() 
	{
	    if($_POST['hidEditStatus'])
		{
			$sClassName          = $_POST['txtClassName'];
			if($_POST['hidTextArea']) $sBusinessLogic = $_POST['hidTextArea'];
			else $sBusinessLogic = $_POST['taBusinessLogic'];
			$sFileName  = '../model/class/'.$sClassName.'.cls.php';	
			/*
			$sOrigClassName      = $_POST['hidClassName'];		
			if($sOrigClassName != $sClassName)
			{
			   $sOrigFileName  = '../model/class/'.$sOrigClassName.'.cls.php';							
			   rename($sOrigFileName, $sFileName);			
			}
			else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The Application Class file already exists.";
			    return $aMsg;
			}
			*/
			$sFileName  = '../model/class/'.$sClassName.'.cls.php';	
			$aMsg = $this->writeClass($sFileName, $sBusinessLogic);	
		    if($aMsg[0] == 0) 
		   	{	
	   		    $sPath = $_SERVER['PHP_SELF'].'?ID=30&PF=2&PC='.$_POST['txtClassName'];	
				header("Location: $sPath");
				exit();
		   	}	
		   	else return $aMsg;
	   	}
	   	else
	   	{
		   	$aMsg[0] = 0;
			$aMsg[1] = "Please edit application class in the form below. An opensource code editor has been provided too.";
	   	}
	   	return $aMsg;
	}
	
	/* Write the Class File Contents to the disk */
	private function writeClass($sFileName, $sContent)
	{
	    $aMsg[0] = 0;
	    $aMsg[1] = '';
	    if(!$handle = fopen($sFileName, 'w+'))
		{
		    $aMsg[0] = 1;
			$aMsg[1] = "The Application Class file could not be created.";
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
			$aMsg[1] = "The Application Class file {$sFileName} is not writable.";
			return $aMsg;
		}
		return $aMsg;
	}
	
	/* Delete Application Class */
	function deleteAppClass($sClass)
	{
		if($sClass)
		{
			$sFileName  = '../model/class/'.$sClass.'.cls.php';
			if(unlink($sFileName))
			{
				$sPath = $_SERVER['PHP_SELF'].'?ID=30&PF=3&PC='.$sClass;	
				header("Location: $sPath");
				exit();
			}
			else
			{
				$aMsg[0] = 1;
			    $aMsg[1] = "The Application Class file {$sClass} could not be deleted.";
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