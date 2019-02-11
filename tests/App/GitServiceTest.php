<?php declare(strict_types=1);

namespace Sasamium\Cra\App;

use PHPUnit\Framework\TestCase;
use Sasamium\Cra\Core\Port\GitServicePort;

class GitServiceTest extends TestCase
{
    /**
     * @param \Sasamium\Cra\App\GitService $gitService
     * @dataProvider gitServiceDataProvider
     */
    public function testDefaultConfig(GitService $gitService)
    {
        self::assertInternalType('array', $gitService->defaultConfig());
    }

    /**
     * @param \Sasamium\Cra\App\GitService $gitService
     * @dataProvider gitServiceDataProvider
     */
    public function testGitServiceAdapter(GitService $gitService)
    {
        self::assertInstanceOf(GitServicePort::class, $gitService->gitServiceAdapter());
    }

    public function gitServiceDataProvider()
    {
        return [GitService::members()];
    }
}
