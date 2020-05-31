<?php

require_once __DIR__ . '/../resources/init.php';


use \ParagonIE\AntiCSRF\AntiCSRF as TokenLib;

$passedTokenValidation = false;;
if (!InvReq::validIpCurUser()) {
    ajax_respond([ 'msg' => 'IP blocked' ]);
}

if (!isset($_POST)) {
    ajax_respond([ 'msg' => 'No rating information provided' ]);
}

$token = new TokenLib();
if (!$token->validateRequest()) {
    ajax_respond([ 'msg' => "Invalid token!"]);
}

// check if member is already logged in.
if (!Member::loggedIn()) {
    ajax_respond([
        'msg' => 'Cannot rate a post, not logged in!'
    ]);
}



$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if (!$action || !$post_id) {
    ajax_respond([ 'msg' => "Missing parameters"]);
}

/* ========================== Used valid Token ============================ */
// Send new token to allow rating new posts
$passedTokenValidation = true;


switch ($action) {
case "like":
case "dislike":
    Post::setRating($post_id, Member::fromSession()->id(), $action);
    break;
case "unlike":
case "undislike":
    Post::unsetRating($post_id, Member::fromSession()->id());
    break;
}

$likeCount = Post::getRatingCount($post_id, 'like');
$dislikeCount = Post::getRatingCount($post_id, 'dislike');

ajax_respond([
    'likes' => $likeCount,
    'dislikes' => $dislikeCount
], true);

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
    if ($passedTokenValidation === true) {
        $responseText['newToken'] = Token::generateTokenArray($token, '/rating.php');
    }

    header('Content-Type: application/json');
    echo json_encode($responseText);
    exit;
}
