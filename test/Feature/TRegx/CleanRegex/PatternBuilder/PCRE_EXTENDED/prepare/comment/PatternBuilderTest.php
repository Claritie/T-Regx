<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\PCRE_EXTENDED\prepare\comment;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIgnoreComment_inPattern()
    {
        // given
        $pattern = PatternBuilder::prepare(["/a b # ignored " . PHP_EOL . " c/x"]);

        // when
        $matches = $pattern->matches('abc');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldNotIgnoreComment_inUserInput_fails()
    {
        // given
        $pattern = PatternBuilder::prepare(["/a b # ignored " . PHP_EOL . " ", ["# ignored " . PHP_EOL], " c/x"]);

        // when
        $matches = $pattern->matches('abc');

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldNotIgnoreComment_inUserInput_passes()
    {
        // given
        $pattern = PatternBuilder::prepare(["/a b # ignored " . PHP_EOL . " ", ["# ignored2 " . PHP_EOL], " c/x"]);

        // when
        $matches = $pattern->matches("ab# ignored2 " . PHP_EOL . "c");

        // then
        $this->assertTrue($matches);
    }
}
