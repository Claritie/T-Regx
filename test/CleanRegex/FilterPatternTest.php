<?php
namespace Test\CleanRegex;

use CleanRegex\FilterArrayPattern;
use CleanRegex\Internal\Pattern as InternalPattern;
use PHPUnit\Framework\TestCase;

class FilterPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patternsAndSubjects
     * @param string $pattern
     * @param array $subjects
     * @param array $expected
     */
    public function shouldFilterArray($pattern, array $subjects, array $expected)
    {
        // given
        $filterArrayPattern = new FilterArrayPattern(new InternalPattern($pattern), $subjects);

        // when
        $filtered = $filterArrayPattern->filter();

        // then
        $this->assertEquals($expected, $filtered, 'Failed asserting that filter() returned expected results.');
    }

    public function patternsAndSubjects()
    {
        return array(
            array(
                '/dog/',
                array('dog', 'dogs', 'underdog'),
                array('dog', 'dogs', 'underdog'),
            ),
            array(
                '/^[aoe]$/',
                array('a', 'b', 'o'),
                array('a', 'o')
            ),
            array(
                '/^.$/',
                array('cat', 'dog', 'John Wick'),
                array(),
            ),
        );
    }
}
