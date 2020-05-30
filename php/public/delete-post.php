<?php

require_once __DIR__ . '/../resources/init.php';

use \ParagonIE\AntiCSRF\AntiCSRF as TokenLib;

$token = new TokenLib();
$member = Member::fromSession();

function sendResponse( bool $valid)
{
    $response = $valid? "true" : "false";
    header('Content-Type: application/json');
    echo json_encode($response);
}

if (!isset($_POST['post_id'])) {
    // Expecting this as an unintended call since no data to modify is supplied
    sendResponse(false);
    exit;
}

if(  ($member->id() <= 0) )
{
    InvReq::addInvalidRequest('delete_post_no_user', 'NA');
    sendResponse(false);
    exit;
}

if(!$token->validateRequest()) {
    InvReq::addInvalidRequest('delete_post_invalid_token', $member->username());
    sendResponse(false);
    exit;
}

// Valid verification data, return success of operation
sendResponse(Post::deletePost($_POST['post_id']));




