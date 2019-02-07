<?php declare(strict_types=1);

namespace Sasamium\Cra\App;

use Eloquent\Enumeration\AbstractEnumeration;
use Sasamium\Cra\App\Adapter\GithubAdapter;
use Sasamium\Cra\App\Adapter\GitlabAdapter;
use Sasamium\Cra\Core\Port\GitServicePort;

/**
 * GitServiceType
 *
 * @method static $this GITHUB()
 * @method static $this GITLAB()
 */
final class GitService extends AbstractEnumeration
{
    /**
     * @var string
     */
    public const GITHUB = 'github';

    /**
     * @var string
     */
    public const GITLAB = 'gitlab';

    /**
     * @var array
     */
    private const DEFAULT_CONFIG_MAP = [
        self::GITHUB => [
            'TOKEN' => 'env:GITHUB_TOKEN',
        ],
        self::GITLAB => [
            'TOKEN' => 'env:GITLAB_TOKEN',
        ],
    ];

    /**
     * @var string[]
     */
    private const ADAPTER_MAP = [
        self::GITHUB => GithubAdapter::class,
        self::GITLAB => GitlabAdapter::class,
    ];

    /**
     * @return array
     */
    public function defaultConfig(): array
    {
        return self::DEFAULT_CONFIG_MAP[$this->value()];
    }

    /**
     * @return GitServicePort
     */
    public function gitServiceAdapter(): GitServicePort
    {
        $className = self::ADAPTER_MAP[$this->value()];
        return new $className();
    }
}
