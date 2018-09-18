<?php
namespace Test\Unit\CleanRegex\Match\MatchPattern\groups\all;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'Nice Matching Pattern');

        // when
        $twoGroups = $pattern->group('two')->all();
        $restGroups = $pattern->group('rest')->all();

        // then
        $this->assertEquals(['Ni', 'Ma', 'Pa'], $twoGroups);
        $this->assertEquals(['ce', 'tching', 'ttern'], $restGroups);
    }

    /**
     * @test
     */
    public function shouldReturnUnmatchedGroups()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<hour>\d\d)?:(?<minute>\d\d)?'), 'First->11:__   Second->__:12   Third->13:32');

        // when
        $hours = $pattern->group('hour')->all();
        $minutes = $pattern->group('minute')->all();

        // then
        $this->assertEquals(['11', null, '13'], $hours);
        $this->assertEquals([null, '12', '32'], $minutes);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'NOT MATCHING');

        // when
        $groups = $pattern->group('two')->all();

        // then
        $this->assertEquals([], $groups);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: missing");

        // when
        $pattern->group('missing')->all();
    }
}
