<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $APP->PAGEVARS['TITLE']; ?></title>
<link href="<?php print $APP->BASEURL;?>/view/css/index.php" rel="stylesheet" type="text/css" />
<script src="<?php print $APP->BASEURL;?>/view/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="<?php print $APP->BASEURL;?>/view/js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="<?php print $APP->BASEURL;?>/view/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php print $APP->BASEURL;?>/view/js/oma.js" type="text/javascript"></script>
</head>
<body>
	<div class="site" style="width:990px;margin:auto;">	
		<div class="head">
			<div class="logo">
				<img src="<?php print $APP->BASEURL;?>/view/images/its_logo.png">
			</div>
			<div class="headmenu" style="float:right;">
				<?php echo "Käyttäjä"; ?>: <?php print $USER->USERNAME?> | <a href="<?php print $APP->BASEURL?>/sign.php?ID=5" title="Signout"><?php echo "Kirjaudu ulos"; ?></a>
				<br/><a href="javascript:popUp('<?=$APP->BASEURL?>/index.php?ID=85')"><?=$lang['VALUUTTA']?></a>
			</div>
		</div>
		<div class="valikko" style="margin:20px">
			<?php
			$kk = date("n");
			?>
			<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="tuntikirjaus.php?ID=30&kk=<?php echo $kk; ?>">Tuntikirjaukset</a>
			<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="tuntikirjaus.php?ID=34">Yhteenveto</a>
			<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="hallinta.php?ID=8">Kustannuspaikat</a>
			<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="hallinta.php?ID=10">Toiminnot</a>		
			<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="hallinta.php?ID=11">Kohteet</a>		
			<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="hallinta.php?ID=26">Koodit</a>		
		</div>
		


