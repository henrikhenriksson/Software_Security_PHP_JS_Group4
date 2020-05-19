<?PHP

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: guestbook.php
 * Desc: Guestbook page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
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
//$hasPosted = isset($_COOKIE["MIUN_GUESTBOOK"]);
$isLoggedIn = isset($_SESSION['username']);
//$gbFormClass = (!$hasPosted || $isLoggedIn) ? "" : "hide";
$gbFormClass = ($isLoggedIn) ? "" : "hide";
$posts = $dbHandler->getPosts();

// Om användaren har submittat något
if (!empty($_POST)) {
    // Skapa en post av användarens input
    $post = new Post(trim($_POST["name"]), trim($_POST["text"]));
    // Skicka posten till databasen
    $dbHandler->addPost($post->toArray());

    // Sätt cookie som anger att användaren har gjort en post
    setcookie("MIUN_GUESTBOOK", "HAS_POSTED");
    header("Location: guestbook.php"); // Refresha sidan
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
    <title>DT161G-Laboration2</title>
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
                    <textarea id="text" name="text" rows="10" cols="50" placeholder="Skriva meddelande här" required><?php echo $text; ?></textarea>
                </fieldset>
            </form>

        </section>
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>