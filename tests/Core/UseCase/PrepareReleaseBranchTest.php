<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Sasamium\Cra\Core\Port\PrepareReleaseBranchPort;
use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\ReleaseType;
use Sasamium\Cra\Core\SortedVersionList;
use Sasamium\Cra\Core\Version;

class PrepareReleaseBranchTest extends TestCase
{
    private $port;
    private $useCase;

    public function setup()
    {
        $this->port = M::mock(PrepareReleaseBranchPort::class);
        $this->useCase = new PrepareReleaseBranch($this->port);
    }

    public function teardown()
    {
        M::close();
    }

    public function prepareBranchByReleaseTypePatternDataProvider()
    {
        $releaseType = ReleaseType::MAJOR();
        return [
            'リリース済みバージョンが存在する' => [
                new SortedVersionList(Version::released(1, 0, 0)),
                $releaseType,
                ReleaseBranch::of(Version::released(1, 0, 0)->increment($releaseType)),
            ],
            'リリース済みバージョンが存在しない' => [
                new SortedVersionList(),
                $releaseType,
                ReleaseBranch::of(Version::initial()->increment($releaseType)),
            ],
        ];
    }

    /**
     * @dataProvider prepareBranchByReleaseTypePatternDataProvider
     */
    public function testPrepareBranchByReleaseType(
        SortedVersionList $versions,
        ReleaseType $releaseType,
        ReleaseBranch $expectedReleaseBranch
    ) {
        $this->port->shouldReceive('listUpAllVersion')
            ->once()
            ->withNoArgs()
            ->andReturn($versions);

        $this->port->shouldReceive('checkoutBranch')
            ->once()
            ->with(M::on(function ($given) use ($expectedReleaseBranch) {
                return $expectedReleaseBranch->equals($given);
            }))
            ->andReturnNull();

        $this->assertNull($this->useCase->byReleaseType($releaseType));
    }

    public function testPrepareBranchByVersion()
    {
        $version = Version::wip(1, 0, 0);
        $expectedReleaseBranch = ReleaseBranch::of($version);

        $this->port->shouldReceive('checkoutBranch')
            ->once()
            ->with(M::on(function ($given) use ($expectedReleaseBranch) {
                return $expectedReleaseBranch->equals($given);
            }))
            ->andReturnNull();

        $this->assertNull($this->useCase->byVersion($version));
    }
}
