<?php

declare(strict_types=1);

/*******************************************************************************
 * Projekt, Kurs: DT167G
 * File: signup.php
 * Desc: dedicated page to handle new signups.
 ******************************************************************************/

require_once __DIR__ . '/../resources/init.php';

use \ParagonIE\AntiCSRF\AntiCSRF as TokenLib;

$passedTokenValidation = false;

if (!InvReq::validIpCurUser()) {
    Session::kill();
    ajax_respond([ 'msg' => 'IP blocked' ]);
}

if (!isset($_POST)) {
    ajax_respond([ 'msg' => 'No signup information provided' ]);
}

$token = new TokenLib();
if (!$token->validateRequest()) {
    Session::kill();
    ajax_respond([ 'msg' => "Invalid token!"]);
}

// check if member is already logged in.
if (Member::loggedIn()) {
    ajax_respond([
        'msg' => 'You are already logged in and cannot create a new account!'
    ]);
}

// =============== TOKEN VALIDATION SUCCESSFUL =================
// Send new token for each new response
$passedTokenValidation = true;


if (!isset($_POST['password']) || !isset($_POST['password2'])) {
    ajax_respond([ 'msg' => "Password fields are required!" ]);
}


// validate password input:
if (($_POST['password'] !== $_POST['password2'])) {
    ajax_respond([
        'msg' => 'The passwords you entered does not match!'
    ]);
}

if (!isset($_POST['user_name'])) {
    ajax_respond([ 'msg' => "username is required!" ]);
}

$member = new Member();
if ($member->error()) {
    ajax_respond([ 'msg' => $member->errorMessage() ]);
}

$member->setUsername($_POST['user_name']);
if ($member->error()) {
    ajax_respond([ 'msg' => $member->errorMessage() ]);
}

if (!validateCaptcha($_POST['captcha'])) {
    ajax_respond([
        'msg' => 'Error passing captcha challenge.'
    ]);
}

// check if save was successfull
if (!$member->save($_POST['password'])) {
    ajax_respond([
        'msg' => $member->errorMessage()
    ]);
}

// happy path
ajax_respond_success([
    'msg' => 'You are now signed up and may post messages!',
]);


/* ============================== Functions ============================== */


function ajax_respond_success(array $responseText): void
{
    ajax_respond($responseText, true);
}

// send the reply
function ajax_respond(array $responseText, bool $success = false): void
{
    global $passedTokenValidation;
    global $token;

    $responseText['success'] = $success;
    if (!$success && $passedTokenValidation === true) {
        $responseText['newToken'] = Token::generateTokenArray($token, '/signup.php');
    }

    header('Content-Type: application/json');
    echo json_encode($responseText);
    exit;
}

// @Todo: Move this to a function file under /functions to increase scalability.
function validateCaptcha($captcha)
{
    $secret = '6Lfpr_0UAAAAANaYPlORYVfO-fXkBheXdc2VcMNL';
    $curlx = curl_init();
    curl_setopt($curlx, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($curlx, CURLOPT_HEADER, 0);
    curl_setopt($curlx, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlx, CURLOPT_POST, 1);

    $post_data = [
        'secret' => $secret,
        'response' => $captcha
    ];

    curl_setopt($curlx, CURLOPT_POSTFIELDS, $post_data);

    $resp = json_decode(curl_exec($curlx));

    curl_close($curlx);

    if (!$resp->success) {
        return false;
    }
    return true;
}
