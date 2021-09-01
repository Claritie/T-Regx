<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Orthography;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography
 */
class PcreOrthographyTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $pcre = new PcreOrthography('/foo/x');

        // when
        $delimiter = $pcre->delimiter();
        $pattern = $pcre->pattern();
        $flags = $pcre->flags();
        $undeveloped = $pcre->undevelopedInput();

        // then
        $this->assertEquals(new Delimiter('/'), $delimiter);
        $this->assertSame('foo', $pattern);
        $this->assertEquals(new Flags('x'), $flags);
        $this->assertSame('/foo/x', $undeveloped);
    }

    /**
     * @test
     */
    public function shouldGetDelimiter()
    {
        // given
        $pcre = new PcreOrthography('#foo#');

        // when
        $delimiter = $pcre->delimiter();

        // then
        $this->assertEquals(new Delimiter('#'), $delimiter);
    }

    /**
     * @test
     */
    public function shouldGetEmptyFlags()
    {
        // given
        $pcre = new PcreOrthography('#foo#');

        // when
        $flags = $pcre->flags();

        // then
        $this->assertEquals(new Flags(''), $flags);
    }

    /**
     * @test
     */
    public function shouldThrowForMissingDelimiter()
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage("PCRE-compatible template is malformed, unclosed pattern '#'");

        // given
        new PcreOrthography('#foo');
    }
}
