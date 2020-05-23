<?php
/**
 * Provides access methods for global, single instances.
 */


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

/**
 * Returns a global single instance of DB.
 */
function getDBInstance(): DB
{
    static $db = null;
    if (!$db) {
        $cfg = getConfig();
        $db = new DB($cfg->getDbDsn());
    }
    return $db;
}
