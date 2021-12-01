<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGet_test()
    {
        $this->markTestIncomplete();
        // when
        $matched = pattern('[A-Z][a-z]+')->match('First, Second, Third')->stream()->filter(DetailFunctions::equals('Third'))->test();

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGet_test_filteredOut()
    {
        $this->markTestIncomplete();
        // when
        $matched = pattern('[A-Z][a-z]+')->match('First, Second')->remaining(Functions::constant(false))->test();

        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldGet_test_notMatched()
    {
        $this->markTestIncomplete();
        // when
        $matched = pattern('Foo')->match('Bar')->remaining(Functions::fail())->test();

        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // given
        $pattern = pattern('\w+')->match('One, two, three')->stream()->filter(DetailFunctions::notEquals('two'));
        $this->assertIsNotArray($pattern);

        // when
        $size = count($pattern);

        // then
        $this->assertSame(2, $size);
    }

    /**
     * @test
     */
    public function shouldForEachGroup_acceptKey()
    {
        // given
        $arguments = [];

        // when
        pattern('(\w+)')->match('Foo, Bar, Cat, Dur')
            ->remaining(Functions::oneOf(['Foo', 'Cat', 'Dur']))
            ->group(1)
            ->forEach(function (string $argument, int $index) use (&$arguments) {
                $arguments[$argument] = $index;
            });

        // then
        $this->assertSame(['Foo' => 0, 'Cat' => 1, 'Dur' => 2], $arguments);
    }
}
