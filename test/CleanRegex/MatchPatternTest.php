<?php
namespace CleanRegex;

use CleanRegex\Match\Match;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllMatches()
    {
        // when
        $matches = pattern('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->all();

        // then
        $this->assertEquals(array('Foo Bar', 'Foo Bar', 'Foo Bar'), $matches);
    }

    /**
     * @test
     */
    public function shouldMatchAllForFirst()
    {
        // given
        $result = null;

        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
            ->match('Foo, Leszek Ziom, Dupa')
            ->first(function (Match $match) use (&$result) {
                $result = $match->all();
            });

        // then
        $this->assertEquals(array('Foo', 'Leszek', 'Ziom', 'Dupa'), $result);
    }

    /**
     * @test
     */
    public function shouldThrowOnMissingGroup()
    {
        // then
        $this->expectException('\CleanRegex\Exception\CleanRegex\NonexistentGroupException');

        // when
        pattern('(?<one>hello)')
            ->match('hello')
            ->first(function (Match $match) {
                $match->group('two');
            });
    }

    /**
     * @test
     */
    public function shouldNotCallIterateOnUnmatchedPattern()
    {
        // given
        pattern('dont match me')
            ->match('word')
            ->iterate(function () {

                // then
                $this->assertTrue(false, "This shouldn't be invoked");
            });

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotCallFirstOnUnmatchedPattern()
    {
        // given
        pattern('dont match me')
            ->match('word')
            ->first(function () {

                // then
                $this->assertTrue(false, "This shouldn't be invoked");
            });

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $groupNames = null;

        // when
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) use (&$groupNames) {

                // when
                $groupNames = $match->groupNames();
            });

        // then
        $this->assertEquals(array('one', 'two'), $groupNames);
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Group index can only be an integer or string');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {

                // when
                $match->group(true);
            });
    }

    function expectException($className)
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($className);
        } else {
            parent::expectException($className);
        }
    }

    function expectExceptionMessage($message)
    {
        // ignore
    }
}
