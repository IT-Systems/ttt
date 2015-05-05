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
class IDE
{
	/* CONSTRUCTOR */
	public function __construct() 
	{		
		global $DB,$APP,$USER;
		$this->DB   = $DB;
		$this->APP  = $APP;
		$this->USER = $USER;		       
		return true;
	}
	
	/* Validate */
	public function getAppDetails()
	{		
		$sQry   = "SELECT appname, author, description,baseurl FROM {$this->APP->TABLEPREFIX}od_sys WHERE sysid <> ''";
		$oStmt  = $this->DB->prepare($sQry); 		
		$oStmt->execute();
		$aAppDetails  = $oStmt->fetchAll();
		return $aAppDetails;		
	}
	
	public function getUsersDetails()
	{		
		$sQry   = "SELECT userid, username, password, email, firstname, lastname, roleid, lastlogin FROM {$this->APP->TABLEPREFIX}od_user WHERE userid <> ''";
		$oStmt  = $this->DB->prepare($sQry); 		
		$oStmt->execute();
		$aUsersDetails = $oStmt->fetchAll();
		return $aUsersDetails;		
	}

	/* Function to update password for the user*/
	private function updatePassword($sPassword,$iId)
    {        
        $sNewPassword = md5($sPassword);
        $sQry  		  = "UPDATE {$this->APP->TABLEPREFIX}od_user SET password = ? WHERE userid = ?";
	    $oStmt    	  = $this->DB->prepare($sQry);
	    $oStmt->bindParam(1, $sNewPassword);
		$oStmt->bindParam(2, $iId);
		$oStmt->execute();
    	return true;
	}
	
	/* Get Feed Content */
	public function getFeedContent()
	{
		$sURL = "http://www.adiipl.com/blog/topics/opendelight/feed/";
		$oFeed = simplexml_load_file($sURL);
		$aFeedItem = $oFeed->channel->item;
		return $aFeedItem;
	}
	
	/* Get Last Edited Events */
	public function getLatestEvents()
	{
		$sQry   = "SELECT t1.eventid, t1.eventname, t2.ctrid, t2.ctrname FROM {$this->APP->TABLEPREFIX}od_event t1, {$this->APP->TABLEPREFIX}od_controller t2 WHERE t1.ctrid = t2.ctrid ORDER BY t1.lastupdate DESC LIMIT 0,5";
		$oStmt  = $this->DB->prepare($sQry); 		
		$oStmt->execute();
		$aLatestEvents = $oStmt->fetchAll();
		return $aLatestEvents;
	}
	
	/* Get Last Edited Model Classes */
	public function getLatestClasses()
	{
		$sPath = '../model/class/';
		$dir_handle  = opendir($sPath);
        $dir_objects = array();
        while ($object = readdir($dir_handle))
        if (!in_array($object, array('.','..')))
        {
            $filename    = $sPath . $object;
            $file_object = array(
                                     'name' => $object,
                                     'size' => ceil(filesize($filename)/1024),                                           
                                     'time' => date("d F Y, g:i a", (filemtime($filename)+60*60*5.5))
                                 );
            $dir_objects[] = $file_object;
        }
        $aLatestClasses = $dir_objects;
		return $aLatestClasses;
	}
}//End of class

?>