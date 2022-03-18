<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Numeral\MatchBase;
use TRegx\CleanRegex\Internal\Match\Numeral\MatchExceptions;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Subject;

class MatchIntStream implements Upstream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;
    /** @var MatchBase */
    private $base;
    /** @var Subject */
    private $subject;

    public function __construct(StreamBase $stream, Base $base, Subject $subject)
    {
        $this->stream = $stream;
        $this->base = new MatchBase($base, new MatchExceptions());
        $this->subject = $subject;
    }

    protected function entries(): array
    {
        return \array_map([$this->base, 'integer'], $this->stream->all()->getTexts());
    }

    protected function firstValue(): int
    {
        try {
            return $this->base->integer($this->stream->first()->getText());
        } catch (UnmatchedStreamException $exception) {
            throw new StreamRejectedException($this->subject, SubjectNotMatchedException::class, new FirstMatchAsIntMessage());
        }
    }
}
