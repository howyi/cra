<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter\GitService;

use Sasamium\Cra\Core\Port\GitServicePort;

/**
 * GitServicePortのGitHub向け実装
 */
class GithubAdapter implements GitServicePort
{
    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'github';
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfig(): array
    {
        return [
            'TOKEN' => 'env:GITHUB_TOKEN',
        ];
    }
}
