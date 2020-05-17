<?php

/*******************************************************************************
 * Laboration 4, Kurs: DT161G
 * File: login.class.php
 * Desc: Class Login for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

/**
 * Summary. Represents an attempt to login to the homepage providing a username and a password as parameters.
 */
class Login
{
    private $username; ///< Username from login form.
    private $password; ///< Password from login form.
    private $members; ///< Array of valid users from database.

    //If username exists in $members, that user info will be loaded into $user.
    private $user;

    /**
     * Summary. Initializing constructor.
     * @param string Username given in login form.
     * @param string Password given in login form.
     */
    function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->members = DatabaseHandler::getInstance()->getMembers();
        $this->user = $this->getUser();
    }
    /**
     * Summary. Attempt to a member from $members with given username from login form. If no such user exists null is returned.
     * @return Member Member with the given username in login form.
     */
    private function getUser()
    {
        foreach ($this->members as $member) {
            if ($member->getUsername() == $this->username) {
                return $member;
            }
        }
        return null;
    }
    /**
     * Summary. Checks if the given username is valid.
     * @return bool True if username is valid.
     * False if username is not valid.
     */
    public function isValidUser()
    {
        return $this->user != null;
    }
    /**
     * Summary. Checks if the given password is valid.
     * @return bool True if password is valid.
     * False if password is not valid.
     */
    public function isValidPsw()
    {
        if ($this->isValidUser()) {
            if ($this->user->getPassword() == $this->password) {
                return true;
            }
        }

        return false;
    }
    /**
     * Summary. Checks if the user has admin rights.
     * @return bool True if the user has admin rights.
     * False if the user do not have admin rights.
     */
    public function isAdmin()
    {
        foreach ($this->user->getRoles() as $role) {
            if ($role->getRole() == "admin") {
                return true;
            }
        }

        return false;
    }
    /**
     * Summary. Gets an array of menu links.
     * @return array An associative array containing menu links.
     */
    public function getLinkArray()
    {
        $linkArray = ["Hem" => "index.php"];

        foreach ($this->user->getRoles() as $role) {
            if ($role->getRole() == "admin") {
                $linkArray = array_merge($linkArray, Config::getInstance()->getAdminLinks());
            }

            if ($role->getRole() == "member") {
                $linkArray = array_merge($linkArray, Config::getInstance()->getMemberLinks());
            }
        }

        return $linkArray;
    }
    /**
     * Summary. Gets a status message for the login attempt.
     * @return string Status message for the login attempt.
     */
    public function getMessage()
    {
        if ($this->isValidPsw()) {
            return "Welcome {$this->username}, you are now logged in!";
        } elseif ($this->isValidUser() && !$this->isValidPsw()) {
            return "Invalid password.";
        } else {
            return "Unauthorized user.";
        }
    }
}
