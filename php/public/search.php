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
        <section>
            <h2>Search Results</h2>
            <!-- Check if there is any posts to print out. -->
            <?php if ($posts != null && !empty($posts)) : ?>
                <table>
                    <tr>
                        <th class="th20">FROM</th>
                        <th class="th40">POST</th>
                        <th class="th20">LOG</th>
                        <th class="th10">LIKES</th>
                        <th id="trash-bin-td-border" class="th5"></th>
                    </tr>
                    <!-- Print out the searched posts -->
                    <?php foreach ($posts as $post) : ?>
                        <tr>
                            <td><?php echo $post->getName(); ?></td>
                            <td><?php echo $post->getMessage(); ?></td>
                            <td><?php echo "IP: {$post->getIplog()}"; ?><br><?php echo "TID: {$post->getTimelog()}"; ?></td>
                            <td>
                                <i <?php
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
                                    
                                    class="fas fa-thumbs-down dislike-btn" 
                                    
                                    <?php else : ?> 
                                        
                                    class="far fa-thumbs-down dislike-btn" 
                                    
                                    <?php endif; ?> data-id="<?php echo $post->getId(); ?>">
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
                    <!-- Security token / timestamp submitted when liking, disliking and deleting posts -->
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