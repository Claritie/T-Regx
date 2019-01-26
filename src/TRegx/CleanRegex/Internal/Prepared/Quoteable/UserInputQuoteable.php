<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

use TRegx\CleanRegex\Internal\Prepared\Quote\Quoter;

class UserInputQuoteable implements Quoteable
{
    /** @var string */
    private $userInput;
    /** @var Quoter */
    private $quoter;

    public function __construct(string $userInput, Quoter $quoter)
    {
        $this->userInput = $userInput;
        $this->quoter = $quoter;
    }

    public function quote(string $delimiter): string
    {
        return $this->quoter->quote($this->userInput, $delimiter);
    }
}
