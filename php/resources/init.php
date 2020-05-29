<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: init.php
 ******************************************************************************/

require_once __DIR__ . '/../vendor/autoload.php';  // Let composer handle autoloads

// Both these options are forced by the server now, but why "trust" the server
// Reduces risk of common XSS attacks (if the browser enforces it).
session_start([
    'cookie_httponly' => true,  // Only allow server to read session cookie
    // i.e don't show it to javascript
    'cookie_secure' => true     // Only send cookie over https
]);

// TODO look at https://github.com/paragonie/csp-builder
// Only allow scripts from this domain to run
header("Content-Security-Policy: script-src 'self' https://link.to.font.awesome/*");

// Only send full referrer information (where the request came from) to this site
// over https, and only domain name to other sites over https. Any http request
// to both this and other sites will not contain referrer information at all.
header('Referrer-Policy: strict-origin-when-cross-origin');

// Use HTTPS for future requests.
header('Strict-Transport-Security: max-age=63072001');

// Enforce mime-types on <style> and <script> tags or block the request
// Also enables Cross-Origin Read Blocking (CORB) protection
// Note: might have to be replaced for file downloads
header('X-Content-Type-Options: nosniff');

// Disallow <frames> to avoid Click Hijacking
header('X-Frame-Options: Deny');

// Enable extra XSS protection in browser.
header('X-XSS-Protection: 1; mode=block');


Session::init(new WebSession());  // Init a static session klass


if (getConfig()->useDebugMode()) {
    require_once __DIR__ . '/functions/debug.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
