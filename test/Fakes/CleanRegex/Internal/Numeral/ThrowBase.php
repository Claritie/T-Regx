<?php
namespace Test\Fakes\CleanRegex\Internal\Numeral;

use AssertionError;
use TRegx\CleanRegex\Internal\Numeral\Base;

class ThrowBase extends Base
{
    public function __construct()
    {
        parent::__construct(2);
    }

    public function base(): int
    {
        throw new AssertionError("Failed to assert that number Base wasn't used");
    }
}
