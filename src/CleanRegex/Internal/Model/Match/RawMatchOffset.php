<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Internal\Tuple;

class RawMatchOffset implements IRawMatchOffset
{
    /** @var array[] */
    private $match;
    /** @var int|null */
    private $index;

    public function __construct(array $match, ?int $index)
    {
        $this->match = $match;
        $this->index = $index;
    }

    public function matched(): bool
    {
        return !empty($this->match);
    }

    public function getText(): string
    {
        return Tuple::first($this->match[0]);
    }

    public function hasGroup($nameOrIndex): bool
    {
        return \array_key_exists($nameOrIndex, $this->match);
    }

    public function getGroup($nameOrIndex): ?string
    {
        [$text, $offset] = $this->match[$nameOrIndex];
        if ($offset === -1) {
            return null;
        }
        return $text;
    }

    public function byteOffset(): int
    {
        return $this->getGroupByteOffset(0);
    }

    public function getGroupByteOffset($nameOrIndex): ?int
    {
        $offset = Tuple::second($this->match[$nameOrIndex]);
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }

    public function getGroupKeys(): array
    {
        return \array_keys($this->match);
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        if (!\array_key_exists($nameOrIndex, $this->match)) {
            return false;
        }
        $match = $this->match[$nameOrIndex];
        if (\is_array($match)) {
            return Tuple::second($match) !== -1;
        }
        return false;
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->match[$nameOrIndex];
    }

    public function getGroupsTexts(): array
    {
        return \array_map(static function ($match) {
            if ($match === null) {
                return null;
            }
            if ($match === '') {
                return null;
            }
            if (\is_array($match)) {
                [$text, $offset] = $match;
                if ($offset === -1) {
                    return null;
                }
                return $text;
            }
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }, $this->match);
    }

    public function getGroupsOffsets(): array
    {
        return \array_map([Tuple::class, 'second'], $this->match);
    }

    public function getIndex(): int
    {
        if ($this->index === null) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        return $this->index;
    }
}
