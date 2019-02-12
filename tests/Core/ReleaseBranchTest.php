<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

use PHPUnit\Framework\TestCase;

class ReleaseBranchTest extends TestCase
{
    public function testAll()
    {
        $branch123A = ReleaseBranch::of(Version::wip(1, 2, 3));
        $branch123B = ReleaseBranch::of(Version::wip(1, 2, 3));
        $branch124 = ReleaseBranch::of(Version::wip(1, 2, 4));

        $this->assertTrue($branch123A->equals($branch123B));
        $this->assertFalse($branch123A->equals($branch124));

        $this->assertSame('1.2.3', $branch123A->toString());
        $this->assertSame('v1.2.3', $branch123A->toString('', 'v'));
        $this->assertSame('release/1.2.3', $branch123A->toString('release'));
        $this->assertSame('release/v1.2.3', $branch123A->toString('release', 'v'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage リリース済みバージョン 2.0.0 のリリースブランチを生成しようとした
     */
    public function testInvalidVersionGiven()
    {
        ReleaseBranch::of(Version::released(2, 0, 0));
    }
}
