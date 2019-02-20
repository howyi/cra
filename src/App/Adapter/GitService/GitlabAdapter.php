<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter\GitService;

use Sasamium\Cra\Core\Port\GitServicePort;

/**
 * GitServicePortのGitLab向け実装
 */
class GitlabAdapter implements GitServicePort
{
    /**
     * @return string
     */
    public function name(): string
    {
        // TODO
        return 'gitlab';
    }

    /**
     * @return array
     */
    public function defaultConfig(): array
    {
        // TODO
        return [
            'TOKEN' => 'env:GITLAB_TOKEN',
        ];
    }
}
