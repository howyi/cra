<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Howyi\Evi;
use Sasamium\Cra\App\Adapter\GitService\GithubAdapter;
use Sasamium\Cra\App\Adapter\GitService\GitlabAdapter;
use Sasamium\Cra\Core\Port\ConfigPort;

/**
 * ConfigAdapterの実装
 */
class ConfigAdapter implements ConfigPort
{
    /**
     * @var string
     */
    public const DEFAULT_PATH = '.cra.yml';

    /**
     * @var array|null
     */
    private $config = null;

    public function __construct()
    {
        $this->config = null;
    }

    /**
     * @param string $path
     * @throws \RuntimeException
     */
    public function set(string $path): void
    {
        if (!is_null($this->config)) {
            throw new \RuntimeException('Config already loaded.');
        }
        $this->config = Evi::parse($path, true);
    }

    /**
     * @param string $key
     * @return string
     * @throws \RuntimeException
     */
    private function loadString(string $key): string
    {
        if (is_null($this->config)) {
            throw new \RuntimeException('Config not loaded.');
        }
        return $this->config[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function masterBranch(): string
    {
        return $this->loadString('masterBranch');
    }

    /**
     * {@inheritdoc}
     */
    public function versionPrefix(): string
    {
        return $this->loadString('versionPrefix');
    }

    /**
     * {@inheritdoc}
     */
    public function releaseBranchPrefix(): string
    {
        return $this->loadString('releaseBranchPrefix');
    }

    /**
     * {@inheritdoc}
     */
    public function supportedGitServicePorts(): array
    {
        return [
            new GithubAdapter(),
            new GitlabAdapter(),
        ];
    }
}
