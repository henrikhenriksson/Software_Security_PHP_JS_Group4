<?php

/*******************************************************************************
 * Projekt, Kurs: DT167G
 * File: search.php
 * Desc: Search page for displaying guestbook posts matching a keyword or user.
 *
 * Gang of Five
 ******************************************************************************/
$title = "Group 4 Guestbook";

require_once __DIR__ . '/../resources/init.php';

$posts = null;

if (isset($_GET["search-type"]) && isset($_GET["search-field"])) {
    if ($_GET["search-type"] == "username") {
        $posts = Post::fromUsername($_GET["search-field"]);
    } elseif ($_GET["search-type"] == "keyword") {
        $posts = Post::fromKeyword($_GET["search-field"]);
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
            <a href="index.php">Back to guestbook</a>
        </aside>
        <section class="content-wrapper">
            <section>
                <h2>Search results</h2>

                <?php if (empty($posts)) : ?>
                    <p>Your search did not return any posts.</p>
                <?php else : ?>
                    <!-- Print out the posts, latest post first. -->
                    <?php foreach (array_reverse($posts) as $post) : ?>
                        <?php require __DIR__ . '/../resources/views/post.php'; ?>
                    <?php endforeach; ?>
                <!-- Security token / timestamp submitted when liking , disliking and deleting posts -->
                <input type="hidden" id="gb-token" value="<?=  Token::generateToken('delete-post') ?>">
                <input type="hidden" id="gb-ts" value="<?=  Token::generateTs() ?>">
                <?php endif; ?>
            </section><!-- posts -->
        </section><!-- content wrapper -->
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>
