<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Validate before casting: strings such as "40a" must never become SQL. */
function cms_paging_integer($value)
{
    if (is_int($value) && $value >= 0) {
        return $value;
    }
    if (is_string($value) && preg_match('/\A[0-9]+\z/', $value)) {
        $digits = ltrim($value, '0');
        $maximum = (string) PHP_INT_MAX;
        if (strlen($digits) < strlen($maximum)
            || (strlen($digits) === strlen($maximum) && strcmp($digits, $maximum) <= 0)) {
            return (int) $digits;
        }
    }
    // Invalid page offsets display the first page instead of an error screen.
    return 0;
}
