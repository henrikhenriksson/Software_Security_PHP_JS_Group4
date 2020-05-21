<?PHP

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: login.php
 * Desc: Login page for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

require_once 'util.php';

//$login = new Login($_GET['uname'], $_GET['psw']);
$login = new Login($_POST['uname'], $_POST['psw']);

$userId = $login->isValidUserData();

$responseText = [];

if ( 0 < $userId ) {
    session_start();
    // Sessionvariable that is created when a user has successfully logged in, this variable holds the username as its value.
    $_SESSION['username'] = $_GET['uname'];

    // Holds weather member has admin rights.
    $_SESSION['is_admin'] = $login->isAdmin();

    // This array holds the links to be displayed when a user has logged in
    $link_array = $login->getLinkArray();
    // Putting link_array in SESSION to keep menu after page refresh
    $_SESSION['link_array'] = $link_array;
    // Add menu links to the response
    $responseText["links"] = $link_array;
}
// Add boolean indicating if the login was successful
$responseText["isValidLogin"] = 0 < $userId;
$responseText["msg"] = $login->getMessage();

// Send back response
header('Content-Type: application/json');
echo json_encode($responseText);
