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
if($_GET['ID']) $iID = $_GET['ID'];
else $iID = 1;
/* Autoload function for class */
function __autoload($sClassName)
{
	require_once (dirname(__FILE__).'/class/'.$sClassName . '.cls.php');
	if (!class_exists($sClassName, false)) trigger_error("Unable to load class: $sClassName", E_USER_WARNING);
}
switch($iID)
{
    case 1:
    		$APP->VIEWPARTS         = array('header.appln.tpl.php', 'setup.application.tpl.php', 'footer.appln.tpl.php');
    		$APP->PAGEVARS['title'] = 'Setup Application';
    		$APP->PAGEVARS['headertext'] = 'Setup Application';      		
    break;
    case 2:
           if($_POST['hidInstallStatus'])
           {
               $oInstall = new Install();
               $sMessage = $oInstall->createApplication();
               if($sMessage == '')
               {
                  $sPath = $_SERVER['PHP_SELF'].'?ID=3';	
				  header("Location: $sPath");
				  exit();
               }
               else
               {
                  $sPath = $_SERVER['PHP_SELF'].'?ID=4';	
				  header("Location: $sPath");
				  exit();
               }
           }
    		$APP->VIEWPARTS         = array('header.appln.tpl.php', 'setup.application.form.tpl.php', 'footer.appln.tpl.php');
    		$APP->PAGEVARS['title'] = 'Setup Application';
    		$APP->PAGEVARS['headertext'] = 'Setup Application';      		
    break;
    case 3:
            include '../sys.inc.php';
            include './script/dal.inc.php';
            $APP             = new APP();
            $IDE             = new IDE();
		    $aAppDetails     = $IDE->getAppDetails();
    		$APP->VIEWPARTS         = array('header.appln.tpl.php', 'success.tpl.php', 'footer.appln.tpl.php');
    		$APP->PAGEVARS['title'] = 'Success!';
    		$APP->PAGEVARS['headertext'] = 'Success!';      		
    break;
    case 4:
            include '../sys.inc.php';
            include './script/dal.inc.php';
            $APP             = new APP();
            $IDE             = new IDE();
		    $aAppDetails     = $IDE->getAppDetails();
    		$APP->VIEWPARTS         = array('header.appln.tpl.php', 'error.tpl.php', 'footer.appln.tpl.php');
    		$APP->PAGEVARS['title'] = 'Error!';
    		$APP->PAGEVARS['headertext'] = 'Error!';      		
    break;
}
if(!empty($APP->VIEWPARTS)) 
{
	foreach($APP->VIEWPARTS as $sViewPart) include(dirname(__FILE__).'/html/'.$sViewPart);
}
?>
