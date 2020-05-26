<?php

/*******************************************************************************
 * Projekt, Kurs: DT167G
 * File: search.php
 * Desc: Search page for displaying guestbook posts matching a keyword or user.
 *
 * Gang of Five
 ******************************************************************************/
require_once __DIR__ . '/../resources/init.php';

$title = "laboration 4";

$posts = null;

if (isset($_GET["search-type"]) && isset($_GET["search-field"])) {
    if ($_GET["search-type"] == "username") {
        $posts = DatabaseHandler::getInstance()->searchUserPosts($_GET["search-field"]);
    } elseif ($_GET["search-type"] == "keyword") {
        $posts = DatabaseHandler::getInstance()->searchKeywordPosts($_GET["search-field"]);
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
    <title>DT161G-<?php echo $title ?></title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/main.js"></script>
</head>

<body>
    <header>
        <img src="img/mittuniversitetet.jpg" alt="miun logga" class="logo" />
        <h1><?php echo $title ?></h1>
    </header>
    <main>
        <aside>
            <?php require 'aside-login.php'; ?>
            <?php require 'aside-menu.php'; ?>
            <?php require 'aside-search.php'; ?>
        </aside>
        <section>
            <h2>Search Results</h2>
            <?php if ($posts != null && !empty($posts)) : ?>
                <table>
                    <tr>
                        <th class="th20">FROM
                        </th>
                        <th class="th40">POST
                        </th>
                        <th class="th40">LOG
                        </th>
                    </tr>
                    <?php foreach ($posts as $post) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post->getName()); ?></td>
                            <td><?php echo htmlspecialchars($post->getMessage()); ?></td>
                            <td><?php echo "IP: {$post->getIplog()}"; ?><br><?php echo "TID: {$post->getTimelog()}"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <p>Your search did not return any posts.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>
