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
session_start();
if ($_POST['name'] == '')
{
   echo "The email field is blank";
}
else if($_POST['name'] != '')
{
 if($_POST["code"] == '')
 {
    echo "The security code is blank";
 }
 else if ( ((md5(strtolower($_POST["code"]))) == $_SESSION["vihash"]) && 
    (!empty($_POST["code"]) && !empty($_SESSION["vihash"])) ) {
 echo "Success";
} else {
  echo "Failure";
}
}
?>