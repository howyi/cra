<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

use PHPUnit\Framework\TestCase;

class SortedVersionListTest extends TestCase
{
    public function testGetIterator()
    {
        $list = new SortedVersionList(
            Version::wip(4, 0, 0),
            Version::wip(1, 0, 0),
            Version::wip(3, 0, 0),
            Version::wip(2, 0, 0)
        );

        $expected = [
            Version::wip(1, 0, 0),
            Version::wip(2, 0, 0),
            Version::wip(3, 0, 0),
            Version::wip(4, 0, 0),
        ];
        $actual = iterator_to_array($list->getIterator());
        $this->assertEquals($expected, $actual);
    }

    public function testLatest()
    {
        $listA = new SortedVersionList(Version::wip(1, 0, 0), Version::wip(2, 0, 0));
        $this->assertEquals(Version::wip(2, 0, 0), $listA->latest());

        $listB = new SortedVersionList();
        $this->assertNull($listB->latest());
    }

    public function testLatestOrElse()
    {
        $listA = new SortedVersionList(Version::wip(1, 0, 0), Version::wip(2, 0, 0));
        $this->assertEquals(Version::wip(2, 0, 0), $listA->latestOrElse(Version::initial()));

        $listB = new SortedVersionList();
        $this->assertEquals(Version::initial(), $listB->latestOrElse(Version::initial()));
    }

    public function testReleased()
    {
        $list = new SortedVersionList(
            Version::released(2, 0, 0),
            Version::wip(3, 0, 0),
            Version::released(1, 0, 0)
        );

        $expected = new SortedVersionList(
            Version::released(1, 0, 0),
            Version::released(2, 0, 0)
        );
        $actual = $list->released();
        $this->assertEquals($expected, $actual);
    }

    public function testWip()
    {
        $list = new SortedVersionList(
            Version::wip(4, 0, 0),
            Version::released(2, 0, 0),
            Version::wip(3, 0, 0)
        );

        $expected = new SortedVersionList(
            Version::wip(3, 0, 0),
            Version::wip(4, 0, 0)
        );
        $actual = $list->wip();
        $this->assertEquals($expected, $actual);
    }
}
