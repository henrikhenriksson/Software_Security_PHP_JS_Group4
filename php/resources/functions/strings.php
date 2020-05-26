<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: debug.php
 * Contains  Helper functions for debugging
 ******************************************************************************/

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
