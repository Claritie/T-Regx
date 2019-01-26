<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\PCRE_EXTENDED\inject\whitespace;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIgnoreWhiteSpace_inPattern()
    {
        // given
        $pattern = PatternBuilder::inject('/a b c d e/x', []);

        // when
        $matches = $pattern->matches('abcde');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldNotIgnoreWhiteSpace_inUserInput_fails()
    {
        // given
        $pattern = PatternBuilder::inject('/a b`input`d e/x', [
            'input' => '  c  '
        ]);

        // when
        $matches = $pattern->matches('ab c de');

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldNotIgnoreWhiteSpace_inUserInput_passes()
    {
        // given
        $pattern = PatternBuilder::inject('/a b`input`d e/x', [
            'input' => '  c  '
        ]);

        // when
        $matches = $pattern->matches('ab  c  de');

        // then
        $this->assertTrue($matches);
    }
}
