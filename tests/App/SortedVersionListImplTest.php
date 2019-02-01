<?php declare(strict_types=1);

namespace Howyi\Cra\App;

use Howyi\Cra\Core\Version;
use PHPUnit\Framework\TestCase;

class SortedVersionListImplTest extends TestCase
{
    public function comparePatternDataProvider()
    {
        return [
            [
                Version::wip(1, 0, 0),
                Version::wip(1, 0, 0),
                0,
            ],
            [
                Version::wip(1, 0, 0),
                Version::wip(0, 1, 0),
                1,
            ],
            [
                Version::wip(0, 1, 0),
                Version::wip(1, 0, 0),
                -1,
            ],
        ];
    }

    /**
     * @dataProvider comparePatternDataProvider
     */
    public function testCompare(Version $a, Version $b, int $expected)
    {
        $actual = \Closure::bind(function ($a, $b) {
            return $this->compare($a, $b);
        }, new SortedVersionListImpl(), SortedVersionListImpl::class)->__invoke($a, $b);
        $this->assertSame($expected, $actual);
    }
}
