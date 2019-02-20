<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter\GitService;

use Sasamium\Cra\Core\Port\GitServicePort;

/**
 * GitServicePortのGitLab向け実装
 */
class GitlabAdapter implements GitServicePort
{
    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'gitlab';
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfig(): array
    {
        return [
            'TOKEN' => 'env:GITLAB_TOKEN',
        ];
    }
}
