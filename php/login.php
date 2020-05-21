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

$responseText = [];

if (!isset($_POST["token"]) || !isset($_POST["TS"])) {
    // Cross reference protection not provided
    ///@todo decide action

    $responseText["msg"] = "Required login data not provided";
} else {
    if (Token::validateToken("login", $_POST["TS"], $_POST["token"])) {

        $member = Member::login($_POST["uname"], $_POST['psw']);

        // Set response data
        $responseText["isValidLogin"] = !$member->error();
        if( is_null( $member->errorMessage() ) )
        {
            $responseText['msg'] = "";
        }else{
            $responseText['msg'] = $member->errorMessage();
        }
    }else{
        ///@todo decide invalid token message
        $responseText['msg'] = "Invalid token";
    }
// Send back response
}
header('Content-Type: application/json');
echo json_encode($responseText);
