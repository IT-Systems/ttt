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
$_ISPUBLIC = false;
/*-------------------------------------------------------------
 * Inclusion of delight self codebase
 *-------------------------------------------------------------*/
if(!file_exists('../sys.inc.php')) //Check for System Configuration
{
	header("Location: ./install.php");
	exit();
}
require_once '../sys.inc.php'; //Include configuration file
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
$APP   = new APP();
$USER  = new USER();
$IDE   = new IDE();
$aAppDetails = $IDE->getAppDetails();
/* Move the control to the respective pages according to the "$APP->ID" */
switch($APP->ID)
{
	case '1': // Dashboard
	    $aLatestEvents = $IDE->getLatestEvents();
	    $aLatestClasses= $IDE->getLatestClasses();
	    $oFeedItems = $IDE->getFeedContent();
		$APP->VIEWPARTS  = array('header.main', 'left-bar','dashboard', 'footer.main');
	    $APP->PAGEVARS['title'] = 'Dashboard | Opendelight IDE';    	
	    $APP->PAGEVARS['headertext'] = 'Opendelight IDE Dashboard';
	    $APP->PAGEVARS['breadcrumb'] = 'Dashboard';   
    break;   
	case '20': // List of Controllers
		$oControllers   = new Controllers();
		$aCtrlDetails   = $oControllers->listPage();	
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'controllers', 'footer.main');
		$APP->PAGEVARS['title'] = 'List of Controllers | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'List of Controllers';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '21': // List of Events
		$oEvent    = new Event();
		$aEventDetails   = $oEvent->listEventPage($_GET['CtrID']);
		$aCtrDetails     = $oEvent->getController($_GET['CtrID']);
		$APP->VIEWPARTS  = array('header.main', 'left-bar', 'events', 'footer.main');
		$APP->PAGEVARS['title'] = 'List of Events for '.stripslashes($aCtrDetails[0]['ctrname']).' | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'List of Events for <em>'.stripslashes($aCtrDetails[0]['ctrname']).'</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo;</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=20&CtrID='.$_GET['CtrID'].'" class="ui-green" title="Controllers">Controllers</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '22': // Add Controller
		$oControllers    = new Controllers();
		$aMsg = $oControllers->addController();
		$iRecID = 0;
		$aPublicControllers = $oControllers->getPublicControllers($iRecID);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'controller-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add Controller | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add Controller';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=20" class="ui-green" title="List of Controllers">List of Controllers</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
    break;
    case '23': // Edit Controller
	    $iRecID              = $_GET['RecID'];
		$oControllers        = new Controllers();
		$aMsg = $oControllers->editController($iRecID);
		$aEachCtrDetails    = $oControllers->listEachController($iRecID);
		$aPublicControllers = $oControllers->getPublicControllers($iRecID);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'controller-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit Controller | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit Controller: <em>'.$APP->BASEURL.'/'.$aEachCtrDetails[0][ctrname].' (CTRID = '.$iRecID.')</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=20" class="ui-green" title="List of Controllers">List of Controllers</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '24': // Add Event to a Controller
		$oEvent    = new Event();          	  	
		$aMsg = $oEvent->addEvent();
		$aCtrDetails     = $oEvent->getController($_GET['CtrID']);   	 
		$aPageVarDetails = $oEvent->getPagevars(); 
		$APP->VIEWPARTS  = array('header.main', 'left-bar', 'events-add', 'footer.main');
	    $APP->PAGEVARS['title'] = 'Add Event for Controller '.stripslashes($aCtrDetails[0]['ctrname']).' | Opendelight IDE';    	
	    $APP->PAGEVARS['headertext'] = 'Add Event for Controller <em>'.stripslashes($aCtrDetails[0]['ctrname']).'</em>';	
	    $APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=20&CtrID='.$_GET['CtrID'].'" class="ui-green" title="Controllers">Controllers</a><span class="ui-menu-seperator">&raquo;</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=21&CtrID='.$_GET[CtrID].'" class="ui-green" title="List of Events for Controller '.stripslashes($aCtrDetails[0][ctrname]).'">List of Events for Controller <em>'.stripslashes($aCtrDetails[0][ctrname]).'</em></a><span class="ui-menu-seperator">&raquo;</span>Add Event';
    break;
    case '25': // Edit Event
		$iRecID = $_GET['RecID'];
		$oEvent = new Event();             
		$aMsg   = $oEvent->editEvent($iRecID);
		$aEachEventDetails      = $oEvent->listEachEvent($iRecID);
		$aCtrDetails            = $oEvent->getController($_GET['CtrID']);	
		$aPageVarDetails 		= $oEvent->getPagevars();	
		$APP->VIEWPARTS         = array('header.main', 'left-bar', 'events-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit Event '.stripslashes($aEachEventDetails[0]['eventname']).' | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit Event: <em>'.stripslashes($aEachEventDetails[0]['eventname']).' ($APP->ID = '.stripslashes($aEachEventDetails[0]['eventid']).')</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=20&CtrID='.$_GET['CtrID'].'" class="ui-green" title="Controllers">Controllers</a><span class="ui-menu-seperator">&raquo;</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=21&CtrID='.$_GET[CtrID].'" class="ui-green" title="List of Events for '.stripslashes($aCtrDetails[0][ctrname]).'">List of Events for '.stripslashes($aCtrDetails[0][ctrname]).'</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;	
	case '26':	// Delete Event	
		$iRecID = $_GET['RecID'];		
		$oEvent = new Event();      		
		$oEvent->deleteEvent($iRecID);
	break;
	case '27': // Sort Controllers
		$oControllers    = new Controllers();          
		$aMsg = $oControllers->sortController();
	    $aSortedCtrlList   = $oControllers->getCtrlSortList();		
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'controllers-arrange', 'footer.main');
		$APP->PAGEVARS['title'] = 'Arrange Controllers | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Arrange Controllers';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=20" class="ui-green" title="List of Controllers">List of Controllers</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '28': // Delete Controller
		$iRecID = $_GET['RecID'];	
		$oControllers    = new Controllers();      		
		$aMsg = $oControllers->deleteController($iRecID);
	break;
	case '29': // Sort events
		$oEvent    = new Event();          
		$aMsg = $oEvent->sortEvent();	
	    $aSortedEventList   = $oEvent->getEventSortList($_GET['CtrID']);	
	    $aCtrDetails        = $oEvent->getController($_GET['CtrID']);	
	    $APP->VIEWPARTS     = array('header.main', 'left-bar', 'events-arrange', 'footer.main');	
		$APP->PAGEVARS['title'] = 'Arrange Events | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Arrange Events for <em>'.stripslashes($aCtrDetails[0]['ctrname']).'</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=20&CtrID='.$_GET['CtrID'].'" class="ui-green" title="Controllers">Controllers</a><span class="ui-menu-seperator">&raquo;</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=21&CtrID='.$_GET[CtrID].'" class="ui-green" title="Manage Events for '.stripslashes($aCtrDetails[0][ctrname]).'">Manage Events for '.stripslashes($aCtrDetails[0][ctrname]).'</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '30': // List Application Classes
		$oModel             = new Model();      	  		
		$aAppClassDetails   = $oModel->listAppClass();
		$aMsg               = $oModel->getListPageMessage();
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'appclass', 'footer.main');
		$APP->PAGEVARS['title'] = 'List of Application Classes | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'List of Application Classes';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span>'.$APP->PAGEVARS['headertext'];
	break;	
	case '31':	// Add Model Class	  
		$oModel = new Model();
		$aMsg = $oModel->addClassName();
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'appclass-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add Application Class | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add Application Class';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=30" class="ui-green" title="List of Application Classes">List of Application Classes</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
    break;
    case '32': // Edit Model Class
		$sClass      = $_GET['Class'];
		$oModel      = new Model();
		$aMsg = $oModel->editAppClass();
		$sClassContent = $oModel->listEachClass($sClass);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'appclass-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit Application Class - '.$sClass.' | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit Application Class - <em>'.$sClass.'</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=30" class="ui-green" title="List of Application Classes">List of Application Classes</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '33': // Delete Model Class
		$sClass = $_GET['Class'];
		$oModel = new Model();   
		$aMsg = $oModel->deleteAppClass($sClass);
	break;
	case '35': // List Script Includes
		$oModelScript             = new ModelScript();      	  		
		$aAppSIDetails   = $oModelScript->listScriptIncludes();
		$aMsg               = $oModelScript->getListPageMessage();
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'scriptincludes', 'footer.main');
		$APP->PAGEVARS['title'] = 'List of Script Includes | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'List of Script Includes';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span>'.$APP->PAGEVARS['headertext'];
	break;	
	case '36':	// Add Script Includes	  
		$oModelScript = new ModelScript();
		$aMsg = $oModelScript->addSIName();
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'scriptincludes-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add Script Include | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add Script Include';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=35" class="ui-green" title="List of Script Includes">List of Script Includes</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
    break;
    case '37': // Edit Script Includes
		$sFile      = $_GET['File'];
		$oModelScript      = new ModelScript();
		$aMsg = $oModelScript->editSIFile();
		$sClassContent = $oModelScript->listEachFile($sFile);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'scriptincludes-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit Script Include File - '.$sFile.' | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit Script Include File - <em>'.$sFile.'</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=35" class="ui-green" title="List of Script Includes">List of Script Includes</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '38': // Delete Script Includes
		$sFile = $_GET['File'];
		$oModelScript = new ModelScript();   
		$aMsg = $oModelScript->deleteSIFile($sFile);
	break;
	case '40': // View UI Files
		$oView             = new View();
		$sFileTypeTexts = $oView->getFileTypeTexts();
		$aViewFileDetails   = $oView->listViewFiles();
		$aMsg               = $oView->getListPageMessage();
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'viewfiles', 'footer.main');
		$APP->PAGEVARS['title'] = 'List of '.$sFileTypeTexts.'s | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'List of '.$sFileTypeTexts.'s';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '41':	// Add View File	  
		$oView = new View();
		$sFileTypeTexts = $oView->getFileTypeTexts();
		$aMsg = $oView->addViewFile();
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'viewfiles-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add '.$sFileTypeTexts.' | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add '.$sFileTypeTexts;
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=40&VFT='.$_GET['VFT'].'" class="ui-green" title="List of '.$sFileTypeTexts.'s">List of '.$sFileTypeTexts.'s</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
    break;
    case '42': // Edit View File
		$sFile      = $_GET['File'];
		$oView      = new View();
		$sFileTypeTexts = $oView->getFileTypeTexts();
		$aMsg = $oView->editViewFile();
		$sFileContent = $oView->listEachFile($sFile);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'viewfiles-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit '.$sFileTypeTexts.' | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit '.$sFileTypeTexts.': <em>'.$_GET['File'].'</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=40&VFT='.$_GET['VFT'].'" class="ui-green" title="List of '.$sFileTypeTexts.'s">List of '.$sFileTypeTexts.'s</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '43': // Delete View File
		$sFile = $_GET['File'];
		$oView = new View();
		$sFileTypeTexts = $oView->getFileTypeTexts();
		$aMsg = $oView->deleteViewfile($sFile);
	break;
    case '50': // List Configuration Variables
		$oConfiguration = new Configuration();          	  		
		$aConfigDetails = $oConfiguration->getConfigSortList();
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'configurations', 'footer.main');
		$APP->PAGEVARS['title'] = 'List of Configuration Variables | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'List of Configuration Variables';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;	
	case '51': // Add Configuration Variable
		$oConfiguration = new Configuration();
		if($_POST['hidAddStatus'] == '1')
		{
			$aMsg = $oConfiguration->addConfiguration();
		}
		else
		{
			$aMsg[0] = "Please fill up the following form to create a new configuration variable. The variable name must be unique.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'configuration-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add Configuration Variable | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add Configuration Variable';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=50" class="ui-green" title="Manage Configuration">Manage Configuration</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '52': // Edit Configuration Variable
		$iRecID              = $_GET['RecID'];
		$oConfiguration      = new Configuration();      
		if($_POST['hidEditStatus'])
		{		
			$oConfiguration->editConfiguration($iRecID);				 
		}
		else
		{
			$aMsg[0] = "Please edit the details of the configuration variable in the form below. You cannot edit the Variable Name";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$aEachConfigDetails   = $oConfiguration->listEachConfiguration($iRecID);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'configuration-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit Configuration Variable | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit Configuration Variable';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=50" class="ui-green" title="Manage Configuration">Manage Configuration</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;	
	case '53': // Delete Application Configuration Variable
		$iRecID = $_GET['RecID'];		
		if($iRecID)
		{
			$oConfiguration      = new Configuration();      		
			$oConfiguration->deleteConfiguration($iRecID);						 
		}		
	break;
	case '54': // Sort the display of Configuration Variables
		$oConfiguration      = new Configuration();      		
		if($_POST['hidArrStatus'])
		{
			$oConfiguration->sortConfiguration();	
		}
		else
		{
			$aMsg[0] = "Please use up and down arrows to sort the display order of configuration variables. This has no other utility except having a display order on IDE.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
	    $aSortedConfigList   = $oConfiguration->getConfigSortList();		
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'configuration-arrange', 'footer.main');
		$APP->PAGEVARS['title'] = 'Sort Display Order of Configuration Variables | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Sort Display Order of Configuration Variables';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=50" class="ui-green" title="Manage Configuration">Manage Configuration</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
    case '60': 
        $APP->VIEWPARTS = array('header.main', 'left-bar', 'users', 'footer.main');
	    $APP->PAGEVARS['title'] = 'USERS | Opendelight IDE';    				
	break; 
    case '61': 
    	$oUsers = new Users();          	  		
		$oUsers->display();		
	break;	
	case '62': 
    	$oUsers = new Users();      	  		
		$oUsers->execute();		
	break;	
	 case '63': 
		$oUsers          = new Users();          	  		
		$aUsersDetails   = $oUsers->listPage();
		$APP->VIEWPARTS  = array('header.main', 'left-bar', 'users', 'footer.main');
	    $APP->PAGEVARS['title'] = 'List of Users | Opendelight IDE'; 
	    $APP->PAGEVARS['headertext'] = 'List of Users';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];   	
    break;	
    case '64': // Add User
		$oUsers          = new Users();
		if($_POST['hidAddStatus'])
		{			      
			$aMsg = $oUsers->addUser();
		}
		else
		{
			$aMsg[0] = "Please fill up the following form to create a new user. The <strong>username</strong> and <strong>email</strong> must be unique.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$APP->VIEWPARTS  = array('header.main', 'left-bar', 'users-add', 'footer.main');
	    $APP->PAGEVARS['title'] = 'Add User | Opendelight IDE';    
	    $APP->PAGEVARS['headertext'] = 'Add User';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=63" class="ui-green" title="List of Users">List of Users</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];	
    break;
    case '65': // Edit User
		$iRecID = $_GET['RecID'];
		$oUsers = new Users();      
		if($_POST['hidEditStatus'])
		{		
			$aMsg = $oUsers->editUser($iRecID);				 
		}
		else
		{
			$aMsg[0] = "Please use the following form to edit user details. The <strong>username</strong> and <strong>email</strong> must be unique.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$aEachUsersDetails   = $oUsers->listEachUser($iRecID);		
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'users-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit User | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit User - <em>'.stripslashes($aEachUsersDetails[0]['username']).'</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=63" class="ui-green" title="List of Users">List of Users</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '66': // Delete User
	    $oUsers = new Users(); 	    		
		$iRecID = $_GET['RecID'];
		$aEachUsersDetails   = $oUsers->listEachUser($iRecID);			
		$aMsg = $oUsers->deleteUser($iRecID);
	break;
	case '67': // Sort Users
		$oUsers = new Users();        		
		if($_POST['hidArrStatus'])
		{
			if($oUsers->sortUsers()) 
			{
				$sPath = $_SERVER['PHP_SELF'].'?ID=63';	
				header("Location: $sPath");
				exit();
			}	
		}		
	    $aSortedUsersList   = $oUsers->getUsersSortList();		
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'users-arrange', 'footer.main');
		$APP->PAGEVARS['title'] = 'Arrange Users | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Arrange Users';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=63" class="ui-green" title="Users">Users</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
   case '70': // List User Roles
		$oRoles         = new Roles();          	  		
		$aRolesDetails  = $oRoles->listPage();	
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'roles', 'footer.main');
		$APP->PAGEVARS['title'] = 'List of User Roles | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'List of User Roles';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;		
	case '71': // Add User Role
		$oRoles      = new Roles();
		if($_POST['hidAddStatus'])
		{			      
			$aMsg = $oRoles->addRole();
		}
		else
		{
			$aMsg[0] = "Please fill up the following form to add an user role.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'role-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add User Role | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add User Role';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=70" class="ui-green" title="List of User Roles">List of User Roles</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '72': // Edit User Role
		$iRecID = $_GET['RecID'];
		$oRoles      = new Roles();      
		if($_POST['hidEditStatus'])
		{		
			$aMsg = $oRoles->editRole($iRecID);				 
		}
		else
		{
			$aMsg[0] = "Please use the following form to edit role details. The <strong>Role Name</strong> must be unique.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$aEachRoleDetails = $oRoles->listEachRole($iRecID);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'role-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit User Role | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit User Role - '.$aEachRoleDetails[0][rolename];
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=70" class="ui-green" title="List of User Roles">List of User Roles</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '73': // Delete User Role
		$iRecID = $_GET['RecID'];
		$oRoles         = new Roles();
		$aMsg = $oRoles->deleteRole($iRecID);
	break;
	case '74': // Sort Display Order of User Roles
		$oRoles      = new Roles();
		if($_POST['hidArrStatus'])
		{
			$oRoles->sortRoles();
		}
		else
		{
			$aMsg[0] = "Please use up and down arrows to sort the display order of user roles. This has no other utility except having a display order on IDE.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}		
	    $aSortedRolesList   = $oRoles->getRolesSortList();		
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'roles-arrange', 'footer.main');
		$APP->PAGEVARS['title'] = 'Sort Display Order of User Roles | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Sort Display Order of User Roles';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=70" class="ui-green" title="List of User Roles">List of User Roles</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '80': // Lifestream listing
		$oLifestream = new Lifestream();
		$aListofFiles = $oLifestream->listLogFiles();
		$aMsg = $oLifestream->getListPageMessage();
	    $APP->VIEWPARTS = array('header.main', 'left-bar', 'lifestream', 'footer.main');
		$APP->PAGEVARS['title'] = 'Lifestream | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Lifestream';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '81': // View Lifestream History Log
		$sFile = $_GET['File'];
		$oLifestream = new Lifestream();   
		$sFileContent = $oLifestream->ViewLogFile($sFile);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'lifestream-view', 'footer.main');
		$APP->PAGEVARS['title'] = 'View Lifestream Log - '.$sFile.' | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'View Lifestream Log - <em>'.$sFile.'</em>';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=80" class="ui-green" title="List of Lifestream Log Files">List of Lifestream Log Files</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '82': // Remove Lifestream Log of A Day
		$sFile = $_GET['File'];
		$oLifestream = new Lifestream();   
		$aMsg = $oLifestream->deleteLog($sFile);
	break;
	case '83': // Remove Lifestream History
		$oLifestream = new Lifestream();   
		$aMsg = $oLifestream->deleteHistory();
	break;
	case '90':// List View Page Variables	
		$oPagevars       = new Pagevars();	
		$aPagevarDetails = $oPagevars->listPage();
		$APP->VIEWPARTS  = array('header.main', 'left-bar', 'pagevars', 'footer.main');
		$APP->PAGEVARS['title'] = 'View Page Variables | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'View Page Variables';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;	
	case '91': // Add View Page Variable
		$oPagevars      = new Pagevars();
		if($_POST['hidAddStatus'])
		{			 
		    $aMsg = $oPagevars->addPagevars();
		}
		else
		{
			$aMsg[0] = "Please fill up the following form to create a new configuration variable. The variable name must be unique.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'pagevars-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add Page Variable | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add Page Variables';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=90" class="ui-green" title="View Page Variables">View Page Variables</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '92':// Edit View Page Variables (NOT in operation now)
		$iRecID 	 = $_GET['RecID'];
		$oPagevars   = new Pagevars();      
		if($_POST['hidEditStatus'])
		{		
			$aMsg = $oPagevars->editPagevars($iRecID);				 
		}
		else
		{
			$aMsg[0] = "Please edit the Page Variable Key with the form below. The variable name must be unique.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$aEachPagevarDetails   = $oPagevars->listEachPagevars($iRecID);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'pagevars-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit Page Variables | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit Page Variables';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=90" class="ui-green" title="View Page Variables">View Page Variables</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '93': // Delete View Page Variable
		$iRecID = $_GET['RecID'];		
		if($iRecID)
		{
			$oPagevars   = new Pagevars();       		
			$oPagevars->deletePagevars($iRecID);						 
		}			
	break;
	case '100': // Application Settings View Page
		$oSys            = new Sys();	
		$aSysDetails     = $oSys->listPage();
		$APP->VIEWPARTS  = array('header.main', 'left-bar', 'sys', 'footer.main');
		$APP->PAGEVARS['title'] = 'Application Settings | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Application Settings';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;	
	case '101': // Add Application [ NOT TO BE USED]
		$oSys            = new Sys();	
		if($_POST['hidAddStatus'])
		{			      
			if($oSys->addSys()) 
			{
				$sPath = $_SERVER['PHP_SELF'].'?ID=100';	
				header("Location: $sPath");
				exit();
			}		 
		}   	
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'sys-add', 'footer.main');
		$APP->PAGEVARS['title'] = 'Add Settings | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Add Settings';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=90" class="ui-green" title="Application Settings">Application Settings</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '102': // Edit Application Settings
		$iRecID 	 = $_GET['RecID'];
		$oSys        = new Sys();      
		if($_POST['hidEditStatus'])
		{		
			$oSys->editSys($iRecID);
		}
		else
		{
			$aMsg[0] = "Please edit the application settings with the form below. Please be very careful as it may affect the working of application.";
			$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			$aMsg[2] = "ui-icon ui-icon-info";
		}
		$aEachSysDetails   = $oSys->listEachSys($iRecID);
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'sys-edit', 'footer.main');
		$APP->PAGEVARS['title'] = 'Edit Application Settings | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Edit Application Settings';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span><a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=100" class="ui-green" title="Application Settings">Application Settings</a><span class="ui-menu-seperator">&raquo;</span>'.$APP->PAGEVARS['headertext'];
	break;
	case '103':	 // Delete Application [ NOT TO BE USED]
		$iRecID = $_GET['RecID'];		
		if($iRecID)
		{
			$oSys        = new Sys();    		
			if($oSys->deleteSys($iRecID))
			{
				$sPath = $_SERVER['PHP_SELF'].'?ID=100';	
				header("Location: $sPath");
				exit();
			}							 
		}		
	break;
	case '104': // Change Password
	    $oProfile = new Profile();		
		if($_POST['hidPwd'])
		{
			$aMsg = $oProfile->changePassword($USER->ID);
		}
		else
		{
			if ($_GET['PF'] == '1')
			{
				$aMsg[0] = "Your password has been changed successfully.";
				$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			    $aMsg[2] = "ui-icon ui-icon-info";
			}
			else
			{
				$aMsg[0] = "Change your password with the form below.";
				$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
			    $aMsg[2] = "ui-icon ui-icon-info";
			}
		}
		$APP->VIEWPARTS = array('header.main', 'left-bar', 'change-password', 'footer.main');
		$APP->PAGEVARS['title'] = 'Change Password | Opendelight IDE';
		$APP->PAGEVARS['headertext'] = 'Change Password';
		$APP->PAGEVARS['breadcrumb'] = '<a href="'.$APP->BASEURL.'/delight-ide/index.php?ID=1" class="ui-green" title="Dashboard">Dashboard</a><span class="ui-menu-seperator">&raquo</span>'.$APP->PAGEVARS['headertext'];	
	break;
	case '105': // Case for "Select Events in Role Page" 
	    $sSelectEvent = '';
	    if ($_POST['requestctrlid'])
		{   
		    $oControllers    = new Controllers();          	  		
		    $aEventDetails   = $oControllers->listEventPage($_POST['requestctrlid']);
	    }
	    $APP->VIEWPARTS = array('role-eventselect');
	break;
	case '106':
	    if ($_POST['requestctrlid'])
		{ 
		    $oControllers    = new Controllers();          	  		
		    $sExecute = $oControllers->changeStatus($_POST['requeststatus'], $_POST['requestctrlid']);
		    if($sExecute) print '1';
		    else  print '2';
		}
	break;
	case '107':
	    if ($_POST['requestctrlid'])
		{ 
		    $oControllers    = new Controllers();          	  		
		    $sExecute = $oControllers->changePublic($_POST['requestpublic'], $_POST['requestctrlid']);
		    if($sExecute) print '1';
		    else  print '2';
		}
	break;
	case '108':
	    if ($_POST['requesteventid'])
		{ 
		    $oControllers    = new Controllers();          	  		
		    $sExecute = $oControllers->changeEventStatus($_POST['requeststatus'], $_POST['requesteventid']);
		    if($sExecute) print '1';
		    else  print '2';
		}
	break;
	case '109': // Change User Status
	    if ($_POST['requestuserid'])
		{ 
		    $oUsers          = new Users();          	  		
		    $oUsers->changeStatus($_POST['requeststatus'], $_POST['requestuserid']);
		}
	break;	
} //End of switch statement
if(!empty($APP->VIEWPARTS)) 
{
	foreach($APP->VIEWPARTS as $sViewPart) include(dirname(__FILE__).'/html/'.$sViewPart.'.tpl.php');
}
?>