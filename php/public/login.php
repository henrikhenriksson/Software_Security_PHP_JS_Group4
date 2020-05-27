<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: login.php
 * Handles login requests.
 ******************************************************************************/

require_once __DIR__ . '/../resources/init.php';

function sendResponse($responseText)
{
    header('Content-Type: application/json');
    echo json_encode($responseText);
}

$responseText = [];

if (!InvReq::validIpCurUser()) {
    sendResponse('Ip blocked');
    exit;
}


if (!isset($_POST["token"]) || !isset($_POST["TS"])) {
    // Cross reference protection not provided
    ///@todo decide action
    sendResponse("Required login data not provided");
    exit;
}

if (!Token::validateToken("login", $_POST["TS"], $_POST["token"])) {
    sendResponse("Invalid token");
    exit;
}

$member = Member::login($_POST["uname"], $_POST['psw']);

// Set response data
$responseText["isValidLogin"] = !$member->error();
if (!is_null($member->errorMessage())) {
    $responseText['msg'] = "";
} else {
    $responseText['msg'] = $member->errorMessage();
}
sendResponse($responseText);





