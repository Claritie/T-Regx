<?php
namespace CleanRegex\Match;

use CleanRegex\Exception\Preg\PatternMatchException;
use CleanRegex\Internal\Pattern as InternalPattern;
use Closure;
use SafeRegex\preg;
use CleanRegex\Internal\Arguments;
use SafeRegex\Exception\SafeRegexException;

class MatchPattern
{
    const WHOLE_MATCH = 0;

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
     * @return array
     * @throws PatternMatchException
     */
    public function all()
    {
        $matches = array();
        preg::match_all($this->pattern->pattern, $this->subject, $matches);

        return $matches[0];
    }

    /**
     * @param Closure $callback
     * @return void
     */
    public function iterate(Closure $callback)
    {
        foreach ($this->getMatchObjects() as $object) {
            call_user_func($callback, $object);
        }
    }

    /**
     * @param Closure $callback
     * @return array
     */
    public function map(Closure $callback)
    {
        $results = array();
        foreach ($this->getMatchObjects() as $object) {
            $results[] = call_user_func($callback, $object);
        }
        return $results;
    }

    /**
     * @param Closure|null $callback
     * @return null|string
     */
    public function first(Closure $callback = null)
    {
        $matches = $this->performMatchAll();
        if (empty($matches[0])) return null;

        if ($callback !== null) {
            call_user_func($callback, new Match($this->subject, 0, $matches));
        }

        list($value, $offset) = $matches[self::WHOLE_MATCH][0];
        return $value;
    }

    /**
     * @return Match[]
     */
    private function getMatchObjects()
    {
        return $this->constructMatchObjects($this->performMatchAll());
    }

    /**
     * @return array
     */
    private function performMatchAll()
    {
        $matches = array();
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }

    /**
     * @param array $matches
     * @return Match[]
     */
    private function constructMatchObjects(array $matches)
    {
        $matchObjects = array();

        foreach ($matches[0] as $index => $match) {
            $matchObjects[] = new Match($this->subject, $index, $matches);
        }

        return $matchObjects;
    }

    /**
     * @return bool
     * @throws SafeRegexException
     */
    public function matches()
    {
        $result = preg::match($this->pattern->pattern, $this->subject);

        return $result === 1;
    }

    /**
     * @return int
     * @throws SafeRegexException
     */
    public function count()
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }
}
