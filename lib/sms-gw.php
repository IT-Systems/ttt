<?php

/**
 * PHP 4/5 functions for sending and receiving SMS messages
 * using Labyrintti Media SMS Gateway.
 * See Service Development Guide for more advanced sending options.
 *
 * <h2>2-WAY SMS: Receiving messages from mobile phones and sending responses</h2>
 *
 * @par Example of use in a .php file:
 * @code
 * <?php
 * include "/my/path/sms-gw.php";
 * sms_extract();
 * $response = "Thank you for your feedback which was: $sms_text";
 * sms_respond($response);
 * ?>
 * @endcode
 *
 * <h2>1-WAY SMS: Sending messages to mobile phones</h2>
 *
 * @par Example of use in a .php file:
 * @code
 * <?php
 * include "/my/path/sms-gw.php";
 * if (sms_send("0401234567", "Hello world!")) {
 *   echo "SMS sent.";
 * } else {
 *   echo "SMS sending failed!";
 * }
 * ?>
 * @endcode
 *
 * Copyright 2007 Labyrintti Media Oy. All rights reserved.
 *
 * @version 1.1.1 (24 July 2007)
 * @file
 */

/** Set your SMS Gateway user name here (only required for 1-way sending) */
$sms_user = "ttt-aviation";
/** Set your SMS Gateway password here (only required for 1-way sending) */
$sms_password = "pEwrUmA2E2uQ";

/** SMS Gateway URL for normal HTTP sending */
$sms_url = "http://gw.labyrintti.com:28080/sendsms";

/* Uncomment if you have purchased the option to use secure HTTPS sending
 * (requires PHP 4.3.0 or newer with OpenSSL support compiled in) */
//$sms_url = "https://gw.labyrintti.com:28443/sendsms";


/**
 * Parses received SMS message.
 * Imports GET/POST parameters into $sms_variables.
 * After calling this function, the following global variables are available:
 *
 * <pre>
 * VARIABLE      DESCRIPTION                                  EXAMPLE
 * ------------- -------------------------------------------- ----------------
 * $sms_source   User's phone number.                         +358401234567
 * $sms_operator Name of user's mobile operator.              Sonera
 * $sms_dest     Service number that received the message.    173172
 * $sms_keyword  Uppercase words that identified the service. LOGO
 * $sms_params   Rest of the message words in an array.       (sports,car)
 * $sms_text     The whole message.                           logo sports car
 * $sms_header   Optional message binary header in hex.       060504158A0000
 * </pre>
 */
function sms_extract() {
    global $sms_source, $sms_operator, $sms_dest, $sms_keyword, $sms_params,
    $sms_text, $sms_header;

    // import HTTP request parameters into PHP symbol table
    $version = explode(".-", PHP_VERSION);
    if ($version[0] > 4 || ($version[0] == 4 && $version[1] > 0)) {
        // PHP 4.1.0 or newer supports $_REQUEST
        extract($_REQUEST, EXTR_PREFIX_ALL, "sms");
    } else {
        global $HTTP_POST_VARS, $HTTP_GET_VARS;
        extract($HTTP_POST_VARS, EXTR_PREFIX_ALL, "sms");
        extract($HTTP_GET_VARS, EXTR_PREFIX_ALL, "sms");
    }

    // unescape any strings escaped by PHP
    if (get_magic_quotes_gpc()) {
        $sms_keyword = stripslashes($sms_keyword);
        $sms_params = stripslashes($sms_params);
        $sms_text = stripslashes($sms_text);
    }

    // change parameter list into an array
    $sms_params = explode(" ", $sms_params);
}

/**
 * Sends one or more text SMS messages as response to a received message.
 *
 * @param text    Response text. If length>160 chars, multiple messages are used.
 * @param params  Optional parameters in an array, e.g. $params["class"]="flash".
 */
function sms_respond($text, $params = NULL) {
    // escape backslashes, carriage returns and linefeeds in text
    $text = str_replace("\\", "\\\\", $text);
    $text = str_replace("\r", "\\r", $text);
    $text = str_replace("\n", "\\n", $text);

    // send response text
    header("Content-Type: text/plain");
    echo "type=SMS\r\n";
    echo "text=$text\r\n";

    // send any optional parameters
    while ($params && list($name, $value) = each($params)) {
        echo "$name=$value\r\n";
    }
}

/**
 * Sends one or more binary SMS messages as response to a received message.
 *
 * @param header  Binary header. Two chars per byte, e.g. "060504158A0000".
 * @param binary  Binary content. Two hex characters per byte.
 * @param params  Optional parameters in an array, e.g. $params["validity"]=60.
 */
function sms_respond_binary($header, $binary, $params = NULL) {
    // send response content
    header("Content-Type: text/plain");
    echo "type=SMS\r\n";
    echo "header=$header\r\n";
    echo "binary=$binary\r\n";
    echo "concatenate=yes\r\n";

    // send any optional parameters
    while ($params && list($name, $value) = each($params)) {
        echo "$name=$value\r\n";
    }
}

/**
 * Sends one or more text SMS messages.
 *
 * @param dest    Destination phone numbers, separated with spaces or in an array.
 * @param text    Message text. If length>160 chars, multiple messages are used.
 * @param params  Optional parameters in an array, e.g. $params["class"]="flash".
 *
 * @return  The number of destinations for which sending succeeded.
 *          If no messages were sent, returns 0.
 */
