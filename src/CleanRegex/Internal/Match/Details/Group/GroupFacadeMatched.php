<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;

class GroupFacadeMatched
{
    /** @var Subjectable */
    private $subject;
    /** @var GroupHandle */
    private $groupHandle;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Signatures */
    private $signatures;

    public function __construct(Subjectable          $subject,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory      $allFactory,
                                GroupHandle          $groupHandle,
                                Signatures           $signatures)
    {
        $this->subject = $subject;
        $this->groupHandle = $groupHandle;
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
        $this->signatures = $signatures;
    }

    public function createGroup(GroupKey $group, UsedForGroup $forGroup, MatchEntry $entry): MatchedGroup
    {
        [$text, $offset] = $forGroup->getGroupTextAndOffset($this->groupHandle->groupHandle($group));
        $groupEntry = new GroupEntry($text, $offset, $this->subject);
        return $this->factoryStrategy->createMatched(
            $this->subject,
            new GroupDetails($this->signatures->signature($group), $group, $this->allFactory),
            $groupEntry,
            new SubstitutedGroup($entry, $groupEntry));
    }
}
