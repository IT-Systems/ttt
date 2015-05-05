<?php
/** This file is part of KCFinder project
 *
 *      @desc Base configuration file
 *   @package KCFinder
 *   @version 2.21
 *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
 * @copyright 2010 KCFinder Project
 *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
 *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
 *      @link http://kcfinder.sunhater.com
 */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions
if($_SESSION['SigninToken']!='' AND $_SESSION['IDVERIFIER']!='' AND $_SESSION['USERID']!='' ) {
    $readonly = ($_GET['iRole'] == 3) ? true : false; // Opiskelijalle readonly oikat päälle
    $_CONFIG = array(
            'disabled' => false,
            'readonly' => $readonly,
            'denyZipDownload' => false,

            'theme' => "its",

            'uploadURL' => "../files/",
            'uploadDir' => "",

            'dirPerms' => 0755,
            'filePerms' => 0644,

            'deniedExts' => "exe com msi bat php cgi pl",

            'types' => array(

            // CKEditor & FCKEditor types
                    'Tiedostot'     => "",
                    'Flash'     => "swf",
                    'Mediat'     => "swf flv avi mpg mpeg qt mov wmv asf rm",
                    'Dokumentit'  => "! pdf doc docx xls xlsx",
                    'Kuvat'    => "*img",
            ),

            'mime_magic' => "",

            'maxImageWidth' => 0,
            'maxImageHeight' => 0,

            'thumbWidth' => 100,
            'thumbHeight' => 100,

            'thumbsDir' => ".thumbs",

            'jpegQuality' => 90,

            'cookieDomain' => "",
            'cookiePath' => "",
            'cookiePrefix' => 'KCFINDER_',

            // THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION CONFIGURATION

            '_check4htaccess' => true,
            //'_tinyMCEPath' => "/tiny_mce",

            '_sessionVar' => &$_SESSION['KCFINDER'],
            //'_sessionLifetime' => 30,
            //'_sessionDir' => "/full/directory/path",

            //'_sessionDomain' => ".mysite.com",
            //'_sessionPath' => "/my/path",
    );
}
else {
    $_CONFIG = array(

            'disabled' => true,
    );
}
?>