function sms_send($dest, $text, $params = NULL) {
    $sms_user = "ttt-aviation";
    $sms_password = "pEwrUmA2E2uQ";

    // add request parameters
    $query = "user=" . urlencode($sms_user);
    $query .= "&password=" . urlencode($sms_password);
    if (is_array($dest)) {
        $dest_array = $dest;
        $dest_list = implode(" ", $dest);
    } else {
        $dest_array = explode(" ", $dest);
        $dest_list = $dest;
    }
    $query .= "&dest=" . urlencode($dest_list);
    $query .= "&text=" . urlencode($text);

    // add any optional parameters
    while ($params && list($name, $value) = each($params)) {
        $query .= "&$name=" . urlencode($value);
    }

    // make the request and return status
    return __sms_send($query, $dest_array);
}

/**
 * Sends one or more binary SMS messages.
 * For example, operator logos or ring tones.
 *
 * @param dest    Destination phone numbers, separated with spaces or in an array.
 * @param header  Binary header. Two chars per byte, e.g."060504158A0000".
 * @param binary  Binary content. Two hex characters per byte.
 * @param params  Optional parameters in an array, e.g. $params["validity"]=60.
 *
 * @return  The number of destinations for which sending succeeded.
 *          If no messages were sent, returns 0.
 */
function sms_send_binary($dest, $header, $binary, $params = NULL) {
    global $sms_user, $sms_password;

    // add request parameters
    $query = "user=" . urlencode($sms_user);
    $query .= "&password=" . urlencode($sms_password);
    if (is_array($dest)) {
        $dest_array = $dest;
        $dest_list = implode(" ", $dest);
    } else {
        $dest_array = explode(" ", $dest);
        $dest_list = $dest;
    }
    $query .= "&dest=" . urlencode($dest_list);
    $query .= "&header=" . $header;
    $query .= "&binary=" . $binary;
    $query .= "&concatenate=yes";

    // add any optional parameters
    while ($params && list($name, $value) = each($params)) {
        $query .= "&$name=" . urlencode($value);
    }

    // make the request and return status
    return __sms_send($query, $dest_array);
}

/**
 * Returns results of the last send operation in an associative array
 * containing send status for every destination, or NULL if SMS Gateway could
 * not be reached. Array keys are destination phone numbers, and array values
 * are booleans indicating whether sending succeeded (TRUE) or failed (FALSE).
 *
 * @return  associative array, phone numbers -> booleans
 */
function sms_result() {
    global $sms_result;
    return $sms_result;
}

/** @internal Sends SMS messages. Used by sms_send() and sms_send_binary(). */
function __sms_send($query, $dest_array) {
    $sms_url = "http://gw.labyrintti.com:28080/sendsms";
    $sms_result = NULL;
    $url = parse_url($sms_url);

    while (TRUE) {
        // connect SMS Gateway or use old connection if still open
        $old_socket_open = ($sms_socket && !feof($sms_socket));
        if (!$old_socket_open) {
            $target = ($url["scheme"] == "https" ? "ssl://" : "") . $url["host"];
            $sms_socket = fsockopen($target, $url["port"]);
            if (!$sms_socket) {
                // SMS Gateway could not be reached - no messages sent
                return 0;
            }
        }

        // post data to SMS Gateway
        fputs($sms_socket, "POST " . $url["path"] . " HTTP/1.1\r\n");
        fputs($sms_socket, "Host: " . $url["host"] . ":" . $url["port"] . "\r\n");
        fputs($sms_socket, "Content-Type: application/x-www-form-urlencoded\r\n");
        fputs($sms_socket, "Content-Length: " . strlen($query) . "\r\n");
        fputs($sms_socket, "\r\n");
        @fputs($sms_socket, $query);
        fflush($sms_socket);

        // check if connection has been closed
        if (feof($sms_socket)) {
            // if we were using an old connection, it might have timed out, so retry
            // with a new connection - otherwise fail and return 0
            if ($old_socket_open) {
                @fclose($sms_socket);
                $sms_socket = NULL;
            } else {
                return 0;
            }
        } else {
            break;
        }
    }

    // read response status
    $status = explode(" ", fgets($sms_socket), 3);

    // read response headers
    $length = 0;
    while (!feof($sms_socket)) {
        $header = explode(":", rtrim(fgets($sms_socket)), 2);
        if (strcasecmp($header[0], "Content-Length") == 0) {
            $length = $header[1];
        } else if (!$header[0]) {
            break;
        }
    }

    // read and parse send results, counting the number of destinations that
    // succeeded - result has one line per destination, for example:
    // "+358401234567 ERROR Sending failed"
    $success_count = 0;
    for ($i = 0; !feof($sms_socket) && $length > 0; $i++) {
        // read the next response line
        $line = fgets($sms_socket, $length + 1);
        $length -= strlen($line);

        // if HTTP status was 200 OK, parse the lines
        if ($status[1] == 200 && $line) {
            $line = explode(" ", $line, 3);
            $success = ($line[1] == "OK");  // OK->TRUE, ERROR->FALSE
            if ($success) {
                $success_count++;
            }
            $sms_result[$dest_array[$i]] = $success;
        }
    }
    $sms_result["success_count"] = $success_count;
    
    return $sms_result;
}

?>
