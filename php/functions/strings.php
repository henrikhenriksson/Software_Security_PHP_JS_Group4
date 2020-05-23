<?php
/**
 * Common string functions
 */

/**
 * Escape a string ta make it XSS-safe when used in html.
 */
function escape(string $unsafe): string
{
    return htmlspecialchars($unsafe, ENT_QUOTES);
}
