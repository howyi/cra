<?php declare(strict_types=1);

namespace Howyi\Cra\Domain\Version;

use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testReleased()
    {
        $version = Version::released(1, 2, 3);
        $this->assertTrue($version->isReleased());
        $this->assertFalse($version->isWip());
    }

    public function testWip()
    {
        $version = Version::wip(1, 2, 3);
        $this->assertFalse($version->isReleased());
        $this->assertTrue($version->isWip());
    }

    public function releaseTypeDataProvider()
    {
        return [
            'Minor' => [ReleaseType::MINOR(), Version::released(1, 2, 3), Version::wip(1, 3, 0)],
            'Major' => [ReleaseType::MAJOR(), Version::released(1, 2, 3), Version::wip(2, 0, 0)],
            'Patch' => [ReleaseType::PATCH(), Version::released(1, 2, 3), Version::wip(1, 2, 4)],
        ];
    }

    /**
     * @dataProvider releaseTypeDataProvider
     */
    public function testIncrement($releaseType, $version, $expected)
    {
        $this->assertEquals($expected, $version->increment($releaseType));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage 対応していないリリース種別を渡された: test
     */
    public function testIncrementGivenInvalidReleaseType()
    {
        // 普通にnewするとMyCLabs\Enum\Enumのチェックが走るため、無理やり書き換える
        $releaseType = ReleaseType::MAJOR();
        \Closure::bind(function () {
            $this->value = 'test';
        }, $releaseType, ReleaseType::class)->__invoke();

        Version::released(1, 2, 3)->increment($releaseType);
    }

    public function testToString()
    {
        $this->assertSame('1.2.3', Version::released(1, 2, 3)->toString());
    }

    public function testToReleaseBranchName()
    {
        $this->assertSame('release/1.2.3', Version::released(1, 2, 3)->toReleaseBranchName());
    }
}
