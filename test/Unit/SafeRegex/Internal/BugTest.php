<?php
namespace Test\Unit\TRegx\SafeRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Internal\Bug;

/**
 * @covers \TRegx\SafeRegex\Internal\Bug
 */
class BugTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFixString()
    {
        // when
        $result = Bug::fix("foo\r\t\f\v");

        // then
        $this->assertSame('foo', $result);
    }

    /**
     * @test
     */
    public function shouldFixArray()
    {
        // when
        $result = Bug::fix(["foo\r\t\f\v", "bar\r\t\f\v"]);

        // then
        $this->assertSame(['foo', 'bar'], $result);
    }

    /**
     * @test
     */
    public function shouldIgnoreOtherTypes()
    {
        // when
        $result = Bug::fix(12);

        // then
        $this->assertSame(12, $result);
    }
}
