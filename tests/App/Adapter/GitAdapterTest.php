<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Cz\Git\IGit;
use Mockery as M;
use PHPUnit\Framework\TestCase;

class GitAdapterTest extends TestCase
{
    private $git;
    private $adapter;

    public function setup()
    {
        $this->git = M::mock(IGit::class);
        $this->adapter = new GitAdapter($this->git);
    }

    public function teardown()
    {
        M::close();
    }

    public function tagDataProvider()
    {
        return [
            [
                ['foo', 'bar'],
                ['foo', 'bar'],
            ],
            [
                null,
                [],
            ],
        ];
    }

    /**
     * @dataProvider tagDataProvider
     */
    public function testListUpTags($tags, $expected)
    {
        $this->git->shouldReceive('getTags')
            ->once()
            ->withNoArgs()
            ->andReturn($tags);

        $rtn = $this->adapter->listUpTags();
        $this->assertSame($expected, $rtn);
    }

    public function testCheckout()
    {
        $this->git->shouldReceive('checkout')
            ->once()
            ->with($name = 'master')
            ->andReturnNull();

        $this->assertNull($this->adapter->checkout($name));
    }

    public function testCreateBranch()
    {
        $this->git->shouldReceive('createBranch')
            ->once()
            ->with($name = 'develop', true)
            ->andReturnNull();

        $this->assertNull($this->adapter->createBranch($name));
    }
}
