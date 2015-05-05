<?php
session_start(); 
$_DELIGHT['CTRID']  = 7;
include "./load.delight.php";
/* Move the control to the respective pages according to the Event ID */
switch($APP->ID)
{ 
    case 16: //Case for eventname "tiedostot"
    $APP->VIEWPARTS = array('header-main.tpl.php', 'tiedostot.tpl.php', 'footer-main.tpl.php');
    $APP->PAGEVARS[TITLE] = "Tiedostot";
    $APP->PAGEVARS[HEADERTEXT] = "Tiedostot";
    $APP->PAGEVARS[BREADCRUMB] = "Tiedostot";
    break;

    default:
    break;
} //End of switch statement

include "./load.view.php";
?>
