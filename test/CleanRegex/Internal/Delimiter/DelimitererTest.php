<?php
namespace Test\CleanRegex\Internal\Delimiter;

use CleanRegex\Internal\Delimiter\Delimiterer;
use PHPUnit\Framework\TestCase;

class DelimitererTest extends TestCase
{
    public function patternsAndResults()
    {
        return array(
            array('siema', '/siema/'),
            array('sie#ma', '/sie#ma/'),
            array('sie/ma', '#sie/ma#'),
            array('si/e#ma', '%si/e#ma%'),
            array('si/e#m%a', '~si/e#m%a~'),
            array('s~i/e#m%a', '+s~i/e#m%a+'),
            array('s~i/e#++m%a', '!s~i/e#++m%a!'),
        );
    }

    /**
     * @test
     * @dataProvider patternsAndResults
     * @param string $pattern
     * @param string $expectedResult
     */
    public function shouldDelimiterPattern($pattern, $expectedResult)
    {
        // given
        $delimiterer = new Delimiterer();

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($expectedResult, $result);
    }

    public function alreadyDelimitered()
    {
        return array(
            array('/a/'),
            array('#a#'),
            array('%a%'),
            array('~a~'),
            array('+a+'),
            array('!a!'),
        );
    }

    /**
     * @test
     * @dataProvider alreadyDelimitered
     * @param string $pattern
     */
    public function shouldDelimiterAlreadyDelimitered($pattern)
    {
        // given
        $delimiterer = new Delimiterer();

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($pattern, $result);
    }

    /**
     * @test
     */
    public function shouldThrowOnNotEnoughDelimiters()
    {
        // given
        $delimiterer = new Delimiterer();

        // then
        $this->expectException('\CleanRegex\Internal\Delimiter\ExplicitDelimiterRequiredException');

        // when
        $delimiterer->delimiter('s~i/e#++m%a!');
    }

    function expectException($className) {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($className);
        } else {
            parent::expectException($className);
        }
    }
}
