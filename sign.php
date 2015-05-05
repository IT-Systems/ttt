<?php
session_start(); 
$_DELIGHT['CTRID']  = 1;
include "./load.delight.php";
/* Move the control to the respective pages according to the Event ID */
switch($APP->ID)
{ 
    case 1: //Case for eventname "SignIn"
    $APP->VIEWPARTS = array('header-sign.tpl.php', 'signin.tpl.php', 'footer-sign.tpl.php');
    $APP->PAGEVARS[TITLE] = "Sisäänkirjautuminen";
    break;


    case 2: //Case for eventname "Validate"
    $APP->FORMRULES = array("txtUsername" => array("validate" => 1, "validation_type" => "required", "reg_exp" => "", "error_message" => "This field is required", "sanitize" => 1, "sanitize_type" => "safe"),"txtPassword" => array("validate" => 1, "validation_type" => "required", "reg_exp" => "", "error_message" => "This field is required", "sanitize" => 1, "sanitize_type" => "safe"));
    if ($_REQUEST["hidStatus"]) 
{
    $oSign = new Delight_Sign();
    $oForm = new Delight_Form($_POST, $APP->FORMRULES);
    $aForm = iterator_to_array($oForm);				
    $sErrMsg = $oSign->signin($aForm, $oSign->sLoginToken);
}
    $APP->VIEWPARTS = array('header-sign.tpl.php', 'signin.tpl.php', 'footer-sign.tpl.php');
    $APP->PAGEVARS[TITLE] = "Sisäänkirjautuminen";
    break;


    case 3: //Case for eventname "Password"
    $APP->VIEWPARTS = array('header-sign.tpl.php', 'forgot-pwd.tpl.php', 'footer-sign.tpl.php');
    $APP->PAGEVARS[TITLE] = "Unohditko salasanasi?";
    break;


    case 4: //Case for eventname "retrievePassword"
    if ($_REQUEST["hidPwd"]) 
{
    $oSign = new Delight_Sign();
    $sMsg = $oSign->getPassword($_REQUEST["txtEmailId"]);
}
    $APP->VIEWPARTS = array('header-sign.tpl.php', 'forgot-pwd.tpl.php', 'footer-sign.tpl.php');
    $APP->PAGEVARS[TITLE] = "Unohditko salasanasi?";
    break;


    case 5: //Case for eventname "SignOut"
    session_start();
    session_unset();
    session_destroy();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
    $APP->VIEWPARTS = array('header-sign.tpl.php', 'signin.tpl.php', 'footer-sign.tpl.php');
    break;

    default:
    break;
} //End of switch statement
include "./load.view.php";
?>
