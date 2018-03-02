<?php
namespace CleanRegex\Replace\Callback;

use CleanRegex\Exception\Preg\PatternReplaceException;
use Closure;
use SafeRegex\preg;
use CleanRegex\Internal\Arguments;
use CleanRegex\Internal\Pattern as InternalPattern;

class ReplacePatternCallbackInvoker
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    /**
     * @param InternalPattern $pattern
     * @param string  $subject
     */
    public function __construct(InternalPattern $pattern, $subject)
    {
        Arguments::string($subject);

        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @param Closure $callback
     * @return string
     * @throws PatternReplaceException
     */
    public function invoke(Closure $callback)
    {
        $result = $this->performReplaceCallback($callback);

        if ($result === null) {
            throw new PatternReplaceException();
        }

        return $result;
    }

    /**
     * @param Closure $callback
     * @return string
     * @throws \SafeRegex\Exception\SafeRegexException
     */
    private function performReplaceCallback(Closure $callback)
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->analyzePattern());

        return preg::replace_callback($this->pattern->pattern, function (array $matches) use ($object) {
            return $object->invoke($matches);
        }, $this->subject);
    }

    /**
     * @return array
     * @throws \SafeRegex\Exception\SafeRegexException
     */
    private function analyzePattern()
    {
        $matches = array();
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }
}
