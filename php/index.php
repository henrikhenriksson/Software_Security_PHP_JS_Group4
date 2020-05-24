<?php declare(strict_types=1);

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: index.php
 * Desc: Start page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/
$title = "DT167G - Group 4";
require_once 'init.php';

// Usage example of DB class.
$posts = [];  // Use posts class to retrieve

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
            <h2>Welcome!
            </h2>
            <p>This is a social networking page where you can share your thoughts on software security. <br />
                Just log in to your account or sign up to post a message.</p>
            <br>
            <hr>
            <br>
            <?php if (empty($posts)) : ?>
                <h2>No recent posts</h2>
            <?php else : ?>
                <h2>Recent posts</h2>
                <table>
                    <tr>
                        <th class="th20">FROM
                        </th>
                        <th class="th40">POST
                        </th>
                        <th class="th40">LOG
                        </th>
                    </tr>
                    <!-- Display the five most recent posts. -->
                    <?php for ($i = 0; $i < sizeof($posts) && $i < 5; $i++) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($posts[$i]->getName()); ?></td>
                            <td><?php echo htmlspecialchars($posts[$i]->getMessage()); ?></td>
                            <td><?php echo "IP: {$posts[$i]->getIplog()}"; ?><br><?php echo "TID: {$posts[$i]->getTimelog()}"; ?></td>
                        </tr>
                    <?php endfor; ?>

                </table>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>
