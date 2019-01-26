<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quote;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Quote\QETokenQuoter;

class QETokenQuoterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuote()
    {
        // given
        $quoter = new QETokenQuoter();
        $pattern = ".?";

        // when
        $quoted = $quoter->quote($pattern, '');

        // then
        $this->assertEquals("\Q.?\E", $quoted);
    }

    /**
     * @test
     */
    public function shouldQuote_forInnerQuoteEnd()
    {
        // given
        $quoter = new QETokenQuoter();
        $pattern = ". \E\E\E ?";

        // when
        $quoted = $quoter->quote($pattern, '');

        // then
        $this->assertEquals("\Q. \E\\\E\Q ?\E", $quoted);
    }
}
