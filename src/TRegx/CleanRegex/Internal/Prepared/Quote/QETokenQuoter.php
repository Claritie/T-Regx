<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quote;

class QETokenQuoter implements Quoter
{
    public function quote(string $input, string $delimiter): string
    {
        $pieces = preg_split('/\\\\E/u', $input, -1, PREG_SPLIT_NO_EMPTY);
        $innerQuoted = join("\E\\\E\Q", $pieces);
        return "\Q$innerQuoted\E";
    }
}
