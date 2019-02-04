<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

use Eloquent\Enumeration\AbstractValueMultiton;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function validStringDataProvider()
    {
        return [
            ['1.2.3', true],
            ['v1.2.3', true],
            ['foo', false],
        ];
    }

    /**
     * @dataProvider validStringDataProvider
     */
    public function testIsValidString($str, $expected)
    {
        $this->assertSame($expected, Version::isValidString($str));
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

    public function releasedVersionStringDataProvider()
    {
        return [
            ['v1.2.3', Version::released(1, 2, 3)],
            ['1.2.3', Version::released(1, 2, 3)],
        ];
    }

    /**
     * @dataProvider releasedVersionStringDataProvider
     */
    public function testReleasedFromString($str, $expected)
    {
        $this->assertTrue(Version::releasedFromString($str)->equals($expected));
    }

    /**
     * @expectedException \Sasamium\Cra\Core\InvalidVersionStringException
     * @expectedExceptionMessage given: foo
     */
    public function testReleasedFromStringGivenInvalidString()
    {
        Version::releasedFromString('foo');
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

    public function wipVersionStringDataProvider()
    {
        return [
            ['v1.2.3', Version::wip(1, 2, 3)],
            ['1.2.3', Version::wip(1, 2, 3)],
        ];
    }

    /**
     * @dataProvider wipVersionStringDataProvider
     */
    public function testWipFromString($str, $expected)
    {
        $this->assertTrue(Version::wipFromString($str)->equals($expected));
    }

    /**
     * @expectedException \Sasamium\Cra\Core\InvalidVersionStringException
     * @expectedExceptionMessage given: foo
     */
    public function testWipFromStringGivenInvalidString()
    {
        Version::wipFromString('foo');
    }

    public function expectedIncrementPatternDataProvider()
    {
        return [
            'Major' => [ReleaseType::MAJOR(), Version::released(1, 2, 3), Version::wip(2, 0, 0)],
            'Minor' => [ReleaseType::MINOR(), Version::released(1, 2, 3), Version::wip(1, 3, 0)],
            'Patch' => [ReleaseType::PATCH(), Version::released(1, 2, 3), Version::wip(1, 2, 4)],
        ];
    }

    public function equalityDataProvider()
    {
        return [
            [Version::wip(1, 2, 3), Version::wip(1, 2, 3), true],
            [Version::wip(1, 2, 3), Version::wip(1, 2, 4), false],
            [Version::wip(1, 2, 3), Version::released(1, 2, 3), false],
            [Version::wip(1, 2, 3), Version::released(1, 2, 4), false],
        ];
    }

    /**
     * @dataProvider equalityDataProvider
     */
    public function testEquals($a, $b, $expected)
    {
        $this->assertSame($expected, $a->equals($b));
    }

    public function greaterThanDataProvider()
    {
        return [
            [Version::wip(1, 0, 0), Version::wip(0, 9, 9), true],
            [Version::wip(1, 0, 0), Version::wip(1, 0, 0), false],
            [Version::wip(1, 0, 0), Version::wip(1, 0, 1), false],
        ];
    }

    /**
     * @dataProvider greaterThanDataProvider
     */
    public function testGreaterThan($a, $b, $expected)
    {
        $this->assertSame($expected, $a->greaterThan($b));
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
        $releaseType = ReleaseType::MAJOR();
        $reflectionClass = new \ReflectionClass(AbstractValueMultiton::class);
        $reflectionProperty = $reflectionClass->getProperty('value');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($releaseType, 'test');

        Version::released(1, 2, 3)->increment($releaseType);
    }

    public function testToString()
    {
        $this->assertSame('1.2.3', Version::released(1, 2, 3)->toString());
        $this->assertSame('v1.2.3', Version::released(1, 2, 3)->toString('v'));
    }
}
