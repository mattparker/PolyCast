<?php

// conditionally define PHP_INT_MIN since PHP 5.x doesn't
// include it and it's necessary for validating integers.
if (!defined("PHP_INT_MIN")) {
    define("PHP_INT_MIN", ~PHP_INT_MAX);
}

/**
 * Returns the value as an int, or false if it cannot be safely cast
 * @param mixed $val
 * @return int
 */
function to_int($val)
{
    switch (gettype($val)) {
        case "integer":
            return $val;
        case "double":
            return ($val === (float) (int) $val) ? (int) $val : false;
        case "string":
            if (!preg_match("/^[+-]?[0-9]+$/", $val)) {
                return false; // reject leading/trailing whitespace
            }

            if ((float) $val > PHP_INT_MAX || (float) $val < PHP_INT_MIN) {
                return false; // reject overflows
            }

            return (int) $val;
        default:
            return false;
    }
}

/**
 * Returns the value as a float, or false if it cannot be safely cast
 * @param mixed $val
 * @return float
 */
function to_float($val)
{
    switch (gettype($val)) {
        case "double":
            return $val;
        case "integer":
            return (float) $val;
        case "string":
            // validation based on http://php.net/manual/en/language.types.float.php
            $lnum    = "[0-9]+";
            $dnum    = "([0-9]*[\.]{$lnum})|({$lnum}[\.][0-9]*)";
            $expDnum = "/^[+-]?(({$lnum}|{$dnum})[eE][+-]?{$lnum})$/";

            if (!(preg_match("/^[+-]?{$lnum}$/", $val) || preg_match("/^[+-]?{$dnum}$/", $val) || preg_match($expDnum, $val))) {
                return false;
            }

            return (float) $val;
        default:
            return false;
    }
}

/**
 * Returns the value as a string, or false if it cannot be safely cast
 * @param mixed $val
 * @return string
 */
function to_string($val)
{
    switch (gettype($val)) {
        case "string":
            return $val;
        case "integer":
        case "double":
            return (string) $val;
        case "object":
            if (method_exists($val, "__toString")) {
                return $val->__toString();
            } else {
                return false;
            }
        default:
            return false;
    }
}
