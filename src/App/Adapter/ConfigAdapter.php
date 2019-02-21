<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Howyi\Evi;
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

    /**
     * @param string $path
     * @throws \RuntimeException
     */
    public function __construct(
        string $path
    ) {
        if (file_exists($path) === false) {
            throw new \RuntimeException('File not exists: ' . $path);
        }
        $this->config = Evi::parse($path, true);
    }

    /**
     * @return string
     */
    public function masterBranch(): string
    {
        return $this->config['masterBranch'];
    }

    /**
     * @return string
     */
    public function versionPrefix(): string
    {
        return $this->config['versionPrefix'];
    }

    /**
     * @return string
     */
    public function releaseBranchPrefix(): string
    {
        return $this->config['releaseBranchPrefix'];
    }
}
