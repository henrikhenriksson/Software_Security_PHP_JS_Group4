<?PHP

declare(strict_types=1);

/*******************************************************************************
 * Projekt, Kurs: DT167G
 * File: signup.php
 * Desc: dedicated page to handle new signups.
 ******************************************************************************/

require_once __DIR__ . '/../resources/init.php';

// if no token is present, or if token validation failed.
if (!isset($_POST["su_token"]) || !isset($_POST["su_ts"]) || !Token::validateToken("signup", $_POST["su_ts"], $_POST['su_token'])) {
    // Cross reference protection not provided
    ajax_respond([
        'msg' => 'An error occured. Your data could not be validated.'
    ]);
}
$member = new Member();
$member->setUsername(trim(escape($_POST['user_name'])));

// check if member is already logged in.
if ($member->loggedIn()) {
    ajax_respond([
        'msg' => 'You are already logged in and cannot create a new account!'
    ]);
}

$inputPassword = trim(escape($_POST['password1']));
$passwordValidation = trim(escape($_POST['password2']));

// validate password input:
if (!isValidPasswordInput($inputPassword, $passwordValidation)) {
    ajax_respond([
        'msg' => 'The passwords you entered does not match!'
    ]);
}

// check if save was successfull
if (!$member->save($inputPassword)) {
    ajax_respond([
        'msg' => $member->errorMessage()
    ]);
}

// happy path
ajax_respond([
    'msg' => 'You are now signed up and may post messages!'
]);

// send the reply
function ajax_respond(array $responseText): void
{
    header('Content-Type: application/json');
    echo json_encode($responseText);
    exit;
}

//--- Support function ---\\

// Check to compare that both entered passwords string are the same.
function isValidPasswordInput(string $input1, string $input2): bool
{
    return $input1 === $input2;
}
