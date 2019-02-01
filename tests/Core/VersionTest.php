<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function setup()
    {
        Version::setVersionPrefix('v');
        Version::setReleaseBranchPrefix('release/');
    }

    public function testInitial()
    {
        $version = Version::initial();
        $this->assertSame(0, $version->major());
        $this->assertSame(0, $version->minor());
        $this->assertSame(0, $version->patch());
        $this->assertFalse($version->isReleased());
        $this->assertTrue($version->isWip());
    }

    public function testReleased()
    {
        $version = Version::released(1, 2, 3);
        $this->assertSame(1, $version->major());
        $this->assertSame(2, $version->minor());
        $this->assertSame(3, $version->patch());
        $this->assertTrue($version->isReleased());
        $this->assertFalse($version->isWip());
    }

    public function testWip()
    {
        $version = Version::wip(1, 2, 3);
        $this->assertSame(1, $version->major());
        $this->assertSame(2, $version->minor());
        $this->assertSame(3, $version->patch());
        $this->assertFalse($version->isReleased());
        $this->assertTrue($version->isWip());
    }

    public function expectedIncrementPatternDataProvider()
    {
        return [
            'Major' => [ReleaseType::MAJOR(), Version::released(1, 2, 3), Version::wip(2, 0, 0)],
            'Minor' => [ReleaseType::MINOR(), Version::released(1, 2, 3), Version::wip(1, 3, 0)],
            'Patch' => [ReleaseType::PATCH(), Version::released(1, 2, 3), Version::wip(1, 2, 4)],
        ];
    }

    /**
     * @dataProvider expectedIncrementPatternDataProvider
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
        $this->assertSame('v1.2.3', Version::released(1, 2, 3)->toString());
    }

    public function testToReleaseBranchName()
    {
        $this->assertSame('release/v1.2.3', Version::released(1, 2, 3)->toReleaseBranchName());
    }
}
