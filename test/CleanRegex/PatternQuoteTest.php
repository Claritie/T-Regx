<?php
namespace CleanRegex;

use PHPUnit\Framework\TestCase;

class PatternQuoteTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function shouldQuoteWithoutException($invalidPattern)
    {
        // given
        $pattern = new Pattern($invalidPattern);

        // when
        $pattern->quote();

        // then
        $this->assertTrue(true);
    }
}
