<?php

class ToIntTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame(0, to_int("0"));
        $this->assertSame(0, to_int(0));
        $this->assertSame(0, to_int(0.0));
        $this->assertSame(10, to_int("10"));
        $this->assertSame(10, to_int("010"));
        $this->assertSame(10, to_int("+10"));
        $this->assertSame(10, to_int(10));
        $this->assertSame(10, to_int(10.0));
        $this->assertSame(310000000, to_int(31e+7));

        $this->assertSame(PHP_INT_MAX, to_int((string) PHP_INT_MAX));
        $this->assertSame(PHP_INT_MAX, to_int(PHP_INT_MAX));
        $this->assertSame(PHP_INT_MIN, to_int((string) PHP_INT_MIN));
        $this->assertSame(PHP_INT_MIN, to_int(PHP_INT_MIN));
    }

    public function disallowedTypes()
    {
        return [
            [null],
            [true],
            [false],
            [new stdClass()],
            [fopen("data:text/html,foobar", "r")],
            [[]],
        ];
    }

    /**
     * @dataProvider disallowedTypes
     * @expectedException InvalidArgumentException
     */
    public function testDisallowedTypes($val)
    {
        to_int($val);
    }

    public function unsafeValues()
    {
        return [
            // non-integral
            [NAN],
            ["10.0"],
            ["75e-5"],
            ["31e+7"],
            ["0x10"],
            [1.5],
            ["1.5"],
            // leading/trailing chars
            ["10abc"],
            ["abc10"],
            ["   100    "],
            ["\n\t\v\r\f   78 \n \t\v\r\f   \n"],
            ["\n\t\v\r78"],
            ["\n\t\v\r\f78"],
            ["78\n\t\v\r\f"],
        ];
    }

    /**
     * @dataProvider unsafeValues
     * @expectedException InvalidArgumentException
     */
    public function testUnsafeValues($val)
    {
        to_int($val);
    }

    public function overflowValues()
    {
        return [
            [INF],
            [-INF],
            [PHP_INT_MAX * 2],
            [PHP_INT_MIN * 2],
            [(string) PHP_INT_MAX . "0"],
            [(string) PHP_INT_MIN . "0"],
        ];
    }

    /**
     * @dataProvider overflowValues
     * @expectedException OverflowException
     */
    public function testOverflowValues($val)
    {
        to_int($val);
    }
}
