<?PHP

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: index.php
 * Desc: Start page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/
$title = "laboration 4";

session_start();

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
            <h2>VÄLKOMMEN
            </h2>
            <p>Detta är andra laborationen</p>
        </section>
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>