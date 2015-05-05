<?php
session_start(); 
$_DELIGHT[CTRID]  = 2;
include "./load.delight.php";
/* Move the control to the respective pages according to the Event ID */
switch($APP->ID)
{ 	
	
    case 11: //Viikkonäkymä (oletus)
		$APP->VIEWPARTS = array('header-main.tpl.php', 'wdCalendar/sample.php', 'footer-main.tpl.php');				
		
    break;	
	
	case 12: //Päivänäkymä (ei käytössä (?))
		$kal = new kalenteri();
		$session_id = session_id();
		//$otsikot = $kal->haeViikonpaivat();
				
		//$paiva = date('Y-m-d');
		$paiva = date('2011-10-29');
		$paiva_print = date('d.m.Y');
		$viikonpaiva = $kal->haePaivanNimi($paiva);		
		$ajat = $kal->teePaivakalenteri($paiva);
			
		$APP->VIEWPARTS = array('header-main.tpl.php', 'kalenteri-paiva.tpl.php', 'footer-main.tpl.php');		
		
    break;	
	
    default:
    break;
} //End of switch statement
include "./load.view.php";
?>
