<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $APP->PAGEVARS['title']; ?></title> 
<link href="css/index.php" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>
<script src="js/xhtml-external-links.js" type="text/javascript"></script> 
<script type="text/javascript">
function funcInstall()
{
	$("#frmInstall").validate();
 	$("#frmInstall").submit();
}
function funcCreateApp()
{
	$('frmCreateAppln').submit();
}
</script>
</head>
<body>
<div id="ui-od-header">
<div style="float:left;margin:0px 9px;padding-top:7px;">
<img alt="Opendelight IDE" src="images/opendelight-ide-logo.png" style="border: 0px;float:left;" width="200" height="30" title="Opendelight IDE" />
</div>
</div>
<div id="ui-middle-content">
<div class="ui-widget ui-corner-tl" style="float:left;width:97%;margin:10px 0px 0px 10px;height:auto;background-color:#ffffff;padding:10px;">
<div class="ui-widget-header" style="float:left;width:99.5%;margin-top:5px;font-size:16px;padding:5px 0px 5px 5px;">
 <div style="float:left;width:75%;"><?php print $APP->PAGEVARS['headertext'];?></div>
 <a rel="external" href="http://www.adiipl.com/opendelight/docs/installation.php"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
</div>
<div id="markerdiv"></div>