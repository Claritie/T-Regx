<?php
namespace Test\CleanRegex;

use CleanRegex\CountPattern;
use CleanRegex\Internal\Pattern as InternalPattern;
use PHPUnit\Framework\TestCase;

class CountPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patternsAndSubjects
     * @param $pattern
     * @param $subject
     * @param $expectedCount
     */
    public function shouldCountMatches($pattern, $subject,$expectedCount)
    {
        // given
        $countPattern = new CountPattern(new InternalPattern($pattern), $subject);

        // when
        $count = $countPattern->count();

        // then
        $this->assertEquals($expectedCount, $count, "Failed asserting that count() returned $expectedCount.");
    }

    public function patternsAndSubjects()
    {
        return array(
            array('/dog/', 'cat', 0),
            array('/[aoe]/', 'match vowels', 3),
            array('/car(pet)?/', 'car carpet', 2),
            array('/car(p(e(t)))?/', 'car carpet car carpet', 4),
        );
    }
}
