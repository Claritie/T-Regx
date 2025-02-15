<?php
namespace Test\Functional\TRegx\SafeRegex\_callback_flags;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\TestCaseConditional;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    use TestCaseConditional;

    /**
     * @test
     */
    public function shouldPassFlagToPregReplaceCallback()
    {
        if (\PHP_VERSION_ID < 70400) {
            $this->markTestUnnecessary("PHP with PCRE2 is not prone to constant override");
        }

        // given
        $collected = null;

        // when
        preg::replace_callback('/Foo/', DetailFunctions::out($collected), 'Foo', -1, $count, \PREG_OFFSET_CAPTURE);

        // then
        $this->assertSame([['Foo', 0]], $collected);
    }

    /**
     * @test
     */
    public function shouldCallPregReplaceCallbackWithoutFlags()
    {
        // given
        $collected = null;

        // when
        preg::replace_callback('/Foo/', DetailFunctions::out($collected), 'Foo', -1, $count, 0);

        // then
        $this->assertSame(['Foo'], $collected);
    }
}
