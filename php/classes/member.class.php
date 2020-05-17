<?php

/*******************************************************************************
 * Laboration 4, Kurs: DT161G
 * File: member.class.php
 * Desc: Class Member for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

/**
 * Summary. Represents a Member in the database table Member.
 */
class Member
{
    private $id; ///< Member id
    private $username; ///< Member username
    private $password; ///< Member password
    private $roles; ///< Member roles

    /**
     * Summary. Initializing constructor.
     * @param string $id Member id.
     * @param string $username Member username.
     * @param string $password Member password.
     */
    public function __construct($id, $username, $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->roles = [];
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}
