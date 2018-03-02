<?php
namespace Test\SafeRegex;

use Closure;
use Exception;
use PHPUnit\Framework\TestCase;
use SafeRegex\Guard\GuardedExecution;
use Test\Warnings;

class GuardedExecutionTest extends TestCase
{
    /**
     * @test
     * @dataProvider possibleObsoleteWarnings
     * @param Closure $obsoleteWarning
     */
    public function shouldIgnorePreviousWarnings(Closure $obsoleteWarning)
    {
        // given
        call_user_func($obsoleteWarning);

        // when
        $invocation = GuardedExecution::catched('preg_match', function () {
            return 1;
        });

        // then
        $this->assertNull($invocation->getException());
        $this->assertFalse($invocation->hasException());
    }

    public function possibleObsoleteWarnings()
    {
        return array(
            array(function () {
                Warnings::causeRuntimeWarning();
            }),
            array(function () {
                Warnings::causeCompileWarning();
            }),
        );
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarning()
    {
        // when
        $invocation = GuardedExecution::catched('preg_match', function () {
            Warnings::causeRuntimeWarning();
            return false;
        });

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf('\SafeRegex\Exception\RuntimeSafeRegexException', $invocation->getException());
    }

    /**
     * @test
     */
    public function shouldCatchCompileWarning()
    {
        // when
        $invocation = GuardedExecution::catched('preg_match', function () {
            Warnings::causeCompileWarning();
            return false;
        });

        // then
        $this->assertTrue($invocation->hasException());
        $this->assertInstanceOf('\SafeRegex\Exception\CompileSafeRegexException', $invocation->getException());
    }

    /**
     * @test
     */
    public function shouldCatchRuntimeWarningWhenInvoking()
    {
        // then
        $this->expectException('\SafeRegex\Exception\RuntimeSafeRegexException');

        // when
        GuardedExecution::invoke('preg_match', function () {
            Warnings::causeRuntimeWarning();
            return false;
        });
    }

    /**
     * @test
     */
    public function shouldCatchCompileWarningWhenInvoking()
    {
        // then
        $this->expectException('\SafeRegex\Exception\CompileSafeRegexException');

        // when
        GuardedExecution::invoke('preg_match', function () {
            Warnings::causeCompileWarning();
            return false;
        });
    }

    /**
     * @test
     */
    public function shouldRethrowException()
    {
        // then
        $this->expectException('Exception');
        $this->expectExceptionMessage('Rethrown exception');

        // when
        GuardedExecution::invoke('preg_match', function () {
            throw new Exception('Rethrown exception');
        });
    }

    /**
     * @test
     */
    public function shouldInvokeReturnResult()
    {
        // when
        $result = GuardedExecution::invoke('preg_match', function () {
            return 13;
        });

        // then
        $this->assertEquals(13, $result);
    }

    /**
     * @test
     */
    public function shouldCatchReturnResult()
    {
        // when
        $invocation = GuardedExecution::catched('preg_match', function () {
            return 13;
        });

        // then
        $this->assertEquals(13, $invocation->getResult());
    }

    function expectException($className)
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($className);
        } else {
            parent::expectException($className);
        }
    }

    function expectExceptionMessage($message)
    {
        // ignore
    }
}
