<?PHP

/**
 * This file contains the basic PHP functions for the project
 */
function validate_alpha_underscore_hyphen($str) {
    return preg_match('/^[a-zA-Z0-9_-]+$/', $str);
}
