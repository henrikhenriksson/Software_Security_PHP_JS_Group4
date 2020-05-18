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

/*******************************************************************************
 * autoload functions for Classes stored i directory classes
 * All classes must be saved i lower case to work and end whit class.php
 ******************************************************************************/
spl_autoload_register(function ($class) {
    $classfilename = strtolower($class);
    include 'classes/' . $classfilename . '.class.php';
});

/*******************************************************************************
 * set debug true/false to change php.ini
 * To get more debug information when developing set to true,
 * for production set to false
 ******************************************************************************/

if (Config::getInstance()->useDebugMode()) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
