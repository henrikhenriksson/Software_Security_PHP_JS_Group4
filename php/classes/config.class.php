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
    private $config;
    private $local_config;

    /**
     * Summary. Default constructor loading settings from config.php.
     */
    public function __construct(string $fname)
    {
        require $fname;
        $this->server_config = $server_config;
        $this->local_config = $local_config;
    }

    /**
     * Summary. Gets parameters used to connect to the database from config file.
     * @return string Parameters used to connect to the database.
     */
    public function getDbDsn(): string
    {
        return $this->makeDsn(
            $this->get('host'),
            $this->get('dbname'),
            $this->get('port')
        );
    }

    public function getLocalDbDsn(): string
    {
        return $this->makeDsn(
            $this->getLocal('host'),
            $this->getLocal('dbname'),
            $this->getLocal('port')
        );
    }

    private function makeDsn(string $host, string $name, string $port): string
    {
        return "pgsql:host={$host};dbname={$name};port={$port}";
    }

    public function get(string $key)
    {
        return $this->server_config[$key];
    }

    public function getLocal(string $key)
    {
        return $this->local_config[$key];
    }

    /**
     * Summary. Checks with config file if debugmode should be used.
     * @return bool True if debug should be used, otherwise false.
     */
    public function useDebugMode(): bool
    {
        return $this->get('debug');
    }
}
