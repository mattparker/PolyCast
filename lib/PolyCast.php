<?php

// conditionally define PHP_INT_MIN since PHP 5.x doesn't
// include it and it's necessary for validating integers.
if (!defined("PHP_INT_MIN")) {
    define("PHP_INT_MIN", ~PHP_INT_MAX);
}

/**
 * Returns the value as an int
 * @param mixed $val
 * @return int
 * @throws InvalidArgumentException if the value has an invalid type or cannot be safely cast
 * @throws OverflowException if the value is less than PHP_INT_MIN or greater than PHP_INT_MAX
 */
function to_int($val)
{
    $overflowCheck = function ($val) {
        if ($val > PHP_INT_MAX) {
            throw new OverflowException("Value $val exceeds maximum integter size");
        } elseif ($val < PHP_INT_MIN) {
            throw new OverflowException("Value $val is less than minimum integer size");
        }
    };

    $type = gettype($val);

    switch ($type) {
        case "integer":
            return $val;
        case "double":
            if ($val !== (float) (int) $val) {
                $overflowCheck($val); // if value doesn't overflow, then it's non-integral
                throw new InvalidArgumentException("Value $val cannot be safely converted to an integer");
            }

            return (int) $val;
        case "string":
            if (!preg_match("/^[+-]?[0-9]+$/", $val)) {
                throw new InvalidArgumentException("The string $val does not have a valid integer format");
            }

            $overflowCheck((float) $val);
            return (int) $val;
        default:
            throw new InvalidArgumentException("Expected integer, float, or string, given $type");
    }
}

/**
 * Returns the value as a float
 * @param mixed $val
 * @return float
 * @throws InvalidArgumentException if the value cannot be safely cast
 */
function to_float($val)
{
    $type = gettype($val);

    switch ($type) {
        case "double":
            return $val;
        case "integer":
            return (float) $val;
        case "string":
            $intVal = filter_var($val, FILTER_VALIDATE_FLOAT);

            if (($intVal === false) || preg_match("/^\s/", $val) || preg_match("/\s$/", $val)) {
                throw new InvalidArgumentException("The string $val cannot be safely converted to a float");
            }

            return $intVal;
        default:
            throw new InvalidArgumentException("Expected float, integer, or string, given $type");
    }
}

/**
 * Returns the value as a string
 * @param mixed $val
 * @return string
 * @throws InvalidArgumentException if the value cannot be safely cast
 */
function to_string($val)
{
    $type = gettype($val);

    switch ($type) {
        case "string":
            return $val;
        case "integer":
        case "double":
            return (string) $val;
        case "object":
            if (method_exists($val, "__toString")) {
                return $val->__toString();
            } else {
                throw new InvalidArgumentException("Object " . get_class($val) . " cannot be converted to a string without a __toString method");
            }
        default:
            throw new InvalidArgumentException("Expected string, integer, float, or object, given $type");
    }
}
