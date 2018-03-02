<?php
namespace CleanRegex\Replace;

use CleanRegex\Internal\Arguments;
use CleanRegex\Internal\Pattern as InternalPattern;
use CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use Closure;
use SafeRegex\preg;

class ReplacePattern
{
    /** @var InternalPattern */
    private $pattern;

    /** @var string */
    private $subject;

    /**
     * @param InternalPattern $pattern
     * @param string          $subject
     */
    public function __construct(InternalPattern $pattern, $subject)
    {
        Arguments::string($subject);

        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @param string $replacement
     * @return string
     */
    public function with($replacement)
    {
        Arguments::string($replacement);

        return preg::replace($this->pattern->pattern, $replacement, $this->subject);
    }

    /**
     * @param Closure $callback
     * @return string
     */
    public function callback(Closure $callback)
    {
        $replacePatternCallbackInvoker = new ReplacePatternCallbackInvoker($this->pattern, $this->subject);
        return $replacePatternCallbackInvoker->invoke($callback);
    }
}
