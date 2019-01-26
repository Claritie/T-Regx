<?php
namespace Test\Functional\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch_quoted()
    {
        // given
        $pattern = PatternBuilder::prepare(['/a ', ["b \t c"], ' d/x']);

        // when
        $matches = $pattern->matches("ab \t cd");

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldNotMatch_unQuoted()
    {
        // given
        $pattern = PatternBuilder::prepare(['/a ', ["b \t c"], ' d/x']);

        // when
        $matches = $pattern->matches('abcd');

        // then
        $this->assertFalse($matches);
    }
}
