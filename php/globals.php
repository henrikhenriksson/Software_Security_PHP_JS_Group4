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
            $cfg->getDBUser(),
            $cfg->getDBPass(),
            [ // Options
                \PDO::ATTR_TIMEOUT => $cfg->get('connect_timeout'),

            ]
        ]);
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
            $cfg->getDBUser(),
            $cfg->getDBPass(),
            [ // Options
                \PDO::ATTR_TIMEOUT => $cfg->getLocal('connect_timeout'),

            ]
        ]);
    }
    return $db;
}
