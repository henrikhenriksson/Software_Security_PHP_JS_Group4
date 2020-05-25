<?php

/*******************************************************************************
 * laboration 4, Kurs: DT161G
 * File: config.php
 * Desc: Config file for laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
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
