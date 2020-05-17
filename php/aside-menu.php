<?php

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: aside-menu.php
 * Desc: Start page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

?>

<h2>MENY</h2>
<nav>
    <ul id="menu-list">
        <?php if (isset($_SESSION['username'])) : ?>
            <?php foreach ($_SESSION['link_array'] as $key => $value) : ?>
                <li>
                    <a href="<?php echo $value; ?>"><?php echo $key; ?></a>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>
                <a href="index.php">Hem</a>
            </li>
            <li>
                <a href="guestbook.php">Gästbok</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>