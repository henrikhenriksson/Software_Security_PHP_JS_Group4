<?php declare(strict_types=1);

/**
 * All that is needed to print a post is is to declare a $post variable before
 * including this view.
 */

$member = Member::loggedIn() ? $member = Member::fromSession() : null;

require_once __DIR__ . '/../functions/post.php';

?>

<article class="post">
    <div class="post-header">
        <h4><?=  $post->getName() ?> wrote:</h4>
        <p class="time">At: <?=  $post->getTimeLog() ?></p>
    </div>
    <p class="content"><?=  $post->getMessage() ?></p>
    <footer>
        <div class="post-stats">
            <!-- like btn -->
            <i data-id="<?=  $post->getId() ?>"
                class="<?=  like_btn_class($post->getId()) ?>">
            </i>
            <!-- Get the number of likes for current post. -->
            <span class="likes"><?=  Post::getRatingCount($post->getId(), 'like') ?>
            </span>

            <!-- dislike btn -->
            <i data-id="<?=  $post->getId() ?>"
                class="<?=  dislike_btn_class($post->getId()) ?>">
            </i>
            <!-- Get the number of likes for current post. -->
            <span class="dislikes"><?=  Post::getRatingCount($post->getId(), 'dislike') ?>
            </span>
        </div>

        <?php if (member_owns_post($post->getName())): ?>
        <div class="delete-post">
            <i class="far fa-trash-alt delete-post"
                data-id="<?=  $post->getId() ?>">
            </i>
        </div>
        <?php endif; ?>

    </footer>
</article>
