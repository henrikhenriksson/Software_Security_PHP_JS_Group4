<?php

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: aside-login.php
 * Desc: Start page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

$loginClass = Member::loggedIn() ? "hide" : "";
$logoutClass = Member::loggedIn() ? "" : "hide";
?>

<div id="login" class="<?php echo $loginClass; ?>">
    <h2>LOGIN</h2>
    <form id="loginForm">
        <input type="hidden" id="token" value="<?php echo Token::generateToken('login');?>">
        <input type="hidden" id="TS" value="<?php echo Token::generateTs();?>">
        <label><b>Username</b></label>
        <input type="text" placeholder="m" name="uname" id="uname" required maxlength="10" value="m" autocomplete="off">
        <label><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
        <button type="button" id="loginButton">Login</button>
    </form>
</div>
<div id="logout" class="<?php echo $logoutClass; ?>">
    <h2>LOGOUT</h2>
    <button type="button" id="logoutButton">Logout</button>
</div>
<p id="loginMsg"></p>