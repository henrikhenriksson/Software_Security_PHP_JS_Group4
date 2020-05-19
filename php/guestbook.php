<?PHP

/*******************************************************************************
 * Project, Kurs: DT167G
 * File: guestbook.php
 * Desc: Guestbook page for project
 *
 * Group 4
 ******************************************************************************/
$title = "DT167G - Group 4";
require_once 'util.php';

# MAIN()
session_start();
date_default_timezone_set("Europe/Stockholm");

// Variabler
$dbHandler = DatabaseHandler::getInstance();
$name = "";
$text = "";
$isLoggedIn = isset($_SESSION['username']);
$gbFormClass = ($isLoggedIn) ? "" : "hide";
$posts = $dbHandler->getPosts();


// TODO: Add TS/TOKEN security instead of $isLoggedIn check

// Om användaren har submittat något och är inloggad
if (!empty($_POST) && $isLoggedIn) {
    // Skapa en post av användarens input
    $post = new Post(trim($_POST["name"]), trim($_POST["text"]));
    // Skicka posten till databasen
    $dbHandler->addPost($post->toArray());
    // Refresha sidan
    header("Location: guestbook.php");
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
    <link rel="stylesheet" href="css/style.css" />
    <title>DT167G - Group 4</title>
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
            <h2>GÄSTBOK</h2>

            <table>
                <tr>
                    <th class="th20">FRÅN
                    </th>
                    <th class="th40">INLÄGG
                    </th>
                    <th class="th40">LOGGNING
                    </th>
                </tr>
                <!-- Skriv ut posts i gästboken -->
                <?php foreach ($posts as $post) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post->getName()); ?></td>
                        <td><?php echo htmlspecialchars($post->getMessage()); ?></td>
                        <td><?php echo "IP: {$post->getIplog()}"; ?><br><?php echo "TID: {$post->getTimelog()}"; ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>

            <form id="guestbookForm" class="<?php echo $gbFormClass; ?>" action="guestbook.php" method="POST">
                <fieldset>
                    <legend>Skriv i gästboken</legend>
                    <label>Från: </label>
                    <input type="text" placeholder="Skriv ditt namn" name="name" required value="<?php echo $name; ?>">
                    <br>
                    <label for="text">Inlägg</label>
                    <textarea id="text" name="text" rows="10" cols="50" placeholder="Skriv meddelande här" required><?php echo $text; ?></textarea>
                </fieldset>
            </form>

        </section>
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>