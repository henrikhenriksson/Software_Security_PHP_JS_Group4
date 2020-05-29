<?php

require_once __DIR__ . '/../resources/init.php';

$response = "false";

if (isset($_POST['post_id']) && isset($_POST['token']) && isset($_POST['ts'])) {

    if (Token::validateToken("login", $_POST["ts"], $_POST["token"])) {
        if (Post::deletePost($_POST['post_id'])) {
            $response = "true";
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
