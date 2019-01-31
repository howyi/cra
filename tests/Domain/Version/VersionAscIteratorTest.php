<?php declare(strict_types=1);

namespace Howyi\Cra\Domain\Version;

use PHPUnit\Framework\TestCase;

class VersionAscIteratorTest extends TestCase
{
    public function testAll()
    {
        $iterator = new VersionAscIterator(
            Version::released(1, 0, 1),
            Version::released(1, 0, 0),
            Version::wip(1, 2, 0),
            Version::released(1, 1, 1),
            Version::released(1, 0, 0),
            Version::wip(1, 1, 2),
            Version::released(1, 1, 0),
            Version::wip(2, 0, 0),
            Version::released(1, 0, 2)
        );

        $expected = [
            Version::released(1, 0, 0),
            Version::released(1, 0, 0),
            Version::released(1, 0, 1),
            Version::released(1, 0, 2),
            Version::released(1, 1, 0),
            Version::released(1, 1, 1),
            Version::wip(1, 1, 2),
            Version::wip(1, 2, 0),
            Version::wip(2, 0, 0),
        ];
        $actual = iterator_to_array($iterator);

        $this->assertEquals($expected, $actual);
    }
}
