<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\asInt\reduce;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use function pattern;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldSum()
    {
        // when
        $reduced = pattern('\d+')->match('14, 15, 16')
            ->asInt()
            ->reduce(Functions::sum(), 2);

        // then
        $this->assertSame(47, $reduced);
    }

    /**
     * @test
     */
    public function shouldReduce_returnAccumulatorForEmptyMatch()
    {
        // when
        $result = pattern('Foo')->match('Bar')
            ->asInt()
            ->reduce(Functions::fail(), 12);

        // then
        $this->assertSame(12, $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passFromCallbackForSingleMatch()
    {
        // when
        $result = pattern('123')->match('123')
            ->asInt()
            ->reduce(Functions::constant('Lorem'), 'Accumulator');

        // then
        $this->assertSame('Lorem', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passAccumulatorAsFirstArgument()
    {
        // when
        $result = pattern('45')->match('45')
            ->asInt()
            ->reduce(Functions::identity(), 'Accumulator');

        // then
        $this->assertSame('Accumulator', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentString()
    {
        // given
        $secondString = function ($acc, string $string) {
            return $string;
        };

        // when
        $result = pattern('45')->match('45')
            ->asInt()
            ->reduce($secondString, 'Accumulator');

        // then
        $this->assertSame('45', $result);
    }

    /**
     * @test
     */
    public function shouldReduce_passValueSecondAsArgumentDetail()
    {
        // given
        $detailText = function ($acc, int $value) {
            return $value;
        };

        // when
        $result = pattern('58')->match('58')
            ->asInt()
            ->reduce($detailText, 'Accumulator');

        // then
        $this->assertSame(58, $result);
    }

    /**
     * @test
     */
    public function shouldReduce_throwForTooManyArguments()
    {
        // given
        $tooManyArguments = function ($one, $two, $three) {
            $this->fail();
        };

        // then
        $this->expectException(\ArgumentCountError::class);

        // when
        pattern('1')->match('1')
            ->asInt()
            ->reduce($tooManyArguments, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduce_throwForInvalidArgument()
    {
        // given
        $tooManyArguments = function ($one, array $invalid) {
            $this->fail();
        };

        // then
        $this->expectException(\TypeError::class);

        // when
        pattern('1')->match('1')
            ->asInt()
            ->reduce($tooManyArguments, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduce_throwForNonCallback()
    {
        // then
        $this->expectException(\TypeError::class);

        // when
        pattern('Foo')->match('Foo')
            ->asInt()
            ->reduce(null, 'Accumulator');
    }

    /**
     * @test
     */
    public function shouldReduceSecond()
    {
        // when
        $reduced = pattern('\d+')->match('123, 345')
            ->asInt()
            ->reduce(Functions::second(), 'Accumulator');

        // then
        $this->assertSame(345, $reduced);
    }

    /**
     * @test
     */
    public function shouldPassAccumulator()
    {
        // when
        $reduced = pattern('\d+')->match('15,16,17')
            ->asInt()
            ->reduce(Functions::sum(), 0);

        // then
        $this->assertSame(48, $reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // when
        $reduced = pattern('\d+')->match('13,14')
            ->asInt()
            ->reduce(Functions::constant(null), 0);

        // then
        $this->assertNull($reduced);
    }

    /**
     * @test
     */
    public function shouldReturnNullAccumulatorForUnmatched()
    {
        // when
        $reduced = pattern('Foo')->match('Bar')
            ->asInt()
            ->reduce(Functions::fail(), null);

        // then
        $this->assertNull($reduced);
    }
}
