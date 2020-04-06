<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('([A-Z])?[a-z]+'), 'Nice matching pattern');

        // when
        $first = $pattern
            ->filter(function (Match $match) {
                return strlen($match) > 4;
            })
            ->all();

        // then
        $this->assertEquals(['matching', 'pattern'], $first);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('([A-Z])?[a-z]+'), 'Nice matching pattern');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (4) given');

        // when
        $pattern
            ->filter(Functions::constant(4))
            ->all();
    }
}
