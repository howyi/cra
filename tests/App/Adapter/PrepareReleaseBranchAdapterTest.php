<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Cz\Git\IGit;
use Mockery as M;
use PHPUnit\Framework\TestCase;
use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\SortedVersionList;
use Sasamium\Cra\Core\Version;

class PrepareReleaseBranchAdapterTest extends TestCase
{
    private $git;
    private $releaseBranchPrefix;
    private $versionPrefix;
    private $masterBranch;
    private $subject;

    public function setup()
    {
        $this->git = M::mock(IGit::class);
        $this->releaseBranchPrefix = 'release/';
        $this->versionPrefix = 'v';
        $this->masterBranch = 'master';
        $this->subject = new PrepareReleaseBranchAdapter(
            $this->git,
            $this->releaseBranchPrefix,
            $this->versionPrefix,
            $this->masterBranch
        );
    }

    public function teardown()
    {
        M::close();
    }

    public function tagsAndBranchesPatternDataProvider()
    {
        return [
            [
                'tags' => null,
                'branches' => null,
                'expected' => new SortedVersionList(),
            ],
            [
                'tags' => ['v1.0.0', 'v1.0.1', 'v1.0.2', 'v1.1.0', 'test'],
                'branches' => null,
                'expected' => new SortedVersionList(
                    Version::released(1, 0, 0),
                    Version::released(1, 0, 1),
                    Version::released(1, 0, 2),
                    Version::released(1, 1, 0)
                ),
            ],
            [
                'tags' => null,
                'branches' => ['release/v1.1.1', 'release/v2.0.0', 'wip'],
                'expected' => new SortedVersionList(
                    Version::wip(1, 1, 1),
                    Version::wip(2, 0, 0)
                ),
            ],
            [
                'tags' => ['v1.0.0', 'v1.0.1', 'v1.0.2', 'v1.1.0', 'test'],
                'branches' => ['release/v1.1.1', 'release/v2.0.0', 'wip'],
                'expected' => new SortedVersionList(
                    Version::released(1, 0, 0),
                    Version::released(1, 0, 1),
                    Version::released(1, 0, 2),
                    Version::released(1, 1, 0),
                    Version::wip(1, 1, 1),
                    Version::wip(2, 0, 0)
                ),
            ],
        ];
    }

    /**
     * @dataProvider tagsAndBranchesPatternDataProvider
     */
    public function testListUpAllVersion($tags, $branches, $expected)
    {
        $this->git->shouldReceive('getTags')
            ->once()
            ->withNoArgs()
            ->andReturn($tags);

        $this->git->shouldReceive('getLocalBranches')
            ->once()
            ->withNoArgs()
            ->andReturn($branches);

        $actual = $this->subject->listUpAllVersion();
        $this->assertEquals($expected, $actual);
    }

    public function testCheckoutBranch()
    {
        $branch = ReleaseBranch::of(Version::wip(0, 1, 0));
        $expectedBranchName = $branch->toString($this->releaseBranchPrefix, $this->versionPrefix);

        $this->git->shouldReceive('checkout')
            ->once()
            ->with($this->masterBranch)
            ->andReturnNull();

        $this->git->shouldReceive('createBranch')
            ->once()
            ->with($expectedBranchName, true)
            ->andReturnNull();

        $this->assertNull($this->subject->checkoutBranch($branch));
    }
}
