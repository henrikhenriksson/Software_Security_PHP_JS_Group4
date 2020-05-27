<?PHP

declare(strict_types=1);

/*******************************************************************************
 * Projekt, Kurs: DT167G
 * File: signup.php
 * Desc: dedicated page to handle new signups.
 ******************************************************************************/
$title = "DT167G - Group 4";

require_once 'init.php';
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
            <div id="new_member">

                <h2 class>Sign up To Use the Website</h2>
                <br>
                <form id="signup_form">
                    <input type="hidden" name="su_token" id="su_token" value="<?php echo Token::generateToken('signup'); ?>">
                    <input type="hidden" name="su_ts" id="su_ts" value="<?php echo Token::generateTs(); ?>">
                    <div>
                        <input type="text" placeholder="Enter Username" name="user_name" id="userName" minlength="1" maxlength="10" autocomplete="off" required>
                    </div>
                    <div>
                        <input type="password" placeholder="Enter Password" name="password" id="password1" minlength="1" maxlength="64" autocomplete="off" required>
                    </div>
                    <div>
                        <input type="password" placeholder="Re-enter Password" name="password2" id="password2" minlength="1" maxlength="64" autocomplete="off" required>
                    </div>

                    <button type="button" id=sign_up_button> <b>Sign Up!</b></button>
                    <p id="signup_message"></p>

                </form>
            </div>
        </section>
    </main>
</body>
<footer>
    Footer
</footer>

</html>