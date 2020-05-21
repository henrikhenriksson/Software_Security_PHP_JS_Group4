<?php

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: logout.php
 * Desc: Logout page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

// This array holds the links to be displayed when a user has logged out
$link_array = [
    "Hem" => "index.php",
    "Gästbok" => "guestbook.php",
];

// Initialize the session.
session_start();

// Check if user has previously posted and thus have a set cookie
$responseText = [];
$responseText["hasPosted"] = isset($_COOKIE["MIUN_GUESTBOOK"]);

// Unset all of the session variables except captcha.
foreach ($_SESSION as $key => $value) {
    if ($key !== "captcha") {
        unset($_SESSION[$key]);
    }
}

$responseText["msg"] = "You are logged out and the session cookie has been destroyed";
$responseText["links"] = $link_array;

// Send back response
header('Content-Type: application/json');
echo json_encode($responseText);
