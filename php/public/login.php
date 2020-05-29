<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: login.php
 * Handles login requests.
 ******************************************************************************/

require_once __DIR__ . '/../resources/init.php';
use \ParagonIE\AntiCSRF\AntiCSRF as Token;

$token = new Token();

function _sendInvalidMessageResponse($msg):void
{
    $responseText = ['isValidLogin'=> false, 'msg'=>$msg];

    sendResponse($responseText);
}

function _sendValidMessageResponse($msg):void
{
    $responseText = ['isValidLogin'=> true, 'msg'=>$msg];

    sendResponse($responseText);
}

function sendResponse($responseText)
{
    header('Content-Type: application/json');
    echo json_encode($responseText);
}

if (!InvReq::validIpCurUser()) {
    _sendInvalidMessageResponse('Ip blocked');
    exit;
}


if (!isset($_POST["_CSRF_TOKEN"]) || !isset($_POST["_CSRF_INDEX"])) {
    // Cross reference protection not provided
    ///@todo decide action
    InvReq::addInvalidRequest('missing Token data', 'na');
    _sendInvalidMessageResponse("Required login data not provided");
    exit;
}

if (! $token->validateRequest() ) {
    InvReq::addInvalidRequest('invalidTokenLogin', 'na');
    _sendInvalidMessageResponse("Invalid token");
    exit;
}

$member = Member::login($_POST["uname"], $_POST['psw']);

// Set response data
if( $member->error() )
{
    InvReq::addInvalidRequest('invalidLoginCredentials', $member->username());
    _sendInvalidMessageResponse($member->errorMessage());
    ///@todo regenerate_session_id() and Send new token back to user, must only be sent when the first token is valid
    exit;
}

_sendValidMessageResponse('valid login');





