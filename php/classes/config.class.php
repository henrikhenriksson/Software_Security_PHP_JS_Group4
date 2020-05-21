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
    private $config; ///< Array containing config settings

    /**
     * Summary. Default constructor loading settings from config.php.
     */
    public function __construct(string $fname)
    {
        require $fname;
        $this->config = $config;
        $this->local_config = $local_config;
    }

    /**
     * Summary. Gets parameters used to connect to the database from config file.
     * @return string Parameters used to connect to the database.
     */
    public function getDbDsn()
    {
        return "host={$this->config['host']} port={$this->config['port']} "
            . "dbname={$this->config['dbname']} user={$this->config['user']} "
            . "password={$this->config['password']} "
            . "connect_timeout={$this->config['connect_timeout']}";
    }

    /**
     * Summary. Gets parameters used to connect to the database from config file.
     * @return string Parameters used to connect to the database.
     */
    public function getLocalDbDsn()
    {
        return "host={$this->local_config['host']} port={$this->local_config['port']} "
            . "dbname={$this->local_config['dbname']} "
            . "user={$this->local_config['user']} "
            . "password={$this->local_config['password']} "
            . "connect_timeout={$this->local_config['connect_timeout']}";
    }

    /**
     * Summary. Checks with config file if debugmode should be used.
     * @return bool True if debug should be used, otherwise false.
     */
    public function useDebugMode()
    {
        return $this->config['debug'];
    }
}
