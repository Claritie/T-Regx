<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quote;

interface Quoter
{
    public function quote(string $input, string $delimiter): string;
}
