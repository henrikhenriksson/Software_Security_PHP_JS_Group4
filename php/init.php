<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: init.php
 ******************************************************************************/


session_start();  // Make session global


function resetSession()
{
    // Unset all of the session variables except captcha.
    foreach ($_SESSION as $key => $value) {
        if ($key !== "captcha") {
            unset($_SESSION[$key]);
        }
    }
}

require_once __DIR__ . '/vendor/autoload.php';  // Let composer handle autoloads
require_once __DIR__ . '/functions/strings.php';
require_once __DIR__ . '/globals.php';

if (getConfig()->useDebugMode()) {
    require_once __DIR__ . '/functions/debug.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
