<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: aside-login-php
 ******************************************************************************/

$loginClass = Member::loggedIn() ? "hide" : "";
$logoutClass = Member::loggedIn() ? "" : "hide";
?>

<div id="login" class="<?php echo $loginClass; ?>">
    <h2>LOGIN</h2>
    <form id="loginForm">
        <input type="hidden" id="token" value="<?php echo Token::generateToken('login'); ?>">
        <input type="hidden" id="TS" value="<?php echo Token::generateTs(); ?>">
        <label><b>Username</b></label>
        <input type="text" placeholder="m" name="uname" id="uname" required maxlength="10" value="m" autocomplete="off">
        <label><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
        <button type="button" id="loginButton">Login</button>
    </form>
    <h3>Not A Member?</h3>
    <p>
        <a href="signupForm.php">Sign up today!</a>
    </p>
</div>
<div id="logout" class="<?php echo $logoutClass; ?>">
    <h2>LOGOUT</h2>
    <button type="button" id="logoutButton">Logout</button>
</div>
<p id="loginMsg"></p>