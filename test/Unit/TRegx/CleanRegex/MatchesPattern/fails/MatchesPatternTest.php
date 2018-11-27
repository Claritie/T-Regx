<?php
namespace Test\Unit\TRegx\CleanRegex\MatchesPattern\fails;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\MatchesPattern;

class MatchesPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNot_fail()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/[a-z]/'), new SubjectableImpl('matching'));

        // when
        $result = $pattern->fails();

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function should_fail()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^[a-z]+$/'), new SubjectableImpl('not matching'));

        // when
        $result = $pattern->fails();

        // then
        $this->assertTrue($result);
    }
}
