<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Cz\Git\IGit;
use Sasamium\Cra\App\SortedVersionListImpl;
use Sasamium\Cra\Core\Version;
use Mockery as M;
use PHPUnit\Framework\TestCase;

class PrepareReleaseBranchAdapterTest extends TestCase
{
    private $git;
    private $subject;

    public function setup()
    {
        Version::setReleaseBranchPrefix('release/');
        Version::setVersionPrefix('v');

        $this->git = M::mock(IGit::class);
        $this->subject = new PrepareReleaseBranchAdapter($this->git, 'release/');
    }

    public function teardown()
    {
        M::close();
    }

    public function tagsAndBranchesPatternDataProvider()
    {
        Version::setReleaseBranchPrefix('release/');
        Version::setVersionPrefix('v');

        return [
            [
                'tags' => null,
                'branches' => null,
                'expected' => new SortedVersionListImpl(),
            ],
            [
                'tags' => ['v1.0.0', 'v1.0.1', 'v1.0.2', 'v1.1.0', 'test'],
                'branches' => null,
                'expected' => new SortedVersionListImpl(
                    Version::released(1, 0, 0),
                    Version::released(1, 0, 1),
                    Version::released(1, 0, 2),
                    Version::released(1, 1, 0)
                ),
            ],
            [
                'tags' => null,
                'branches' => ['release/v1.1.1', 'release/v2.0.0', 'wip'],
                'expected' => new SortedVersionListImpl(
                    Version::wip(1, 1, 1),
                    Version::wip(2, 0, 0)
                ),
            ],
            [
                'tags' => ['v1.0.0', 'v1.0.1', 'v1.0.2', 'v1.1.0', 'test'],
                'branches' => ['release/v1.1.1', 'release/v2.0.0', 'wip'],
                'expected' => new SortedVersionListImpl(
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

    public function branchesPatternDataProvider()
    {
        return [
            [
                'branches' => null,
                'givenName' => 'foo',
                'expected' => false,
            ],
            [
                'branches' => ['bar'],
                'givenName' => 'foo',
                'expected' => false,
            ],
            [
                'branches' => ['foobar'],
                'givenName' => 'foo',
                'expected' => false,
            ],
            [
                'branches' => ['foo'],
                'givenName' => 'foo',
                'expected' => true,
            ],
            [
                'branches' => ['foo', 'foobar'],
                'givenName' => 'foo',
                'expected' => true,
            ],
        ];
    }

    /**
     * @dataProvider branchesPatternDataProvider
     */
    public function testExistsBranch($branches, $givenName, $expected)
    {
        $this->git->shouldReceive('getLocalBranches')
            ->once()
            ->withNoArgs()
            ->andReturn($branches);

        $actual = $this->subject->existsBranch($givenName);
        $this->assertSame($expected, $actual);
    }

    public function testCheckoutBranch()
    {
        $this->git->shouldReceive('checkout')
            ->once()
            ->with($name = 'master')
            ->andReturnNull();

        $this->assertNull($this->subject->checkoutBranch($name));
    }

    public function testCreateBranchWithCheckout()
    {
        $this->git->shouldReceive('createBranch')
            ->once()
            ->with($name = 'master', true)
            ->andReturnNull();

        $this->assertNull($this->subject->createBranchWithCheckout($name));
    }
}
