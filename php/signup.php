<?PHP

declare(strict_types=1);

/*******************************************************************************
 * Projekt, Kurs: DT167G
 * File: signup.php
 * Desc: dedicated page to handle new signups.
 ******************************************************************************/

require_once 'init.php';

$responseText = [];

if (!isset($_POST["su_token"]) || !isset($_POST["su_ts"])) {
    // Cross reference protection not provided
    ///@todo decide action

    $responseText['msg'] = "Required sign up data not provided";

    // handle a new sign up request
} else {
    if (isset($_POST['user_name']) && Token::validateToken("signup", $_POST["su_ts"], $_POST['su_token'])) {

        $userName = escape($_POST['user_name']);
        $inputPassword = trim(escape($_POST['password1']));
        $passwordValidation = trim(escape($_POST['password2']));

        if ($inputPassword != '' && isValidPasswordInput($inputPassword, $passwordValidation)) {

            $member = new Member();
            $member->setUsername($userName);

            if ($member->save($inputPassword)) {
                // on success, should the user be logged in automatically?
                $responseText['msg'] = "You are now signed up and may create posts!";
            } else {
                $responseText['msg'] = $member->errorMessage();
            }
        } else {
            $responseText['msg'] = "The entered passwords does not match!";
        }
    }
}


// Check to compare that both entered passwords string are the same.
function isValidPasswordInput(string $input1, string $input2): bool
{
    return $input1 === $input2;
}
header('Content-Type: application/json');
echo json_encode($responseText);
