<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quote;

use TRegx\SafeRegex\preg;
use function implode;
use function preg_replace;

class PregExtendedQuoter implements Quoter
{
    public function quote(string $string, string $delimiter): string
    {
        $quoted = preg::quote($string, $delimiter);
        return $this->quoteWhiteSpaces($quoted);
    }

    private function quoteWhiteSpaces(string $input)
    {
        $whitespaces = implode([" ", "\t", "\x0B", "\f"]);
        $newLine = PHP_EOL;

        return preg_replace("/([$whitespaces]|$newLine)/", '\\\\$0', $input);
    }
}
