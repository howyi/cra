<?php declare(strict_types=1);

namespace Howyi\Cra\Core\UseCase;

use Howyi\Cra\Core\Port\PrepareReleaseBranchPort;
use Howyi\Cra\Core\ReleaseType;
use Howyi\Cra\Core\SortedVersionList;
use Howyi\Cra\Core\Version;
use Mockery as M;
use PHPUnit\Framework\TestCase;

class PrepareReleaseBranchTest extends TestCase
{
    private $port;
    private $useCase;

    public function setup()
    {
        Version::setReleaseBranchPrefix('release/');
        Version::setVersionPrefix('v');

        $this->port = M::mock(PrepareReleaseBranchPort::class);
        $this->useCase = new PrepareReleaseBranch($this->port);
    }

    public function teardown()
    {
        M::close();
    }

    public function createBranchPatternDataProvider()
    {
        Version::setReleaseBranchPrefix('release/');
        Version::setVersionPrefix('v');

        $releaseType = ReleaseType::MAJOR();
        return [
            'リリース済みバージョンが存在する' => [
                Version::released(1, 0, 0),
                $releaseType,
                Version::released(1, 0, 0)->increment($releaseType)->toReleaseBranchName(),
            ],
            'リリース済みバージョンが存在しない' => [
                null,
                $releaseType,
                Version::initial()->increment($releaseType)->toReleaseBranchName(),
            ],
        ];
    }

    /**
     * @dataProvider createBranchPatternDataProvider
     */
    public function testCreateBranch(
        ?Version $latestVersion,
        ReleaseType $releaseType,
        string $expectedReleaseBranchName
    ) {
        $this->port->shouldReceive('listUpAllVersion')
            ->once()
            ->withNoArgs()
            ->andReturn($list = M::mock(SortedVersionList::class));

        $list->shouldReceive('released->latest')
            ->once()
            ->andReturn($latestVersion);

        $this->port->shouldReceive('existsBranch')
            ->once()
            ->with($expectedReleaseBranchName)
            ->andReturn(false);

        $this->port->shouldReceive('checkoutBranch')
            ->once()
            ->with('master')
            ->andReturnNull();

        $this->port->shouldReceive('createBranchWithCheckout')
            ->once()
            ->with($expectedReleaseBranchName)
            ->andReturnNull();

        $this->assertNull($this->useCase->run($releaseType));
    }

    public function testNoCreateBranch()
    {
        $releaseType = ReleaseType::MINOR();

        $this->port->shouldReceive('listUpAllVersion')
            ->once()
            ->withNoArgs()
            ->andReturn($list = M::mock(SortedVersionList::class));

        $list->shouldReceive('released->latest')
            ->once()
            ->andReturn($latest = Version::released(1, 0, 0));

        $releaseBranchName = $latest
            ->increment($releaseType)
            ->toReleaseBranchName();

        $this->port->shouldReceive('existsBranch')
            ->once()
            ->with($releaseBranchName)
            ->andReturn(true);

        $this->assertNull($this->useCase->run($releaseType));
    }
}
