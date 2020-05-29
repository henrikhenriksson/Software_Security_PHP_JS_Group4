<?php

/**
 * Common operations when handling posts
 */


function like_btn_class(int $postid): string
{
    global $member;
    if ($member && member_has_liked($postid, $member->id())) {
        return "fas fa-thumbs-up like-btn";
    }
    return "far fa-thumbs-up like-btn";
}

function dislike_btn_class(int $postid): string
{
    global $member;
    if ($member && member_has_disliked($postid, $member->id())) {
        return "fas fa-thumbs-down dislike-btn";
    }
    return "far fa-thumbs-down dislike-btn";
}

function member_has_liked(int $postid, int $memberid): bool
{
    return Post::isRatedByUser($postid, $memberid, 'like');
}

function member_has_disliked(int $postid, int $memberid): bool
{
    return Post::isRatedByUser($postid, $memberid, 'dislike');
}

function member_owns_post(string $postname): bool
{
    global $member;
    return $member && $member->username() === $postname;
}
