<?php declare(strict_types=1);

namespace Howyi\Cra\Domain\Version;

use PHPUnit\Framework\TestCase;

class VersionCollectionTest extends TestCase
{
    private $versions;

    public function setUp()
    {
        $this->versions = new VersionCollection(...[
            Version::released(1, 0, 0),
            Version::released(1, 0, 2),
            Version::wip(1, 1, 0),
            Version::released(1, 0, 1),
        ]);
    }

    public function testConcat()
    {
        $a = new VersionCollection(...[
            Version::released(1, 0, 0),
            Version::released(1, 0, 1),
        ]);
        $b = new VersionCollection(...[
            Version::wip(1, 0, 2),
            Version::wip(2, 0, 0),
        ]);

        $expected = [
            Version::released(1, 0, 0),
            Version::released(1, 0, 1),
            Version::wip(1, 0, 2),
            Version::wip(2, 0, 0),
        ];
        $actual = VersionCollection::concat($a, $b)->toAscSortedArray();

        $this->assertEquals($expected, $actual);
    }

    public function testPush()
    {
        $expected = [
            Version::released(1, 0, 0),
            Version::released(1, 0, 1),
            Version::released(1, 0, 2),
            Version::wip(1, 1, 0),
            Version::wip(2, 0, 0),
        ];
        $actual = $this->versions->push(Version::wip(2, 0, 0))->toAscSortedArray();

        $this->assertEquals($expected, $actual);
    }

    public function testGetIterator()
    {
        $this->assertInstanceOf(VersionAscIterator::class, $this->versions->getIterator());
    }

    public function testReleased()
    {
        $expected = [
            Version::released(1, 0, 0),
            Version::released(1, 0, 1),
            Version::released(1, 0, 2),
        ];
        $actual = $this->versions->released()->toAscSortedArray();

        $this->assertEquals($expected, $actual);
    }

    public function testWip()
    {
        $expected = [Version::wip(1, 1, 0)];
        $actual = $this->versions->wip()->toAscSOrtedArray();

        $this->assertEquals($expected, $actual);
    }

    public function testLatest()
    {
        $expected = Version::wip(1, 1, 0);
        $actual = $this->versions->latest();

        $this->assertEquals($expected, $actual);
    }

    public function testToAscSortedArray()
    {
        $expected = [
            Version::released(1, 0, 0),
            Version::released(1, 0, 1),
            Version::released(1, 0, 2),
            Version::wip(1, 1, 0),
        ];
        $actual = $this->versions->toAscSortedArray();

        $this->assertEquals($expected, $actual);
    }
}
