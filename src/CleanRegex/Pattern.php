<?php
namespace CleanRegex;

use CleanRegex\Internal\Arguments;
use CleanRegex\Internal\Delimiter\Delimiterer;
use CleanRegex\Internal\Delimiter\ExplicitDelimiterRequiredException;
use CleanRegex\Internal\Pattern as InternalPattern;
use CleanRegex\Match\MatchPattern;
use CleanRegex\Replace\ReplacePattern;
use SafeRegex\Exception\SafeRegexException;

class Pattern
{
    /** @var string */
    public $pattern;

    /** @var string */
    private $flags;

    /**
     * @param string $pattern
     * @param string $flags
     */
    public function __construct($pattern, $flags = '')
    {
        Arguments::string($pattern)->string($flags);

        $this->pattern = $pattern;
        $this->flags = $flags;
    }

    /**
     * @param string $subject
     * @return MatchPattern
     */
    public function match($subject)
    {
        Arguments::string($subject);
        return new MatchPattern(new InternalPattern($this->pattern), $subject);
    }

    /**
     * @param string $subject
     * @return bool
     * @throws Exception\CleanRegex\ArgumentNotAllowedException
     * @throws SafeRegexException
     */
    public function matches($subject)
    {
        Arguments::string($subject);
        $matchesPattern = new MatchesPattern(new InternalPattern($this->pattern), $subject);
        return $matchesPattern->matches();
    }

    /**
     * @param string $subject
     * @return ReplacePattern
     */
    public function replace($subject)
    {
        Arguments::string($subject);
        return new ReplacePattern(new InternalPattern($this->pattern), $subject);
    }

    /**
     * @param array $haystack
     * @return array
     */
    public function filter(array $haystack)
    {
        $filterArrayPattern = new FilterArrayPattern(new InternalPattern($this->pattern), $haystack);
        return $filterArrayPattern->filter();
    }

    /**
     * @param string $subject
     * @return SplitPattern
     */
    public function split($subject)
    {
        Arguments::string($subject);

        return new SplitPattern(new InternalPattern($this->pattern), $subject);
    }

    /**
     * @param string $subject
     * @return int
     */
    public function count($subject)
    {
        Arguments::string($subject);
        $countPattern = new CountPattern(new InternalPattern($this->pattern), $subject);
        return $countPattern->count();
    }

    /**
     * @return string
     */
    public function quote()
    {
        $quotePattern = new QuotePattern(new InternalPattern($this->pattern));
        return $quotePattern->quote();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $validPattern = new ValidPattern(new InternalPattern($this->pattern));
        return $validPattern->isValid();
    }

    /**
     * @return string
     * @throws ExplicitDelimiterRequiredException
     */
    public function delimitered()
    {
        $delimiterer = new Delimiterer();
        return $delimiterer->delimiter($this->pattern);
    }

    /**
     * @param string $pattern
     * @param string $flags
     * @return Pattern
     */
    public static function of($pattern, $flags = '')
    {
        Arguments::string($pattern)->string($flags);
        return new Pattern($pattern, $flags);
    }

    /**
     * @param string $pattern
     * @param string $flags
     * @return Pattern
     */
    public static function pattern($pattern, $flags = '')
    {
        return self::of($pattern, $flags);
    }
}
