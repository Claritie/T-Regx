<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quote;

use TRegx\SafeRegex\preg;

class PregQuoter implements Quoter
{
    public function quote(string $string, string $delimiter): string
    {
        return preg::quote($string, $delimiter);
    }
}
