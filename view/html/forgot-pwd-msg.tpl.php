<?php
$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
$sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$sHeaders .= 'From: noreply@ttt-aviation.com<noreply@ttt-aviation.com>' . "\r\n";
$sHeaders .= 'Reply-To: noreply@ttt-aviation.com' . "\r\n";
$sSubject = 'TTT-Aviation intra / Uusi salasanasi!';
$kirjautumisosoite = $this->APP->BASEURL;
$sMessage  = <<<FORGOTYOURPWD
<html>
<body>
<p>Hei $sFirstName,</p>

<p>Tämän sähköpostiosoitteen käyttäjätunnuksen salasanaa on pyydetty vaihtamaan.</p>
<p>Kirjaudu seuraavilla tiedoilla sisään osoitteessa <a href="$kirjautumisosoite" title="TTT-Aviation intra">$kirjautumisosoite</a><br /></p>

<pre style="font-size: 16px">
<strong>Käyttäjätunnus:</strong>    {$sUsername}
<strong>Salasana:</strong>          {$sPwd}
</pre>

</body>
</html>
FORGOTYOURPWD;
?>
