<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer\ThrowPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Posix;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixOpen;
use TRegx\CleanRegex\Internal\Prepared\PatternAsEntities;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\PatternAsEntities
 */
class PatternAsEntitiesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCloseGroup()
    {
        // given
        $asEntities = new PatternAsEntities('(foo)', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new GroupOpen(),
            new Literal('f'),
            new Literal('o'),
            new Literal('o'),
            new GroupClose(),
        ];
        $this->assertEquals($expected, $entities);
    }

    /**
     * @test
     */
    public function shouldParseNullByte()
    {
        // given
        $asEntities = new PatternAsEntities('\0', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new Escaped('0')], $entities);
    }

    /**
     * @test
     */
    public function shouldParseLookAroundAssertion()
    {
        // given
        $asEntities = new PatternAsEntities('\K', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new Escaped('K')], $entities);
    }

    /**
     * @test
     */
    public function shouldConsumeImmediatelyClosedGroupsRemainder()
    {
        // given
        $asEntities = new PatternAsEntities('()(?)', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new GroupOpen(), new GroupClose(), new GroupRemainder(''),], $entities);
    }

    /**
     * @test
     */
    public function shouldConsumeImmediatelyClosedGroupsRepeatedly()
    {
        // given
        $asEntities = new PatternAsEntities('())))(?)', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new GroupOpen(),
            new GroupClose(),
            new GroupClose(),
            new GroupClose(),
            new GroupClose(),
            new GroupRemainder(''),
        ];
        $this->assertEquals($expected, $entities);
    }

    /**
     * @test
     */
    public function shouldParseImmediatelyClosedCharacterClass()
    {
        // given
        $asEntities = new PatternAsEntities('[]]]', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new PosixOpen(),
            new Posix(']'),
            new PosixClose(),
            new Literal(']'),
        ];
        $this->assertEquals($expected, $entities);
    }

    /**
     * @test
     */
    public function shouldParseDoubleColorWordInCharacterClass()
    {
        // given
        $asEntities = new PatternAsEntities('[:alpha:]', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new PosixOpen(), new Posix(':alpha:'), new PosixClose()], $entities);
    }

    /**
     * @test
     */
    public function shouldParseEscapedClosingPosix()
    {
        // given
        $asEntities = new PatternAsEntities('[F\]O]', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new PosixOpen(), new Posix('F\]O'), new PosixClose()], $entities);
    }

    /**
     * @test
     */
    public function shouldParseNestedCharacterClass()
    {
        // given
        $asEntities = new PatternAsEntities('[01[:alpha:]%]', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new PosixOpen(),
            new Posix('01'),
            new Posix('[:alpha:]'),
            new Posix('%'),
            new PosixClose()
        ];
        $this->assertEquals($expected, $entities);
    }
}
