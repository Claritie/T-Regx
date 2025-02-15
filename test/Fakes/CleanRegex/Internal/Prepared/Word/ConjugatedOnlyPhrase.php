<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Word;

use AssertionError;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class ConjugatedOnlyPhrase implements Phrase
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->pattern;
    }

    public function unconjugated(string $delimiter): string
    {
        throw new AssertionError('Failed to assert that Phrase was used unconjugated');
    }
}
