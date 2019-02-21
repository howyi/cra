<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Sasamium\Cra\Core\Port\QuestionPort;
use Sasamium\Cra\Core\Port\StoragePort;

class InitializeConfigTest extends TestCase
{
    /**
     * @var M\MockInterface
     */
    private $storage;

    /**
     * @var M\MockInterface
     */
    private $question;

    /**
     * @var InitializeConfig
     */
    private $useCase;

    public function setup()
    {
        $this->storage = M::mock(StoragePort::class);
        $this->question = M::mock(QuestionPort::class);
        $this->useCase = new InitializeConfig(
            $this->storage,
            $this->question
        );
    }

    public function teardown()
    {
        /** @see https://github.com/mockery/mockery/issues/376 */
        if ($container = M::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        M::close();
    }

    public function testCreateConfig()
    {
        $configPath = '/';
        $this->storage->shouldReceive('exists')
            ->once()
            ->with($configPath)
            ->andReturn(false);

        $gitServiceDefaultConfig = [
            'github' => [
                'TOKEN' => 'env:GITHUB_TOKEN',
            ],
            'gitlab' => [
                'TOKEN' => 'env:GITLAB_TOKEN',
            ],
        ];

        $this->question->shouldReceive('select')
            ->once()
            ->with(
                'Please select Git Service.',
                array_keys($gitServiceDefaultConfig)
            )
            ->andReturn($answer = 'github');

        $config['git_service'] = [
            'name'    => $answer,
            'setting' => $gitServiceDefaultConfig[$answer],
        ];

        $this->storage->shouldReceive('putFromArray')
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
        $this->storage->shouldReceive('exists')
            ->once()
            ->with($configPath)
            ->andReturn(true);

        $this->useCase->run($configPath);
    }
}
