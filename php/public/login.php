<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: login.php
 * Handles login requests.
 ******************************************************************************/

require_once __DIR__ . '/../resources/init.php';

$responseText = [];

if (!isset($_POST["token"]) || !isset($_POST["TS"])) {
    // Cross reference protection not provided
    ///@todo decide action

    $responseText["msg"] = "Required login data not provided";
} else {
    if (Token::validateToken("login", $_POST["TS"], $_POST["token"])) {
        $member = Member::login($_POST["uname"], $_POST['psw']);

        // Set response data
        $responseText["isValidLogin"] = !$member->error();
        if (is_null($member->errorMessage())) {
            $responseText['msg'] = "";
        } else {
            $responseText['msg'] = $member->errorMessage();
        }
    } else {
        ///@todo decide invalid token message
        $responseText['msg'] = "Invalid token";
    }
    // Send back response
}
header('Content-Type: application/json');
echo json_encode($responseText);
