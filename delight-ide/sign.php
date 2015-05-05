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
session_start();
$_ISPUBLIC = true;
//include ('./load.ide.php');
if(!file_exists('../sys.inc.php')) //Check for System Configuration
{
	header("Location: ./install.php");
	exit();
}
/* Include configuration file */
require_once '../sys.inc.php';
if(!count($_DELIGHT))
{
	header("Location: ./install.php");
	exit();
}
include './script/dal.inc.php';
/* Autoload function for class */
function __autoload($sClassName)
{
	require_once (dirname(__FILE__).'/class/'.$sClassName . '.cls.php');
	if (!class_exists($sClassName, false)) trigger_error("Unable to load class: $sClassName", E_USER_WARNING);
}
$APP  = new APP();
/* Move the control to the respective pages according to the "$APP->ID" */
switch($APP->ID)
{
    case 1:
            $IDE         = new IDE();
		    $aAppDetails = $IDE->getAppDetails();
    		$APP->VIEWPARTS         = array('header.login.tpl.php', 'signin.tpl.php', 'footer.main.tpl.php');
    		$APP->PAGEVARS['title'] = 'Signin of opendelight IDE | Delight IDE';
    		$APP->PAGEVARS['headertext'] = 'Signin of opendelight IDE';      		
    break;	
    case 2:
    		if ($_REQUEST['hidStatus']) 
			{
				$oSignIn         = new IDE_Sign();
				$IDE             = new IDE();
				$aAppDetails     = $IDE->getAppDetails();
    			$APP->FORMRULES  = array('txtUsername' => array('validate' => 1, 'validation_type' => 'required', 'reg_exp' => '', 'error_message' => 'This field is required', 'sanitize' => 1, 'sanitize_type' => 'safe'),
							 		'txtPassword' => array('validate' => 1, 'validation_type' => 'required', 'reg_exp' => '', 'error_message' => 'This field is required', 'sanitize' => 1, 'sanitize_type' => 'safe'));				 
				$oForm 		     = new IDE_Form($_POST, $APP->FORMRULES);
				$aForm 		     = iterator_to_array($oForm);				
				$sErrMsg         = $oSignIn->signin($aForm, $oSignIn->sLoginToken);   // If User is valid then store "username" & move to controller file.
				$APP->VIEWPARTS  = array('header.login.tpl.php', 'signin.tpl.php', 'footer.main.tpl.php');
				$APP->PAGEVARS['title'] = 'Signin of opendelight IDE | Delight IDE';
				$APP->PAGEVARS['headertext'] = 'Signin of opendelight IDE';    			
			}
    break;	
	case 3:
	        $IDE         = new IDE();
		    $aAppDetails = $IDE->getAppDetails();
			$APP->VIEWPARTS         = array('header.login.tpl.php', 'forgot-pwd.tpl.php', 'footer.main.tpl.php');
			$APP->PAGEVARS['title'] = 'Forgot password? | Delight IDE';	
			$APP->PAGEVARS['headertext'] = 'Forgot password';		
	break;
	case 4:		
    		if ($_REQUEST['hidPwd']) 
			{
			    $IDE         = new IDE();
		        $aAppDetails = $IDE->getAppDetails();
				$oSignIn                = new IDE_Sign();
				$iMsg                   = $oSignIn->getPassword($_REQUEST['txtEmailId'], stripslashes($aAppDetails[0]['appname']));
				$APP->VIEWPARTS         = array('header.login.tpl.php', 'forgot-pwd.tpl.php', 'footer.main.tpl.php');
				$APP->PAGEVARS['title'] = 'Forgot password? | Delight IDE';
				$APP->PAGEVARS['headertext'] = 'Forgot password';				
			}			
	 break;
	 case 5:			
    		session_start();
			session_unset();
			session_destroy();
			header("Location: ./sign.php?Msg=1");
			exit();		
	 break;	
} //End of switch statement
if(!empty($APP->VIEWPARTS)) 
{
	foreach($APP->VIEWPARTS as $sViewPart) include(dirname(__FILE__).'/html/'.$sViewPart);
}
?>