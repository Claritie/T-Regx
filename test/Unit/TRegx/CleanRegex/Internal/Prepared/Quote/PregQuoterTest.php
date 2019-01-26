<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quote;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Quote\PregQuoter;

class PregQuoterTest extends TestCase
{
    public function testQuote()
    {
        // given
        $quoter = new PregQuoter();
        $text = '? #';

        // when
        $quoted = $quoter->quote($text, '/');

        // then
        $this->assertEquals('\\? \\#', $quoted);
    }
}
