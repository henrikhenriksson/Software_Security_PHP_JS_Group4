<?php

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: config.class.php
 * Desc: Class Config for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
 ******************************************************************************/

/**
 * Summary. Singleton class that acts as an interface to get settings from config file.
 */
class Config
{
    private static $instance = null; ///< Holds the class instance

    private $config; ///< Array containing config settings
    private $admin_link_array; ///< Links displayed for admins
    private $member_link_array; ///< Links displayed for members

    /**
     * Summary. Default constructor loading settings from config.php.
     */
    private function __construct()
    {
        require __DIR__ . "/../config.php";
        $this->config = $config;
        $this->admin_link_array = $admin_link_array;
        $this->member_link_array = $member_link_array;
    }
    /**
     * Summary. Gets an instance of config class.
     * @return Config An instance of config class.
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Config();
        }

        return self::$instance;
    }
    /**
     * Summary. Gets parameters used to connect to the database from config file.
     * @return string Parameters used to connect to the database.
     */
    public function getDbDsn()
    {
        return "host={$this->config['host']} port={$this->config['port']} dbname={$this->config['dbname']} user={$this->config['user']} password={$this->config['password']}";
    }
    /**
     * Summary. Gets the length to use when generating captchas from config file.
     * @return int Length of generated captcha messages.
     */
    public function getCaptchaLength()
    {
        return $this->config['captchaLength'];
    }
    /**
     * Summary. Checks with config file if debugmode should be used.
     * @return bool True if debug should be used, otherwise false.
     */
    public function useDebugMode()
    {
        return $this->config['debug'];
    }
    /**
     * Summary Gets the menu links to be displayed for a logged in admin.
     * @return array Associative array with menu links for admins.
     */
    public function getAdminLinks()
    {
        return $this->admin_link_array;
    }
    /**
     * Summary Gets the menu links to be displayed for a logged in member.
     * @return array Associative array with menu links for members.
     */
    public function getMemberLinks()
    {
        return $this->member_link_array;
    }
}
