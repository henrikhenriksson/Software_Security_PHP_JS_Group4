<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: aside-login-php
 * Provides access methods for global, single instances.
 ******************************************************************************/

// Initialize the session.
require_once __DIR__ . '/../resources/init.php';

Member::logout();

$responseText["msg"] = "You are logged out";

// Send back response
header('Content-Type: application/json');
echo json_encode($responseText);
