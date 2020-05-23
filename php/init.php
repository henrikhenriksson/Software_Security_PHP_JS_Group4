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

function resetSession()
{
    // Unset all of the session variables except captcha.
    foreach ($_SESSION as $key => $value) {
        if ($key !== "captcha") {
            unset($_SESSION[$key]);
        }
    }
}

/*******************************************************************************
 * autoload functions for Classes stored i directory classes
 * All classes must be saved i lower case to work and end whit class.php
 ******************************************************************************/
spl_autoload_register(function ($class) {
    $classfilename = strtolower($class);
    include 'classes/' . $classfilename . '.class.php';
});

require_once 'functions/strings.php';
require_once 'globals.php';

if (getConfig()->useDebugMode()) {
    require_once 'functions/debug.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
