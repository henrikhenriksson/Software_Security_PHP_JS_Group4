<?php
/**
 * Helper functions for debugging
 */


/**
 * Wrapper function  that preserves output of var_dump.
 *
 * Displays detailed information about a variable including types
 */
function dump($something)
{
    echo '<pre>' , var_dump($something) , '</pre>';
}


/**
 * Wrapper function  that preserves output of print_r.
 *
 * Displays variables in a human readable way. Objects and arrays
 * will be displayed using keys and elements.
 */
function prettyprint($something)
{
    echo '<pre>' , print_r($something) , '</pre>';
}
