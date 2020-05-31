<?php

require_once __DIR__ . '/../resources/init.php';
require_once __DIR__ . '/../resources/functions/post.php';

use \ParagonIE\AntiCSRF\AntiCSRF as TokenLib;

$token = new TokenLib();

$passedTokenValidation = false;
if (!InvReq::validIpCurUser()) {
    ajax_respond([ 'msg' => 'IP blocked' ]);
}

if (!isset($_POST)) {
    // Expecting this as an unintended call since no data to modify is supplied
    ajax_respond(['msg' => "Not a POST request"]);
}
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if (!$post_id) {
    // Expecting this as an unintended reques since no data to modify is supplied
    ajax_respond([
        'msg' => "Not a valid request format",
]);
}

if (!Member::loggedIn()) {
    InvReq::addInvalidRequest('delete_post_no_user', 'NA');
    ajax_respond(['msg' => 'Not logged in']);
}
$member = Member::fromSession();

if (!$token->validateRequest()) {
    InvReq::addInvalidRequest('delete_post_invalid_token', $member->username());
    ajax_respond(['msg' => 'Invalid token']);
}

$post = Post::fetchById($post_id);
if ($post->isSetError()) {
    ajax_respond(['msg' => $post->getErrorMessage()]);
}

if (!member_owns_post($post->getName())) {
    InvReq::addInvalidRequest('delete_post_unauthorized', $member->username());
    ajax_respond(['msg' => 'This is not your post!']);
}

/* ============================== TOKEN VALIDATED ========================== */
$passedTokenValidation = true;

// Valid verification data, return success of operation
if (!Post::deletePost($_POST['post_id'])) {
    ajax_respond(['msg' => 'Cannot delete post!']);
}
ajax_respond(['msg' => 'Post was deleted'], true);


/* ================================= FUNCTIONS ============================= */

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
        $responseText['newToken'] = Token::generateTokenArray($token, '/delete-post.php');
    }

    header('Content-Type: application/json');
    echo json_encode($responseText);
    exit;
}
