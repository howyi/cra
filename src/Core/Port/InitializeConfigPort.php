<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

use Sasamium\Cra\App\GitService;

/**
 * InitializeConfigPort
 */
interface InitializeConfigPort
{
    /**
     * @param string $configPath
     * @return bool
     */
    public function exists(string $configPath): bool;

    /**
     * @return GitService
     */
    public function questionGitService(): GitService;

    /**
     * @param string $configPath
     * @param array  $config
     */
    public function put(string $configPath, array $config): void;
}
