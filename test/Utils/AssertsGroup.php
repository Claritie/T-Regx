<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Details\Group\Group;

trait AssertsGroup
{
    function assertGroupIndex(int $expectedIndex, Group $group): void
    {
        Assert::assertSame($expectedIndex, $group->index());
    }

    function assertGroupNotMatched(Group $group): void
    {
        $id = $group->usedIdentifier();
        $message = "Failed to assert that group $id is not matched";
        Assert::assertFalse($group->matched(), $message);
        Assert::assertSame('first', $group->or('first'), $message);
        Assert::assertSame('second', $group->or('second'), $message);
    }

    function assertGroupMatched(Group $group): void
    {
        $id = $group->usedIdentifier();
        $message = "Failed to assert that group $id is matched";
        Assert::assertTrue($group->matched(), $message);
        Assert::assertSame($group->text(), $group->or('first'), $message);
        Assert::assertSame($group->text(), $group->or('second'), $message);
    }

    function assertGroupTexts(array $expectedTexts, array $groups)
    {
        Assert::assertSame($expectedTexts, $this->groupTexts($groups));
        $this->assertGroupsMatched($groups);
    }

    function assertGroupTextsOptional(array $expectedTexts, array $groups)
    {
        Assert::assertSame($expectedTexts, $this->groupTextsOptional($groups), "Failed asserting that texts of groups are identical");
    }

    function assertGroupOffsetsOptional(array $expectedOffsets, array $groups)
    {
        Assert::assertSame($expectedOffsets, $this->groupOffsetsOptional($groups), "Failed asserting that offsets of groups are identical");
    }

    function assertGroupNames(array $expectedNames, array $groups)
    {
        Assert::assertSame($expectedNames, $this->groupNames($groups));
    }

    function assertGroupIndices(array $indices, array $groups)
    {
        Assert::assertSame($indices, $this->groupIndices($groups));
    }

    function assertGroupOffsets(array $offsets, array $groups)
    {
        Assert::assertSame($offsets, $this->groupOffsets($groups));
        $this->assertGroupsMatched($groups);
    }

    function assertGroupByteOffsets(array $byteOffsets, array $groups)
    {
        Assert::assertSame($byteOffsets, $this->groupByteOffsets($groups));
        $this->assertGroupsMatched($groups);
    }

    function assertGroupIndicesConsequetive(array $groups): void
    {
        Assert::assertSame($this->sequence(\count($groups)), $this->groupIndices($groups));
    }

    function assertGroupsMatched(array $groups)
    {
        Assert::assertSame($this->expectedTrue($this->groupMatched($groups)), $this->groupMatched($groups));
    }

    private function groupTextsOptional(array $groups): array
    {
        $texts = [];
        foreach ($groups as $key => $group) {
            if ($group->matched()) {
                $texts[$key] = $group->text();
            } else {
                $texts[$key] = null;
            }
        }
        return $texts;
    }

    private function groupOffsetsOptional(array $groups): array
    {
        $texts = [];
        foreach ($groups as $key => $group) {
            if ($group->matched()) {
                $texts[$key] = $group->offset();
            } else {
                $texts[$key] = null;
            }
        }
        return $texts;
    }

    private function groupTexts(array $groups): array
    {
        $texts = [];
        foreach ($groups as $key => $group) {
            $texts[$key] = $group->text();
        }
        return $texts;
    }

    function groupNames(array $groups): array
    {
        $names = [];
        foreach ($groups as $key => $group) {
            $names[$key] = $group->name();
        }
        return $names;
    }

    function groupIndices(array $groups): array
    {
        $indices = [];
        foreach ($groups as $key => $group) {
            $indices[$key] = $group->index();
        }
        return $indices;
    }

    function groupOffsets(array $groups): array
    {
        $offsets = [];
        foreach ($groups as $key => $group) {
            $offsets[$key] = $group->offset();
        }
        return $offsets;
    }

    function groupByteOffsets(array $groups): array
    {
        $offsets = [];
        foreach ($groups as $key => $group) {
            $offsets[$key] = $group->byteOffset();
        }
        return $offsets;
    }

    function groupMatched(array $groups): array
    {
        $matched = [];
        foreach ($groups as $key => $group) {
            $matched[$key] = $group->matched();
        }
        return $matched;
    }

    private function sequence(int $count): array
    {
        $expected = [];
        for ($i = 0; $i < $count; $i++) {
            $expected[] = $i + 1;
        }
        return $expected;
    }

    private function expectedTrue(array $entries): array
    {
        $expected = [];
        foreach ($entries as $key => $value) {
            $expected[$key] = true;
        }
        return $expected;
    }
}
