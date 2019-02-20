<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter\GitService;

use Sasamium\Cra\Core\Port\GitServicePort;

/**
 * GitServicePortのGitHub向け実装
 */
class GithubAdapter implements GitServicePort
{
    /**
     * @return string
     */
    public function name(): string
    {
        // TODO
        return 'github';
    }

    /**
     * @return array
     */
    public function defaultConfig(): array
    {
        return [
            'TOKEN' => 'env:GITHUB_TOKEN',
        ];
    }
}
