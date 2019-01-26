<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\PCRE_EXTENDED\inject\comment;

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
        $pattern = PatternBuilder::inject("/a b # ignored " . PHP_EOL . " c/x", []);

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
        $pattern = PatternBuilder::inject("/a b # ignored " . PHP_EOL . " @input c/x", [
            'input' => "# ignored " . PHP_EOL
        ]);

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
        $pattern = PatternBuilder::inject("/a b # ignored " . PHP_EOL . " @input c/x", [
            'input' => "# ignored2 " . PHP_EOL
        ]);

        // when
        $matches = $pattern->matches("ab# ignored2 " . PHP_EOL . "c");

        // then
        $this->assertTrue($matches);
    }
}
