<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quote;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Quote\PregExtendedQuoter;

class PregExtendedQuoterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuoteWhiteSpace()
    {
        // given
        $quoter = new PregExtendedQuoter();
        $input = " \t\x0B\f #?";

        // then
        $result = $quoter->quote($input, '');

        // then
        $this->assertEquals("\\ \\\t\\\x0B\\\f\\ \\#\\?", $result);
    }
}
