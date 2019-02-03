<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

use PHPUnit\Framework\TestCase;

class SortedVersionListTest extends TestCase
{
    public function testGetIterator()
    {
        $list = new SortedVersionListDummyImpl(
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
        $list = new SortedVersionListDummyImpl(Version::wip(1, 0, 0), Version::wip(2, 0, 0));

        $expected = Version::wip(2, 0, 0);
        $actual = $list->latest();
        $this->assertEquals($expected, $actual);
    }

    public function testReleased()
    {
        $list = new SortedVersionListDummyImpl(
            Version::released(2, 0, 0),
            Version::wip(3, 0, 0),
            Version::released(1, 0, 0)
        );

        $expected = new SortedVersionListDummyImpl(
            Version::released(1, 0, 0),
            Version::released(2, 0, 0)
        );
        $actual = $list->released();
        $this->assertEquals($expected, $actual);
    }

    public function testWip()
    {
        $list = new SortedVersionListDummyImpl(
            Version::wip(4, 0, 0),
            Version::released(2, 0, 0),
            Version::wip(3, 0, 0)
        );

        $expected = new SortedVersionListDummyImpl(
            Version::wip(3, 0, 0),
            Version::wip(4, 0, 0)
        );
        $actual = $list->wip();
        $this->assertEquals($expected, $actual);
    }
}
