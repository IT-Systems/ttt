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
class USER
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
	function __construct() 
	{
	    global $DB,$APP;
		$this->DB  = $DB;
		$this->APP = $APP;
		$this->ID           = (empty($_SESSION['USERID'])) ? '' : $_SESSION['USERID'];
		$this->IDVERIFIER   = (empty($_SESSION['IDVERIFIER'])) ? '' : $_SESSION['IDVERIFIER'];		
		$this->sRequestUrl  = $APP->URL;		
		if($this->validate()) return true;
	}
		
	private function validate()
	{
	   if($this->ID == 1) 
	   {	  
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
				    $this->USERNAME   = stripslashes($aRow['username']);
				    if($this->USERNAME != 'admin' || $this->iRole != 1)  $bAllowAccess  = false;
				    else
				    {
					    $sQry		   = "SELECT rolename FROM {$this->APP->TABLEPREFIX}od_role WHERE roleid = :roleid";
			   	        $oStmt 		   = $this->DB->prepare($sQry); 
						$oStmt->bindParam(':roleid',$this->iRole);
					    $oStmt->execute();
					    $iNumRows  = $oStmt->rowCount();	
					    if($iNumRows == 1)
						{
						 	$aNumRows    	   = $oStmt->fetchAll();
						 	foreach($aNumRows as $aNumRow)
							{
								$sRoleName   = stripslashes($aNumRow['rolename']);
							}
						}				    
					    if ($sRoleName != 'Administrator') $bAllowAccess  = false;
		                else
		                {	                    
		                    $this->EMAIL      = stripslashes($aRow['email']);
						    $this->FULLNAME   = stripslashes($aRow['firstname']).' '.stripslashes($aRow['lastname']);
						    $this->LASTLOGIN  = stripslashes($aRow['lastlogin']);
		                    $bAllowAccess  = true;
		                }		
				    }		
				}				   
			}		
			else $bAllowAccess  = false; 
	    }
	    else $bAllowAccess  = false;
		if($bAllowAccess) return true;
		else
		{	
			$_SESSION['REQUESTURL'] = $this->sRequestUrl;
			header("Location: ./sign.php");
			exit();
		}		
	}
} //END OF CLASS
?>