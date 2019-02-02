<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

use PHPUnit\Framework\TestCase;

class ReleaseTypeTest extends TestCase
{
    public function testFactory()
    {
        $this->assertEquals(ReleaseType::of(ReleaseType::MAJOR), ReleaseType::MAJOR());
        $this->assertEquals(ReleaseType::of(ReleaseType::MINOR), ReleaseType::MINOR());
        $this->assertEquals(ReleaseType::of(ReleaseType::PATCH), ReleaseType::PATCH());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOfGivenInvalidValue()
    {
        ReleaseType::of('ささみ梅');
    }

    public function testValue()
    {
        $this->assertEquals(ReleaseType::MAJOR, ReleaseType::MAJOR()->value());
        $this->assertEquals(ReleaseType::MINOR, ReleaseType::MINOR()->value());
        $this->assertEquals(ReleaseType::PATCH, ReleaseType::PATCH()->value());
    }
}
