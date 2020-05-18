<?php

/*******************************************************************************
 * Laboration 4, Kurs: DT161G
 * File: role.class.php
 * Desc: Class Role for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

/**
 * Summary. Represents a Role in the database table Role.
 */
class Role
{
    private $id; ///< Role id
    private $role; ///< Role role
    private $roletext; ///< Role roletext

    /**
     * Initializing constructor.
     * @param string $id Role id.
     * @param string $role Role username.
     * @param string $roletext Role password.
     */
    public function __construct($id, $role, $roletext)
    {
        $this->id = $id;
        $this->role = $role;
        $this->roletext = $roletext;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get the value of roletext
     */
    public function getRoletext()
    {
        return $this->roletext;
    }
}
