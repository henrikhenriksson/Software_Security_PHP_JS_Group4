<?php

require_once __DIR__ . '/../resources/init.php';

if (Member::loggedIn() && isset($_POST['action']) && isset($_POST['post_id']) && isset($_POST['token']) && isset($_POST['ts'])) {

    if (Token::validateToken("login", $_POST["ts"], $_POST["token"])) {
        $post_id = $_POST['post_id'];
        $action = $_POST['action'];

        if ($action === "like" || $action === "dislike") {
            Post::setRating($post_id, Member::fromSession()->id(), $action);
        } elseif ($action === "unlike" || $action === "undislike") {
            Post::unsetRating($post_id, Member::fromSession()->id());
        }
    }
}

$likeCount = Post::getRatingCount($post_id, 'like');
$dislikeCount = Post::getRatingCount($post_id, 'dislike');

$response = [
    'likes' => $likeCount,
    'dislikes' => $dislikeCount
];

header('Content-Type: application/json');
echo json_encode($response);
