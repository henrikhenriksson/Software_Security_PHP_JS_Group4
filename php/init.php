<?php

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: util.php
 * Desc: Util file for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

require_once __DIR__.'/vendor/autoload.php';  // Let composer handle autoloads
session_start();
Session::init(new WebSession());  // Init a static session klass

function resetSession()
{
    // Unset all of the session variables except captcha.
    foreach ($_SESSION as $key => $value) {
        if ($key !== "captcha") {
            unset($_SESSION[$key]);
        }
    }
}

require_once __DIR__.'/functions/strings.php';
require_once __DIR__.'/globals.php';

if (getConfig()->useDebugMode()) {
    require_once __DIR__.'/functions/debug.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
