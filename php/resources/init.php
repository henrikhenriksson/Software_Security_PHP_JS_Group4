<?php declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: init.php
 ******************************************************************************/

require_once __DIR__.'/../vendor/autoload.php';  // Let composer handle autoloads

// Both these options are forced by the server now, but why "trust" the server
// Reduces risk of common XSS attacks (if the browser enforces it).
session_start([
    'cookie_httponly' => true,  // Only allow server to read session cookie
                                // i.e don't show it to javascript
    'cookie_secure' => true     // Only send cookie over https
]);

Session::init(new WebSession());  // Init a static session klass


if (getConfig()->useDebugMode()) {
    require_once __DIR__ . '/functions/debug.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
