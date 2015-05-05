<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $APP->PAGEVARS['TITLE']; ?></title>
        <link href="<?php print $APP->BASEURL;?>/view/css/index.php" rel="stylesheet" type="text/css" />
        <?php
            if ($APP->getCtrid() == 6) {
        ?>
        <link rel="stylesheet" type="text/css" href="<?php print $APP->BASEURL;?>/view/css/varauskirja.css" />
        <?php } ?>
        
        <link type="text/css" rel="stylesheet" href="<?php print $APP->BASEURL; ?>/view/css/jquery.miniColors.css" />
        <link type="text/css" rel="stylesheet" href="<?php print $APP->BASEURL; ?>/view/css/jquery.ui.timepicker.css"/>
        <link rel="stylesheet" type="text/css" href="<?php print $APP->BASEURL;?>/view/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
        <link href="<?php print $APP->BASEURL;?>/view/css/ttt-ui/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" />

        
        <script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery-1.7.1.min.js"></script>

        <script src="<?php print $APP->BASEURL;?>/view/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
        
        
        <script src="<?php print $APP->BASEURL;?>/view/js/jquery.validate.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/fancybox/jquery.easing-1.3.pack.js"></script>
        <script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
        <script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<?php
if ($_GET["ID"] == 32) { ?>
        <script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript">
        tinyMCE.init({
                // General options
                mode : "exact",
                elements : "note",
                theme : "advanced",
                plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

                // Theme options
                theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,

                // Skin options
                skin : "o2k7",
                skin_variant : "silver",

                // Example content CSS (should be your site CSS)
                content_css : "css/example.css",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url : "js/template_list.js",
                external_link_list_url : "js/link_list.js",
                external_image_list_url : "js/image_list.js",
                media_external_list_url : "js/media_list.js",

                // Replace values for the template plugin
                template_replace_values : {
                        username : "Some User",
                        staffid : "991234"
                }
        });
        </script>
<?php
} ?>

    </head>
    <body>
        <div class="site" style="width:1200px;margin:auto;">	
            <div class="head">
                <div class="logo">
                    <img src="<?php print $APP->BASEURL;?>/view/images/ttt_logo.png">
                </div>
                <div class="headmenu" style="float:right;">
                    <div class="warnings">
<?php
if ($USER->iRole == 2 || $USER->iRole == 1) {
    $help = new helper();
    $warnings = $help->checkWorkLimits();
    if ($warnings) {
        $j = sizeof($warnings);
        $i = 0;
        foreach ($warnings as $warning) {
            print $warning;
            $i++;
            if ($j > $i) print '<br/>';
        }
    }
}
?>
                    </div>
                    <div class="userinfo">
                    <?php echo "Käyttäjä"; ?>: <?php print $USER->USERNAME?> | <a href="<?php print $APP->BASEURL?>/sign.php?ID=5" title="Signout"><?php echo "Kirjaudu ulos"; ?></a>
                    </div>
                </div>
            </div>
            <div class="valikko normivalikko ui-corner-top">
                <ul class="topnav">
<?php
$kk = date("n");
$session_id = session_id();
$etusivu = new etusivu();
$tyovuoro = $etusivu->haeTyovuoro($session_id);
                ?>
                    <li class="first-li"><a href="index.php?ID=6">Etusivu</a></li>
                <li><a href="omattiedot.php?ID=10">Omat tiedot</a></li>
                <li><a href="viestit.php?ID=31">Viestit</a></li>
<?php
// Kalenteri / Lennot / Työaika -linkit näytetään erilailla eri käyttäjätasoille.
if (count($tyovuoro) > 0 && $USER->iRole == '2') { ?>
                <li><a href="kalenteri.php?ID=11">Kalenteri</a></li>
                <li><a href="lennot.php?ID=13">Lennot</a></li>
                <li><a href="tyoaika.php?ID=44">Työaika</a></li>
<?php
}
if ($USER->iRole == '3') { ?>
                <li><a href="kalenteri.php?ID=11">Kalenteri</a></li>
                <li><a href="lennot.php?ID=13">Lennot</a></li>
<?php
}
if ($USER->iRole == '1') { ?>
                <li><a href="kalenteri.php?ID=11">Kalenteri</a></li>
                <li><a href="lennot.php?ID=13">Lennot</a></li>
                <li><a href="tyoaika.php?ID=44">Työaika</a></li>
<?php
}

?>
                <li><a href="varauskirja.php">Varauskirja</a></li>
                <li><a href="tiedostot.php">Tiedostot</a></li>
<?php
if ($USER->iRole == 1) { ?>
                <li><a href="hallinta.php?ID=50">Hallinta</a></li>
<?php
} ?>
                </ul>
            </div>
