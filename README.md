# PolyCast

[![Build Status](https://travis-ci.org/theodorejb/PolyCast.svg?branch=master)](https://travis-ci.org/theodorejb/PolyCast) [![Packagist Version](https://img.shields.io/packagist/v/theodorejb/polycast.svg)](https://packagist.org/packages/theodorejb/polycast) [![License](https://img.shields.io/packagist/l/theodorejb/polycast.svg)](LICENSE.md)

Adds `to_int`, `to_float`, and `to_string` functions for safe, strict casting.
The functions throw an exception if a value cannot be safely cast.

Based on https://github.com/php/php-src/pull/874.
An RFC proposing inclusion in PHP 7 was opened for discussion on 2014-10-20:
https://wiki.php.net/rfc/safe_cast.

## Installation

To install via [Composer](https://getcomposer.org/),
add the following to the composer.json file in your project root:

```json
{
    "require": {
        "theodorejb/polycast": "~0.4"
    }
}
```

Then run `composer install` and require `vendor/autoload.php`
in your application's bootstrap file.

## Examples

Value      | `to_int()`                 | `to_float()`                | `to_string()`
---------- | -------------------------- | --------------------------- | --------------------------
`null`     | `InvalidArgumentException` | `InvalidArgumentException`  | `InvalidArgumentException`
`true`     | `InvalidArgumentException` | `InvalidArgumentException`  | `InvalidArgumentException`
`false`    | `InvalidArgumentException` | `InvalidArgumentException`  | `InvalidArgumentException`
`array`    | `InvalidArgumentException` | `InvalidArgumentException`  | `InvalidArgumentException`
resource   | `InvalidArgumentException` | `InvalidArgumentException`  | `InvalidArgumentException`
`stdClass` | `InvalidArgumentException` | `InvalidArgumentException`  | `InvalidArgumentException`
"10"       | 10                         | 10.0                        | "10"
"-10"      | -10                        | -10.0                       | "-10"
10.0       | 10                         | 10.0                        | "10"
"10.0"     | `InvalidArgumentException` | 10.0                        | "10.0"
1.5        | `InvalidArgumentException` | 1.5                         | "1.5"
"1.5"      | `InvalidArgumentException` | 1.5                         | "1.5"
"31e+7"    | `InvalidArgumentException` | 310000000.0                 | "31e+7"
"75e-5"    | `InvalidArgumentException` | 0.00075                     | "75e-5"
`INF`      | `OverflowException`        | `INF`                       | "INF"
`NAN`      | `InvalidArgumentException` | `NAN`                       | "NAN"
"   10   " | `InvalidArgumentException` | `InvalidArgumentException`  | "   10   "
"10abc"    | `InvalidArgumentException` | `InvalidArgumentException`  | "10abc"
"abc10"    | `InvalidArgumentException` | `InvalidArgumentException`  | "abc10"

### Support for `__toString()`

```php
class NotStringable {}
class Stringable {
    public function __toString() {
        return "foobar";
    }
}

to_string(new NotStringable()); // InvalidArgumentException
to_string(new Stringable());    // "foobar"
```

## Author

Theodore Brown  
<http://theodorejb.me>

## License

MIT
