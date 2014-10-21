# PolyCast

[![Build Status](https://travis-ci.org/theodorejb/PolyCast.svg?branch=master)](https://travis-ci.org/theodorejb/PolyCast) [![Packagist Version](https://img.shields.io/packagist/v/theodorejb/polycast.svg)](https://packagist.org/packages/theodorejb/polycast) [![License](https://img.shields.io/packagist/l/theodorejb/polycast.svg)](LICENSE.md)

Adds `to_int`, `to_float`, and `to_string` functions for safe, strict casting.

~~The functions return `false` if a value cannot be safely cast.~~
The functions return `false` by default if a value cannot be safely cast.
However, in this branch you can pass a second argument that will be returned 
if the value cannot be safely cast.

This return value can be just a value:

```php

$val = to_int($maybe_ok, 3);

```

If $maybe_ok can't be safely cast, $val will be 3.

It can also be a Callable or an Exception:

```php

$val = to_int($maybe_ok, function ($rejected_value) {
    return "Did NOT like $rejected_value";
});

$val2 = to_int($maybe_ok, new InvalidArgumentException("No. just no."));

```

And the Callable will be called, with the rejected value as the argument,
or the Exception thrown (without modification).  Of course if you wanted
you could pass a function that throws an Exception so that your
Exception message could include the rejected value.



(Amended but) Based on https://github.com/php/php-src/pull/874.
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

Value      | `to_int()` | `to_float()` | `to_string()`
---------- | ---------- | ------------ | -------------
`null`     | `false`    | `false`      | `false`
`true`     | `false`    | `false`      | `false`
`false`    | `false`    | `false`      | `false`
`array`    | `false`    | `false`      | `false`
resource   | `false`    | `false`      | `false`
`stdClass` | `false`    | `false`      | `false`
"10"       | 10         | 10.0         | "10"
"-10"      | -10        | -10.0        | "-10"
10.0       | 10         | 10.0         | "10"
"10.0"     | `false`    | 10.0         | "10.0"
1.5        | `false`    | 1.5          | "1.5"
"1.5"      | `false`    | 1.5          | "1.5"
"31e+7"    | `false`    | 310000000.0  | "31e+7"
"75e-5"    | `false`    | 0.00075      | "75e-5"
`INF`      | `false`    | `INF`        | "INF"
`NAN`      | `false`    | `NAN`        | "NAN"
"   10   " | `false`    | `false`      | "   10   "
"10abc"    | `false`    | `false`      | "10abc"
"abc10"    | `false`    | `false`      | "abc10"

### Support for `__toString()`

```php
class NotStringable {}
class Stringable {
    public function __toString() {
        return "foobar";
    }
}

to_string(new NotStringable()); // false
to_string(new Stringable());    // "foobar"
```

## Author

Theodore Brown  
<http://theodorejb.me>

## License

MIT
