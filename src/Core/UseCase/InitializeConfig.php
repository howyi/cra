<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Sasamium\Cra\Core\Port\InitializeConfigPort;
use Sasamium\Cra\Core\Port\PrepareReleaseBranchPort;

/**
 * Configを新規作成する
 */
class InitializeConfig
{
    /**
     * @var PrepareReleaseBranchPort
     */
    private $port;

    /**
     * @param InitializeConfigPort $port
     */
    public function __construct(InitializeConfigPort $port)
    {
        $this->port = $port;
    }

    /**
     * @param string $configPath
     * @throws \RuntimeException
     */
    public function run(string $configPath): void
    {
        if ($this->port->exists($configPath)) {
            throw new \RuntimeException('File already exists: ' . $configPath);
        }

        $config = [];

        $gitService = $this->port->questionGitService();
        $config['service'][$gitService->value()] = $gitService->defaultConfig();

        // TODO: その他

        $this->port->put($configPath, $config);
    }
}
