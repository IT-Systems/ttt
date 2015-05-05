<?php
/*------------------------------------------------------------------------------+
 * Opendelight - A PHP based Rapid Web Application Development Framework        |
 * (c)Copyright ADII Research & Applications (P) Limited. All rights reserved.  |
 * Author: Ashwini Kumar Rath                                                   |
 * Website of Opendelight: http://www.adiipl.com/opendelight                    |
 * Licensed under the terms of the GNU General Public License Version 2 or later|
 * (the "GPL"): http://www.gnu.org/licenses/gpl.html                            |
 * NOTE: The copyright notice like this on any of the distributed files         |
 *       (downloaded or obtained as the part of Opendelight) must NOT be        |
 *       removed or modified.                                                   |
 *------------------------------------------------------------------------------+
 */
ini_set('session.use_only_cookies', '1');
session_start();
function randomString($len = 5)
{
    $chars = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
    $s = "";
    for ($i = 0; $i < $len; $i++)
    {
        $int         = rand(0, strlen($chars)-1);
        $rand_letter = $chars[$int];
        $s           = $s . $rand_letter;
    }
    return $s;
}

// generate  5 digit random number and store it in session
$rand = randomString(5);
$_SESSION['vihash'] = md5(strtolower($rand));
$width = 125;
$height = 50;
$image = imagecreate($width, $height);
$bgColor = imagecolorallocate ($image, 255, 255, 255);
$textColor = imagecolorallocate ($image, 0, 0, 0);

// add random noise
for ($i = 0; $i < 20; $i++)
{
    $rx1 = rand(0, $width);
    $rx2 = rand(0, $width);
    $ry1 = rand(0, $height);
    $ry2 = rand(0, $height);
    $rcVal = rand(0, 255);
    $rc1 = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(100, 255));
    imageline($image, $rx1, $ry1, $rx2, $ry2, $rc1);
}
$rc2 = imagecolorallocate($image, 0,0, 0);

$font = '../css/trebucit.ttf';
imagettftext($image, 26, 7, 10, 45, $rc2, $font, $rand);

// date in the past
header("Expires: Mon, 23 Jul 1993 05:00:00 GMT");

// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");

// send the content type header so the image is displayed properly
header('Content-type: image/jpeg');

imagejpeg($image);
imagedestroy($image);
?>
