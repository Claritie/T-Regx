<?php
namespace CleanRegex\Internal;

use PHPUnit\Framework\TestCase;

class ArgumentsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldValidateString()
    {
        // when
        Arguments::string('text');

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotValidateString()
    {
        // then
        $this->expectException('InvalidArgumentException');

        // when
        Arguments::string(2);

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldValidateInteger()
    {
        // when
        Arguments::integer(2);

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotValidateInteger()
    {
        // then
        $this->expectException('InvalidArgumentException');

        // when
        Arguments::integer('text');

        // then
        $this->assertTrue(true);
    }

    function expectException($className) {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($className);
        } else {
            parent::expectException($className);
        }
    }
}
