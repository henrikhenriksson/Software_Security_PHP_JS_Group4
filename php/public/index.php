<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: index.php
 * Main index of the app.
 ******************************************************************************/

$title = "Group 4 Guestbook";
require_once __DIR__ . '/../resources/init.php';

// Usage example of DB class.
$posts = Post::fetchAll();  // Use posts class to retrieve

$formClass = "hide";
$welcomeClass = "";
$member = null;
if (Member::loggedIn()) {
    $formClass = "";
    $welcomeClass = "hide";
    $member = Member::fromSession();
    if ($member->error()) {
        $member = null;
    }
}

// Shows form for logged in users, otherwise a welcome message

$errorMsg = "";

// Om användaren är inloggad och har submittat något.
if ($member && isset($_POST)) {
    if (isset($_POST['post-message']) && isset($_POST['post-ts']) && isset($_POST['post-token'])) {

        // Validera TS och TOKEN
        if (Token::validateToken("login", $_POST["post-ts"], $_POST["post-token"])) {
            $post = Post::fromForm(Member::fromSession()->username(), $_POST['post-message']);

            $post->save();

            if ($post->isSetError()) {
                $errorMsg = $post->getErrorMessage();
            } else {
                // Refresha sidan
                header("Location: index.php");
            }
        }
    }
}

/*******************************************************************************
 * HTML section starts here
 ******************************************************************************/
?>
<!DOCTYPE html>
<html lang="sv-SE">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=  $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
    <header>
        <img src="img/mittuniversitetet.jpg" alt="miun logga" class="logo" />
        <h1><?=  $title ?></h1>
    </header>
    <main>
        <aside>
            <?php require __DIR__ . '/../resources/views/aside-login.php'; ?>
            <?php require __DIR__ . '/../resources/views/aside-search.php'; ?>
        </aside>

        <section class="content-wrapper">
            <!-- Guestbook form -->
            <section id="gb-form" class="<?=  $formClass ?>">
                <form id="guestbookForm" class="<?=  $formClass ?>" action="index.php" method="POST">
                    <fieldset>
                        <legend>Add post</legend>
                        <textarea id="post-message" name="post-message" rows="10" cols="50" placeholder="Enter message here..."></textarea>
                        <br>
                        <button type="submit">Send</button>
                        <span id="post-error" class="red"><?=  $errorMsg ?></span>
                    </fieldset>

                    <!-- Security token / timestamp submitted with post -->
                    <input type="hidden" id="post-token" name="post-token" value="<?=  Token::generateToken('post') ?>">
                    <input type="hidden" id="post-ts" name="post-ts" value="<?=  Token::generateTs() ?>">
                </form>
            </section>

            <!-- Welcome message that is shown if user is logged out -->
            <section id="welcome-message" class="<?=  $welcomeClass ?>">
                <h2>Welcome!
                </h2>
                <p>This is a social networking page where you can share your thoughts on software security. <br />
                    Just log in to your account or sign up to post a message.</p>
                <br>
                <hr>
                <br>
            </section>
            <br>
            <br>

            <!-- Check if there is any posts to print out. -->
            <section id="gustbook-posts">

                <?php if (empty($posts)) : ?>
                <h2>The guestbook is empty</h2>

                <?php else : ?>
                <h2>Guestbook posts</h2>
            <!-- Print out the posts, latest post first. -->
                <?php foreach (array_reverse($posts) as $post) : ?>
                    <?php require __DIR__ . '/../resources/views/post.php'; ?>
                <?php endforeach; ?>
                <!-- Security token / timestamp submitted when liking , disliking and deleting posts -->
                <input type="hidden" id="gb-token" value="<?=  Token::generateToken('delete-post') ?>">
                <input type="hidden" id="gb-ts" value="<?=  Token::generateTs() ?>">
                <?php endif; ?>
            </section><!-- gbposts -->
        </section><!-- content wrapper -->
    </main>

    <footer>
        Footer
    </footer>
</body>

</html>
