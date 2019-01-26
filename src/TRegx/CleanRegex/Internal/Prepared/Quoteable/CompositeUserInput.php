<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quote\Quoter;
use TRegx\CleanRegex\Internal\StringValue;
use function array_map;
use function implode;

class CompositeUserInput implements Quoteable
{
    /** @var array */
    private $userInputs;
    /** @var Quoter */
    private $quoter;

    public function __construct(array $userInputs, Quoter $quoter)
    {
        $this->userInputs = $userInputs;
        $this->quoter = $quoter;
    }

    public function quote(string $delimiter): string
    {
        $quoted = $this->getQuoted($delimiter);
        return implode($quoted);
    }

    /**
     * @param string $delimiter
     * @return string[]
     */
    private function getQuoted(string $delimiter): array
    {
        return array_map(function (UserInputQuoteable $quotable) use ($delimiter) {
            return $quotable->quote($delimiter);
        }, $this->getAsUserInputs());
    }

    /**
     * @return UserInputQuoteable[]
     */
    private function getAsUserInputs(): array
    {
        return array_map(function ($quoteable) {
            $this->validateQuoteable($quoteable);
            return new UserInputQuoteable($quoteable, $this->quoter);
        }, $this->userInputs);
    }

    private function validateQuoteable($quoteable): void
    {
        if (!is_string($quoteable)) {
            $type = (new StringValue($quoteable))->getString();
            throw new InvalidArgumentException("Invalid injected value. Expected string, but $type given");
        }
    }
}
