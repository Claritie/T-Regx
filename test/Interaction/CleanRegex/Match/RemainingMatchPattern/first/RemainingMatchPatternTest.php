<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use Test\Utils\Impl\CallbackPredicate;
use Test\Utils\Impl\ThrowApiBase;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern::first
 */
class RemainingMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::notEquals('matching'));

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('nice', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_all()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::notEquals('matching'));

        // when
        $matchPattern->first(function (Detail $detail) {
            // when
            $all = $detail->all();

            // then
            $this->assertSame(['nice', 'matching', 'pattern'], $all);
        });
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_group_all()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::notEquals('matching'));

        // when
        $matchPattern->first(function (Detail $detail) {
            // when
            $all = $detail->group(0)->all();

            // then
            $this->assertSame(['nice', 'matching', 'pattern'], $all);
        });
    }

    /**
     * @test
     */
    public function shouldGetFirst_notFirst()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'foo bar', Functions::notEquals('foo'));

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('bar', $first);
    }

    /**
     * @test
     */
    public function shouldNotGetFirst_notMatched()
    {
        // given
        $matchPattern = $this->matchPattern('Foo', 'Bar', Functions::constant(true));

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern long', Functions::constant(false));

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldNotInvokeFilter()
    {
        // given
        $matchPattern = $this->matchPattern('\w+', 'One, two, three', Functions::collecting($invoked, Functions::constant(true)));

        // when
        $matchPattern->first();

        // then
        $this->assertSame(['One'], $invoked);
    }

    /**
     * @test
     */
    public function shouldGet_group_asInt_first()
    {
        // given
        $matchPattern = $this->matchPattern('(\d+)', '14 15', Functions::notEquals('14'));

        // when
        $result = $matchPattern->group(1)->asInt()->first();

        // then
        $this->assertSame($result, 15);
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        $base = new ApiBase(Definitions::pattern($pattern), new StringSubject($subject), new UserData());
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                $base,
                new CallbackPredicate($predicate)),
            new ThrowApiBase(),
            new LazyMatchAllFactory($base));
    }
}
