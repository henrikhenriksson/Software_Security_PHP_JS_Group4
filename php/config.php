<?PHP

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: config.php
 * Desc: Config file for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

$config = [

    /***** DATABASE CONNECTION *****/
    #MIUN HOST : studentpsql.miun.se
    #LOCALHOST : 127.0.0.1
    'host' => '172.17.0.1',    // Database host
    'port' => '5432',                   // Database port
    'dbname' => 'grupp4',             // Database name
    'user' => 'grupp4',               // Database user
    'password' => 'test',          // Database password

    /***** GUESTBOOK *****/
    'captchaLength' => 5,

    /***** UTILITY ******/
    'debug' => true
];

// This array holds the links to be displayed when a member has logged in
$member_link_array = [
    "Gästbok" => "guestbook.php",
    "Meddlemssida" => "members.php"
];

// This array holds the links to be displayed when a admin has logged in
$admin_link_array = [
    "Adminsida" => "admin.php"
];
