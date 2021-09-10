<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

use TRegx\SafeRegex\preg;

class TextWord implements Word
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function quote(string $delimiter): string
    {
        return $this->quoteExtendedWhitespace(preg::quote($this->text, $delimiter));
    }

    private function quoteExtendedWhitespace(string $string): string
    {
        return \strtr($string, [
            ' '  => '\ ', #32
            "\t" => '\t', #9
            "\n" => '\n', #10
            "\v" => '\v', #11
            "\f" => '\f', #12
            "\r" => '\r', #13
        ]);
    }
}
