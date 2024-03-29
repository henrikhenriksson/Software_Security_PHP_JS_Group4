<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: login.php
 * Handles login requests.
 ******************************************************************************/

require_once __DIR__ . '/../resources/init.php';
use \ParagonIE\AntiCSRF\AntiCSRF as TokenLib;

$token = new TokenLib();

function _sendInvalidResponseMessage($msg):void
{
    $responseText = ['isValidLogin'=> false, 'msg'=>$msg];

    sendResponse($responseText);
}

function _sendInvalidResponseComplex($response)
{
    $response['isValidLogin'] = false;
    sendResponse($response);
}

function _sendValidResponseMessage($msg):void
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
    Session::kill();
    _sendInvalidResponseMessage('Ip blocked');
    exit;
}


if (!isset($_POST["_CSRF_TOKEN"]) || !isset($_POST["_CSRF_INDEX"])) {
    // Cross reference protection not provided
    InvReq::addInvalidRequest('missing Token data', 'na');
    Session::kill();
    _sendInvalidResponseMessage("Required login data not provided");
    exit;
}



if (! $token->validateRequest()) {
    InvReq::addInvalidRequest('invalidTokenLogin', 'na');
    Session::kill();
    _sendInvalidResponseComplex([
        'msg'=>"Invalid token",
        //'newToken'=>$token->getTokenArray('./login')
        //'newToken'=>\htmlentities($token->getTokenArray('./login'), ENT_QUOTES, 'UTF-8')
        'newToken'=>Token::generateTokenArray($token, './login')
    ]);
    exit;
}

$member = Member::login($_POST["uname"], $_POST['psw']);

// Set response data
if ($member->error()) {
    InvReq::addInvalidRequest('invalidLoginCredentials', $member->username());
    _sendInvalidResponseComplex([
        'msg'=>$member->errorMessage(),
        'newToken'=>$token->getTokenArray('./login')
    ]);
    exit;
}

_sendValidResponseMessage('valid login');
