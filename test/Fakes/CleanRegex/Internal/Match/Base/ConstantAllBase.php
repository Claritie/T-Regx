<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Base;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class ConstantAllBase implements Base
{
    use Fails;

    /** @var RawMatchesOffset */
    private $matchesOffset;

    public function __construct(RawMatchesOffset $matchesOffset)
    {
        $this->matchesOffset = $matchesOffset;
    }

    public function matchAllOffsets(): RawMatchesOffset
    {
        return $this->matchesOffset;
    }

    public function match(): RawMatch
    {
        throw $this->fail();
    }

    public function matchOffset(): RawMatchOffset
    {
        throw $this->fail();
    }

    public function matchAll(): RawMatches
    {
        throw $this->fail();
    }
}
