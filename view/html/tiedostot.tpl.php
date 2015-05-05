<?php
// Välitetään filebrowserille käyttäjän rooli.
$iRole = $USER->iRole;
?>
<iframe name="kcfinder_iframe" src="./filebrowser/browse.php?lang=fi&iRole=<?php print $iRole; ?>" frameborder="0" width="100%" height="400px" marginwidth="0" marginheight="0" scrolling="no" /></iframe>