<?php

/*******************************************************************************
 * Project Group 4 DT167G
 * File: config.php
 * Holds global config settings for the project.
 ******************************************************************************/

$server_config = [

    /***** DATABASE CONNECTION *****/
    'host' => '172.17.0.1',    // Database host
    'port' => '5432',
    'dbname' => 'grupp4',
    'user' => 'grupp4',
    'password' => 'test',
    'connect_timeout' => 5,

    /***** UTILITY ******/
    'debug' => true
];

$local_config = [

    /***** DATABASE CONNECTION *****/
    'host' => '127.0.0.1',    // Database host
    'port' => '15432',
    'dbname' => 'grupp4',
    'user' => 'grupp4',
    'password' => 'test',
    'connect_timeout' => 5,

    /***** UTILITY ******/
    'debug' => true
];
