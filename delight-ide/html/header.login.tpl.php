<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $APP->PAGEVARS['title']; ?></title> 
<link href="<?php print $APP->BASEURL;?>/delight-ide/css/index.php" rel="stylesheet" type="text/css" />
<script src="<?php print $APP->BASEURL;?>/delight-ide/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="<?php print $APP->BASEURL;?>/delight-ide/js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="<?php print $APP->BASEURL;?>/delight-ide/js/jquery.validate.js" type="text/javascript"></script> 
<script src="<?php print $APP->BASEURL;?>/delight-ide/js/xhtml-external-links.js" type="text/javascript"></script>
</head>
<body>
<div id="ui-od-header">
<div style="float:left;margin:0px 5px;padding-top:5px;">
<a href="http://www.adiipl.com/opendelight" rel="external"><img alt="Opendelight IDE" src="<?php print $APP->BASEURL;?>/delight-ide/images/opendelight-ide-logo.png" style="border:0px;" width="200" height="30" title="Opendelight IDE" /></a>
</div>
<div style="float:left;margin:0px 5px;padding-top:5px;">
<img alt="" src="<?php print $APP->BASEURL;?>/delight-ide/images/bar-green.png" style="border:0px;float:left;padding-top:2px;" width="1" height="25" title="Open delight" />
</div>
<div style="float:left;margin:0px 5px;height:18px;padding-top:18px;color:#ffffff;">
<?php print stripslashes($aAppDetails[0]['appname']);?>
</div>
</div>
<div id="ui-middle-content">
<div class="ui-widget ui-corner-tl" style="float:left;width:97%;margin:10px 0px 0px 10px;height:auto;background-color:#ffffff;padding:10px;">
<div class="ui-widget-header" style="float:left;width:99.5%;margin-top:5px;font-size:16px;padding:5px 0px 5px 5px;">
 <div style="float:left;width:75%;"><?php print $APP->PAGEVARS['headertext'];?></div>
