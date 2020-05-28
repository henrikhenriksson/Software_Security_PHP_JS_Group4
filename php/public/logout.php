<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: aside-login-php
 * Provides access methods for global, single instances.
 ******************************************************************************/

// Initialize the session.
require_once __DIR__ . '/../resources/init.php';

// Check if user has previously posted and thus have a set cookie
$responseText = [];
$responseText["hasPosted"] = isset($_COOKIE["MIUN_GUESTBOOK"]);

Member::logout();

$responseText["msg"] = "You are logged out and the session cookie has been destroyed";
//$responseText["links"] = $link_array;

// Send back response
header('Content-Type: application/json');
echo json_encode($responseText);