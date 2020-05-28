<?PHP

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
    } else if ($_GET["search-type"] == "keyword") {
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
    <link rel="stylesheet" href="css/all.min.css">
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
        <section>
            <h2>Search Results</h2>
            <?php if ($posts != null && !empty($posts)) : ?>
                <table>
                    <tr>
                        <th class="th20">FROM</th>
                        <th class="th40">POST</th>
                        <th class="th20">LOG</th>
                        <th class="th10">LIKES</th>
                        <th id="trash-bin-td-border" class="th5"></th>
                    </tr>
                    <?php foreach ($posts as $post) : ?>
                        <tr>
                            <td><?php echo escape($post['name']); ?></td>
                            <td><?php echo escape($post['message']); ?></td>
                            <td><?php echo "IP: {$post['iplog']}"; ?><br><?php echo "TID: {$post['timelog']}"; ?></td>
                            <td>
                                <i <?php
                                    if (Member::loggedIn() && Post::isRatedByUser($post['id'], Member::fromSession()->id(), 'like')) : ?> class="fas fa-thumbs-up like-btn" <?php else : ?> class="far fa-thumbs-up like-btn" <?php endif; ?> data-id="<?php echo $post['id']; ?>"></i>

                                <span class="likes"><?php echo Post::getRatingCount($post['id'], 'like'); ?></span>

                                <i <?php
                                    if (Member::loggedIn() && Post::isRatedByUser($post['id'], Member::fromSession()->id(), 'dislike')) : ?> class="fas fa-thumbs-down dislike-btn" <?php else : ?> class="far fa-thumbs-down dislike-btn" <?php endif; ?> data-id="<?php echo $post['id']; ?>">
                                </i>

                                <span class="dislikes"><?php echo Post::getRatingCount($post['id'], 'dislike'); ?></span>
                            </td>
                            <td id="trash-bin-td-border">
                                <?php if (Member::loggedIn() && Member::fromSession()->username() == escape($post['name'])) : ?>
                                    <i class="far fa-trash-alt delete-post" data-id="<?php echo $post['id']; ?>"></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <input type="hidden" id="gb-token" value="<?php echo Token::generateToken('delete-post'); ?>">
                    <input type="hidden" id="gb-ts" value="<?php echo Token::generateTs(); ?>">
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