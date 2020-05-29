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

// Shows form for logged in users, otherwise a welcome message
$formClass = Member::loggedIn() ? "" : "hide";
$welcomeClass = Member::loggedIn() ? "hide" : "";

$errorMsg = "";

// Om användaren har submittat något.
if (!empty($_POST)) {

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
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
    <header>
        <img src="img/mittuniversitetet.jpg" alt="miun logga" class="logo" />
        <h1><?php echo $title ?></h1>
    </header>
    <main>
        <aside>
            <?php require __DIR__ . '/../resources/views/aside-login.php'; ?>
            <?php require __DIR__ . '/../resources/views/aside-search.php'; ?>
        </aside>
        <section>
            <!-- Guestbook form -->
            <div id="gb-form" class="<?php echo $formClass ?>">
                <form id="guestbookForm" class="<?php echo $gbFormClass; ?>" action="index.php" method="POST">
                    <fieldset>
                        <legend>Add post</legend>
                        <textarea id="post-message" name="post-message" rows="10" cols="50" placeholder="Enter message here..."></textarea>
                        <br>
                        <button type="submit">Send</button>
                        <span id="post-error" class="red"><?php echo $errorMsg; ?></span>
                    </fieldset>

                    <!-- Security token / timestamp submitted with post -->
                    <input type="hidden" id="post-token" name="post-token" value="<?php echo Token::generateToken('post'); ?>">
                    <input type="hidden" id="post-ts" name="post-ts" value="<?php echo Token::generateTs(); ?>">
                </form>
            </div>

            <!-- Welcome message that is shown if user is logged out -->
            <div id="welcome-message" class="<?php echo $welcomeClass ?>">
                <h2>Welcome!
                </h2>
                <p>This is a social networking page where you can share your thoughts on software security. <br />
                    Just log in to your account or sign up to post a message.</p>
                <br>
                <hr>
                <br>
            </div>
            <br>
            <br>
            
            <!-- Check if there is any posts to print out. -->
            <?php if (empty($posts)) : ?>
                <h2>The guestbook is empty</h2>
            <?php else : ?>
                <h2>Guestbook posts</h2>
                <table>
                    <tr>
                        <th class="th20">FROM</th>
                        <th class="th40">POST</th>
                        <th class="th20">LOG</th>
                        <th class="th10">LIKES</th>
                        <th id="trash-bin-td-border" class="th5"></th>
                    </tr>
                    <div id="gustbook-posts">
                    <!-- Print out the posts, latest post first. -->
                        <?php foreach (array_reverse($posts) as $post) : ?>
                            <tr>
                                <td><?php echo $post->getName(); ?></td>
                                <td><?php echo $post->getMessage(); ?></td>
                                <td><?php echo "IP: {$post->getIplog()}"; ?><br><?php echo "TID: {$post->getTimelog()}"; ?></td>
                                <td>
                                    <i  <?php
                                        // Set likes based on user being logged in and previous likes
                                        if (Member::loggedIn() && Post::isRatedByUser($post->getId(), Member::fromSession()->id(), 'like')) : ?>

                                        class="fas fa-thumbs-up like-btn" 

                                        <?php else : ?> 

                                        class="far fa-thumbs-up like-btn" 

                                        <?php endif; ?> data-id="<?php echo $post->getId(); ?>">
                                    </i>
                                        
                                    <!-- Get the number of likes for current post. -->
                                    <span class="likes"><?php echo Post::getRatingCount($post->getId(), 'like'); ?></span>

                                    <i <?php
                                        // Set dislikes based on user being logged in and previous dislikes
                                        if (Member::loggedIn() && Post::isRatedByUser($post->getId(), Member::fromSession()->id(), 'dislike')) : ?>
                                        class="fas fa-thumbs-down dislike-btn" <?php else : ?>
                                        class="far fa-thumbs-down dislike-btn" <?php endif; ?> data-id="<?php echo $post->getId(); ?>">
                                    </i>

                                    <!-- Get the number of dislikes for current post. -->
                                    <span class="dislikes"><?php echo Post::getRatingCount($post->getId(), 'dislike'); ?></span>
                                </td>

                                <!-- If current post belongs to logged in user, enable deletion by displaying a trash bin. -->
                                <td id="trash-bin-td-border">
                                    <?php if (Member::loggedIn() && Member::fromSession()->username() == $post->getName()) : ?>
                                        <i class="far fa-trash-alt delete-post" data-id="<?php echo $post->getId(); ?>"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Security token / timestamp submitted when liking , disliking and deleting posts -->
                        <input type="hidden" id="gb-token" value="<?php echo Token::generateToken('delete-post'); ?>">
                        <input type="hidden" id="gb-ts" value="<?php echo Token::generateTs(); ?>">
                    </div>
                </table>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>