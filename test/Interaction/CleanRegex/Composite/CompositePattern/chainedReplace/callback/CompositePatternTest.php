<?php
namespace Test\Interaction\TRegx\CleanRegex\Composite\CompositePattern\chainedReplace\callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Composite\CompositePattern;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

class CompositePatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider times
     * @param int $times
     * @param string $expected
     */
    public function test(int $times, string $expected)
    {
        // given
        $pattern = new CompositePattern($this->nthPatterns($times, [
            "/at's ai/",
            "/th__r you're (?<group>bre)(?<unmatched>lol)?/",
            "/nk __ath/",
            "/thi__ing/",
            '/(\s+|\?)/',
        ]));

        // when
        $replaced = $pattern
            ->chainedReplace("Do you think that's air you're breathing now?")
            ->callback(Functions::constant('__'));

        // then
        $this->assertSame($expected, $replaced);
    }

    /**
     * @test
     */
    public function shouldGetLimit()
    {
        // given
        $pattern = new CompositePattern([InternalPattern::pcre('/Foo/')]);

        // when
        $pattern->chainedReplace("Foo")->callback(Functions::peek(Functions::assertSame(-1, Functions::property('limit')), Functions::constant('_')));
    }

    public function times(): array
    {
        return [
            [0, "Do you think that's air you're breathing now?"],
            [1, "Do you think th__r you're breathing now?"],
            [2, 'Do you think __athing now?'],
            [3, 'Do you thi__ing now?'],
            [4, 'Do you __ now?'],
            [5, 'Do__you______now__'],
            [6, 'Do__you______now__'],
            [7, 'Do__you______now__'],
        ];
    }

    /**
     * @test
     */
    public function shouldInvokeCallbackForOnePattern()
    {
        // given
        $pattern = new CompositePattern([
            InternalPattern::pcre('/[a-z]/'),
            InternalPattern::pcre('/[1-9]/')
        ]);
        $chainedReplace = $pattern->chainedReplace('a 1 b 2 c 3');
        $matches = [];
        $subjects = [];
        $modified = [];

        // when
        $result = $chainedReplace->callback(function (ReplaceDetail $detail) use (&$matches, &$subjects, &$modified) {
            $matches[] = $detail->text();
            $subjects[] = $detail->subject();
            $modified[] = $detail->modifiedSubject();
            return '_';
        });

        // then
        $first = 'a 1 b 2 c 3';
        $second = '_ 1 _ 2 _ 3';
        $expectedModified = [
            'a 1 b 2 c 3',
            '_ 1 b 2 c 3',
            '_ 1 _ 2 c 3',
            '_ 1 _ 2 _ 3',
            '_ _ _ 2 _ 3',
            '_ _ _ _ _ 3',
        ];
        $expectedResult = '_ _ _ _ _ _';

        $this->assertSame(['a', 'b', 'c', '1', '2', '3'], $matches);
        $this->assertSame([$first, $first, $first, $second, $second, $second], $subjects);
        $this->assertSame($expectedModified, $modified);
        $this->assertSame($expectedResult, $result);
    }

    private function nthPatterns(int $times, array $patterns): array
    {
        return \array_map([InternalPattern::class, 'pcre'], \array_slice($patterns, 0, $times));
    }
}
