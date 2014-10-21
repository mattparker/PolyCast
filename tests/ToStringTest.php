<?php

class ToStringTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame("foobar", to_string("foobar"));
        $this->assertSame("123", to_string(123));
        $this->assertSame("123.45", to_string(123.45));
        $this->assertSame("INF", to_string(INF));
        $this->assertSame("-INF", to_string(-INF));
        $this->assertSame("NAN", to_string(NAN));
    }

    public function testDisallowedTypes()
    {
        $this->assertFalse(to_string(null));
        $this->assertFalse(to_string(true));
        $this->assertFalse(to_string(false));
        $this->assertFalse(to_string([]));
        $this->assertFalse(to_string(fopen("data:text/html,foobar", "r")));
    }

    public function testObjects()
    {
        $this->assertFalse(to_string(new stdClass()));
        $this->assertFalse(to_string(new NotStringable()));
        $this->assertSame("foobar", to_string(new Stringable()));
    }

    public function testUserReturnValue()
    {
        $this->assertEquals(3, to_string(null, 3));
        $this->assertSame(3, to_string(new NotStringable(), 3));
    }

    public function testUserReturnCallable()
    {
        $this->assertEquals(42, to_string(null, function () {return 6*7;}));
        $this->assertEquals(4, to_string([4], function ($rejected_value) {
            return $rejected_value[0];
        }));
    }

    public function testFailWithException()
    {
        $this->setExpectedException('InvalidArgumentException', 'oops');
        to_string(null, new InvalidArgumentException("oops"));
    }

}

class NotStringable {}

class Stringable
{
    public function __toString()
    {
        return "foobar";
    }
}
