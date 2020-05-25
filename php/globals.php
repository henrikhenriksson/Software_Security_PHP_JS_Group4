<?php
/**
 * Provides access methods for global, single instances.
 */

use \ParagonIE\EasyDB\EasyDB;
use \ParagonIE\EasyDB\Factory as DBFactory;

/**
 * Returns a global single instance of Config.
 */
function getConfig(): Config
{
    static $cfg = null;
    if (!$cfg) {
        $cfg = new Config(__DIR__.'/config.php');
    }
    return $cfg;
}

function getEasyDB(): EasyDB
{
    static $db;
    $cfg = getConfig();
    if ($db == null) {
        $db = DBFactory::fromArray([
            $cfg->getDbDsn(),
            $cfg->get('user'),
            $cfg->get('password'),
            [ // Options
                \PDO::ATTR_TIMEOUT => $cfg->get('connect_timeout'),

            ]
        ]);
        $schema = $cfg->get('schema');
        $db->run("SET search_path TO {$schema};");
        $db->run("SET application_name TO 'Webserver';");
    }
    return $db;
}

function getLocalEasyDB(): EasyDB
{
    static $db;
    $cfg = getConfig();
    if ($db == null) {
        $db = DBFactory::fromArray([
            $cfg->getLocalDbDsn(),
            $cfg->getLocal('user'),
            $cfg->getLocal('password'),
            [ // Options
                \PDO::ATTR_TIMEOUT => $cfg->getLocal('connect_timeout'),

            ]
        ]);
        $schema = $cfg->getLocal('schema');
        $db->run("SET search_path TO {$schema};");
        $db->run("SET application_name TO 'Unit tests';");
        echo "Created new database instance\n";
    }
    return $db;
}
