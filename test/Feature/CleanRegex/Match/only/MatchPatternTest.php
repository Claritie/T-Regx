<?php
namespace Test\Feature\TRegx\CleanRegex\Match\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Definitions;
use Test\Utils\PhpunitPolyfill;
use Test\Utils\PhpVersionDependent;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::only
 */
class MatchPatternTest extends TestCase
{
    use PhpunitPolyfill;

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = Pattern::of('\w+')->match('Nice matching pattern');
        // when
        $only = $pattern->only(2);
        // then
        $this->assertSame(['Nice', 'matching'], $only);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = Pattern::of('([A-Z])?[a-z]+')->match('NOT MATCHING');
        // when
        $only = $pattern->only(2);
        // then
        $this->assertEmpty($only, 'Failed asserting that only() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches_onlyOne()
    {
        // given
        $pattern = Pattern::of('Foo')->match('Bar');
        // when
        $only = $pattern->only(1);
        // then
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeLimit()
    {
        // given
        $pattern = Pattern::of('Foo')->match('Bar');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');
        // when
        $pattern->only(-2);
    }

    /**
     * @test
     */
    public function shouldGetOne_withPregMatch()
    {
        // given
        $pattern = Pattern::of('\w+')->match('Nice matching pattern');
        // when
        $only = $pattern->only(1);
        // then
        $this->assertSame(['Nice'], $only);
    }

    /**
     * @test
     */
    public function shouldGetNone()
    {
        // given
        $pattern = Pattern::of('Foo')->match('Bar');
        // when
        $only = $pattern->only(0);
        // then
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldValidatePattern_onOnly0()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('invalid)'), new ThrowSubject());
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessageMatches(PhpVersionDependent::getUnmatchedParenthesisMessage(7));
        // when
        $pattern->only(0);
    }
}
