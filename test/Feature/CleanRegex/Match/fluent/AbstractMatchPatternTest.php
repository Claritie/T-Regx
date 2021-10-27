<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @coversNothing
 */
class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // when
        $result = pattern("(?<capital>[A-Z])?[a-zA-Z']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Detail $detail) {
                return $detail->textLength() !== 3;
            })
            ->map(function (Detail $detail) {
                return $detail->group('capital');
            })
            ->map(function (Group $detailGroup) {
                if ($detailGroup->matched()) {
                    return "yes: $detailGroup";
                }
                return "no";
            })
            ->values()
            ->all();

        // then
        $this->assertSame(['no', 'yes: H', 'no', 'yes: A'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames_lastGroup()
    {
        // when
        pattern('Foo(?<one>Bar)?(?<two>Bar)?')
            ->match('Foo')
            ->fluent()
            ->first(function (Detail $detail) {
                $this->assertEquals(['one', 'two'], $detail->groupNames());
                $this->assertTrue($detail->hasGroup('one'));
            });
    }

    /**
     * @test
     */
    public function shouldFluent_passUserData()
    {
        // given
        pattern("(Foo|Bar)")
            ->match("Foo, Bar")
            ->fluent()
            ->filter(function (Detail $detail) {
                // when
                $detail->setUserData("$detail" === 'Foo' ? 'hey' : 'hello');

                return true;
            })
            ->forEach(function (Detail $detail) {
                // then
                $this->assertSame($detail->index() === 0 ? 'hey' : 'hello', $detail->getUserData());
            });
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_orThrow()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first match, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->fluent()->findFirst(Functions::fail())->orThrow();
    }

    /**
     * @test
     */
    public function shouldFluent_keys_findFirst_orThrow()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->fluent()->keys()->findFirst(Functions::fail())->orThrow();
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_orThrow_custom()
    {
        try {
            // when
            pattern("Foo")
                ->match("Bar")
                ->fluent()
                ->findFirst(Functions::fail())
                ->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
            $this->assertSame('Bar', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function shouldFluent_filter_findFirst_orThrow_custom()
    {
        try {
            // when
            pattern('Foo')
                ->match('Foo')
                ->fluent()
                ->filter(Functions::constant(false))
                ->findFirst(Functions::fail())
                ->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)', $exception->getMessage());
            $this->assertSame('Foo', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_orElse()
    {
        // when
        pattern('Foo')->match('Bar')->fluent()->findFirst(Functions::fail())->orElse(Functions::argumentless());
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_orValue()
    {
        // when
        $value = pattern('Foo')->match('Bar')->fluent()->findFirst(Functions::fail())->orReturn('value');

        // then
        $this->assertSame('value', $value);
    }

    /**
     * @test
     */
    public function shouldFluent_filter_findFirst_orElse()
    {
        // when
        pattern('Foo')
            ->match('Foo')
            ->fluent()
            ->filter(Functions::constant(false))
            ->findFirst(Functions::fail())
            ->orElse(Functions::argumentless());
    }

    /**
     * @test
     */
    public function shouldFluent_first_throw_InternalRegxException()
    {
        try {
            // when
            pattern("Foo")->match("Foo")->fluent()->first(Functions::throws(new EmptyStreamException()));
        } catch (EmptyStreamException $exception) {
            // then
            $this->assertEmpty($exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_orThrow_InternalRegxException()
    {
        try {
            // when
            pattern("Foo")->match("Foo")->fluent()->findFirst(Functions::throws(new EmptyStreamException()))->orThrow();
        } catch (EmptyStreamException $exception) {
            // then
            $this->assertEmpty($exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function shouldFluent_findFirstCallback_orThrow()
    {
        // when
        $letters = pattern('Foo')->match('Foo')->fluent()->findFirst(Functions::letters())->orThrow();

        // then
        $this->assertSame(['F', 'o', 'o'], $letters);
    }

    /**
     * @test
     */
    public function shouldThrow_filter_all_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Foo'));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (45) given');

        // when
        $pattern->fluent()->filter(Functions::constant(45))->all();
    }

    /**
     * @test
     */
    public function shouldThrow_filter_first_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Foo'));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (45) given');

        // when
        $pattern->fluent()->filter(Functions::constant(45))->first();
    }

    /**
     * @test
     */
    public function shouldThrowForUnparsableEntity()
    {
        // when
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage('Failed to parse value as integer. Expected integer|string, but null given');

        // when
        pattern('\d+')->match('123')
            ->fluent()
            ->map(Functions::constant(null))
            ->asInt()
            ->first();
    }
}
