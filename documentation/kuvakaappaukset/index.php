<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Kuvakaappaukset</title>
</head>
<body>
<?php
$handle = opendir('.');
$imagetypes = array('jpg', 'gif', 'png');
while (false !== ($file = readdir($handle)))
{
    if (in_array(pathinfo($file, PATHINFO_EXTENSION), $imagetypes))
    { ?>
    <a href="<?php print $file; ?>" target="_BLANK"><img src="<?php print $file; ?>" width="600px"></a>
    <br/>
<?php
    }
}
?>
</body>
</html>