<?php declare(strict_types=1);

require_once __DIR__ . '/../resources/init.php';

/**
 * Test Member class on server (in browser)
 */


/**
 * Try login existing user.
 */
echo '<br><h3>Login user</h3><br>';
$new_user = Member::login('a', 'a');
//$new_user = Member::login('a', 'b');  // Wrong password
//$new_user = Member::login('z', 'b');  // Wrong username
if ($new_user->error()) {
    die("Login error: " . $new_user->errorMessage());
}
prettyprint($new_user);

/**
 * Try restore user from session.
 */
echo '<br><h3>Restored from session</h3><br>';
// $_SESSION['user'] = 105;  // change to invalid user id in session
$user = Member::fromSession();
if ($user->error()) {
    die("Error when restoring user from session: " . $user->errorMessage());
}
prettyprint($user);


/**
 * Fetch some of the members
 */
echo '<br><h3>Some members</h3><br>';
$limit = 2;
$offset = 1;
$members = Member::fetchMembers($limit, $offset);
prettyprint($members);

/**
 * Fetch all members
 */
echo '<br><h3>All members</h3><br>';
$allMembers = Member::fetchAll();
prettyprint($allMembers);
