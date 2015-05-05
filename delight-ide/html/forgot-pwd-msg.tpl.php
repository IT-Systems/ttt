<?php
$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
$sHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$sHeaders .= 'From: '.$sNoReplyEmail.'<'.$sNoReplyEmail.'>' . "\r\n";
$sHeaders .= 'Reply-To: ' . $sNoReplyEmail . "\r\n";
$sSubject = 'Forgot your password?';
$sMessage  = <<<FORGOTYOURPWD
<html>
<body>
<p><strong>Dear {$sFirstName}</strong>,</p>
<p>Please use the following details to SignIn at our web address <a href="{$sWebsiteURL}/delight-ide/sign.php" title="{$sWebsiteURL}/delight-ide/sign.php">{$sWebsiteURL}/delight-ide/sign.php</a><br />
<strong>Username:</strong> {$_REQUEST['txtEmailId']}<br />
<strong>Password:</strong> {$sPwd}</p>
<p><strong>Best Regards</strong></p>
<p><strong>Admin</strong></p>
</body>
</html>
FORGOTYOURPWD;
?>
