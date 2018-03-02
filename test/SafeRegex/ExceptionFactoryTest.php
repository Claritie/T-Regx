<?php
namespace Test\SafeRegex;

use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\ExceptionFactory;

class ExceptionFactoryTest extends TestCase
{
    protected function setUp()
    {
        $errorsCleaner = new ErrorsCleaner();
        $errorsCleaner->clear();
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function testPregErrors($invalidPattern)
    {
        // given
        $result = @preg_match($invalidPattern, '');
        $exceptionFactory = new ExceptionFactory();

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf('\SafeRegex\Exception\CompileSafeRegexException', $exception);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidUtf8Sequences()
     * @param string $description
     * @param string $utf8
     */
    public function test($description, $utf8)
    {
        // given
        $exceptionFactory = new ExceptionFactory();
        $result = @preg_match("/pattern/u", $utf8);

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf('\SafeRegex\Exception\RuntimeSafeRegexException', $exception);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function testUnexpectedReturnError($invalidPattern)
    {
        // given
        $result = @preg_match($invalidPattern, '');
        $exceptionFactory = new ExceptionFactory();
        $errorsCleaner = new ErrorsCleaner;
        $errorsCleaner->clear();

        // when
        $exception = $exceptionFactory->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf('\SafeRegex\Exception\SuspectedReturnSafeRegexException', $exception);
    }
}
