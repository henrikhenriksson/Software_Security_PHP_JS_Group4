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
    // @TODO register invalid request once that class has been merged.
    ajax_respond([
        'msg' => 'An error occured. Your data could not be validated.'
    ]);
}
$member = new Member();
$member->setUsername($_POST['user_name']);

// check if member is already logged in.
if ($member->loggedIn()) {
    ajax_respond([
        'msg' => 'You are already logged in and cannot create a new account!'
    ]);
}

// validate password input:
if (!isValidPasswordInput($_POST['password1'], $_POST['password2'])) {
    ajax_respond([
        'msg' => 'The passwords you entered does not match!'
    ]);
}

if (!validateCaptcha($_POST['captcha'])) {
    ajax_respond([
        'msg' => 'Error passing captcha challenge.'
    ]);
}

// check if save was successfull
if (!$member->save($_POST['password1'])) {
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

// @Todo: Move this to a function file under /functions to increase scalability.
function validateCaptcha($captcha)
{
    $secret = '6Lfpr_0UAAAAANaYPlORYVfO-fXkBheXdc2VcMNL';
    $curlx = curl_init();
    curl_setopt($curlx, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($curlx, CURLOPT_HEADER, 0);
    curl_setopt($curlx, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlx, CURLOPT_POST, 1);

    $post_data = [
        'secret' => $secret,
        'response' => $captcha
    ];

    curl_setopt($curlx, CURLOPT_POSTFIELDS, $post_data);

    $resp = json_decode(curl_exec($curlx));

    curl_close($curlx);

    if (!$resp->success) {
        return false;
    }
    return true;
}
