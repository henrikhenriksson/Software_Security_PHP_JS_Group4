<?php

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: member.php
 * Desc: Member page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

session_start();

// If user is not logged in, redirect to index.php
if (isset($_SESSION['username'])) {
    $title = "Laboration 2";
} else {
    header("Location: index.php"); /* Redirect browser */
    exit;
}
?>


<!DOCTYPE html>
<html lang="sv-SE">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DT161G-Laboration2-member</title>
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
        </aside>
        <section>
            <h2>Medlemssida</h2>
            <p>Denna sida skall bara kunna ses av inloggade medlemmar</p>
        </section>
    </main>
    <footer>
        Footer
    </footer>
</body>

</html>