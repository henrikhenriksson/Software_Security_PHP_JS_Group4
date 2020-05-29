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
        <label>
            <p><b>Username</b></p>
        </label>
        <input type="text" placeholder="m" name="uname" id="uname" required maxlength="10" value="m" autocomplete="off">
        <label>
            <p><b>Password</b></p>
        </label>
        <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
        <br>
        <button type="button" id="loginButton">Login</button>
        <span id="loginMsg" class="red"></span>
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