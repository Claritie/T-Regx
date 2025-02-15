<?php
namespace Test\Feature\CleanRegex\_entry_points;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

class PcrePatternTest extends TestCase
{
    use AssertsPattern, ExactExceptionMessage;

    /**
     * @test
     * @dataProvider malformedPregPatterns
     * @param string $pattern
     * @param string $expectedMessage
     */
    public function shouldThrowForAlphanumericFirstCharacter(string $pattern, string $expectedMessage)
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage($expectedMessage);
        // given
        PcrePattern::builder($pattern)->build();
    }

    public function malformedPregPatterns(): array
    {
        return [
            ['', 'PCRE-compatible template is malformed, pattern is empty'],
            ['&foo', "PCRE-compatible template is malformed, unclosed pattern '&'"],
            ['#foo/', "PCRE-compatible template is malformed, unclosed pattern '#'"],
            ['/foo', "PCRE-compatible template is malformed, unclosed pattern '/'"],
            ['ooo', "PCRE-compatible template is malformed, alphanumeric delimiter 'o'"],
            ['4oo', "PCRE-compatible template is malformed, alphanumeric delimiter '4'"],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forUndelimitedPcrePattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Delimiter must not be alphanumeric or backslash');
        // when
        PcrePattern::of('foo')->test('bar');
    }
}
