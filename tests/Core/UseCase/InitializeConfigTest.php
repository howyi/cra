<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Sasamium\Cra\App\GitService;
use Sasamium\Cra\Core\Port\InitializeConfigPort;

class InitializeConfigTest extends TestCase
{
    /**
     * @var M\MockInterface
     */
    private $port;

    /**
     * @var InitializeConfig
     */
    private $useCase;

    public function setup()
    {
        $this->port = M::mock(InitializeConfigPort::class);
        $this->useCase = new InitializeConfig($this->port);
    }

    public function teardown()
    {
        /** @see https://github.com/mockery/mockery/issues/376 */
        if ($container = M::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        M::close();
    }

    public function createConfigDataProvider()
    {
        return [GitService::members()];
    }

    /**
     * @param GitService $gitService
     * @dataProvider createConfigDataProvider
     */
    public function testCreateConfig(GitService $gitService)
    {
        $configPath = '/';
        $this->port->shouldReceive('exists')
            ->once()
            ->with($configPath)
            ->andReturn(false);

        $this->port->shouldReceive('questionGitService')
            ->once()
            ->withNoArgs()
            ->andReturn($gitService);

        $config = [
            'service' => [
                $gitService->value() => $gitService->defaultConfig()
            ],
        ];

        $this->port->shouldReceive('put')
            ->once()
            ->with($configPath, $config)
            ->andReturnNull();

        $this->useCase->run($configPath);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage File already exists: /
     */
    public function testCreateConfigWhenFileAlreadyExists()
    {
        $configPath = '/';
        $this->port->shouldReceive('exists')
            ->once()
            ->with($configPath)
            ->andReturn(true);

        $this->useCase->run($configPath);
    }
}
