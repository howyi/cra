<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Sasamium\Cra\Core\Port\GitPort;
use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\ReleaseType;
use Sasamium\Cra\Core\Version;

class PrepareReleaseBranchTest extends TestCase
{
    private $git;
    private $useCase;

    public function setup()
    {
        $this->git = M::mock(GitPort::class);
        $this->useCase = new PrepareReleaseBranch($this->git);
    }

    public function teardown()
    {
        M::close();
    }

    public function tagDataProvider()
    {
        return [
            [
                ['foo'],
                Version::wip(0, 1, 0),
            ],
            [
                ['foo', 'v1.0.0', 'v1.0.1'],
                Version::wip(1, 1, 0),
            ],
        ];
    }

    /**
     * @dataProvider tagDataProvider
     */
    public function testPrepareBranchByReleaseType(array $tags, Version $expectedReleaseVersion)
    {
        $releaseType = ReleaseType::MINOR();

        $this->git->shouldReceive('listUpTags')
            ->once()
            ->withNoArgs()
            ->andReturn($tags);

        $this->git->shouldReceive('checkout')
            ->once()
            ->with('master')
            ->andReturnNull();

        $expectedReleaseBranchName = ReleaseBranch::of($expectedReleaseVersion)->toString('release', 'v');
        $this->git->shouldReceive('createBranch')
            ->once()
            ->with($expectedReleaseBranchName)
            ->andReturnNull();

        $this->assertNull($this->useCase->byReleaseType($releaseType));
    }

    public function testPrepareBranchByVersion()
    {
        $version = Version::wip(1, 0, 0);
        $expectedReleaseBranchName = ReleaseBranch::of($version)->toString('release', 'v');

        $this->git->shouldReceive('checkout')
            ->once()
            ->with('master')
            ->andReturnNull();

        $this->git->shouldReceive('createBranch')
            ->once()
            ->with($expectedReleaseBranchName)
            ->andReturnNull();

        $this->assertNull($this->useCase->byVersion($version));
    }
}
