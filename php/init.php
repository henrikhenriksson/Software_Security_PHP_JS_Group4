<?php declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: init.php
 ******************************************************************************/

require_once __DIR__.'/vendor/autoload.php';  // Let composer handle autoloads
session_start();
Session::init(new WebSession());  // Init a static session klass

require_once __DIR__.'/functions/strings.php';
require_once __DIR__.'/globals.php';

if (getConfig()->useDebugMode()) {
    require_once __DIR__ . '/functions/debug.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
