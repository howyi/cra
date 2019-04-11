<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Sasamium\Cra\Core\Port\DefaultConfigPort;

/**
 * DefaultConfigAdapterの実装
 */
class DefaultConfigAdapter implements DefaultConfigPort
{
    /**
     * @return string
     */
    public function masterBranch(): string
    {
        return 'master';
    }

    /**
     * @return string
     */
    public function versionPrefix(): string
    {
        return 'v';
    }

    /**
     * @return string
     */
    public function releaseBranchPrefix(): string
    {
        return 'release';
    }

    /**
     * @return string
     */
    public function featureBranchPrefix(): string
    {
        return 'feature';
    }

    /**
     * @return array
     */
    public function gitServices(): array
    {
        return [
            'github' => [
                'TOKEN' => 'env:GITHUB_TOKEN',
            ],
            'gitlab' => [
                'TOKEN' => 'env:GITLAB_TOKEN',
            ],
        ];
    }

    /**
     * @return array
     */
    public function hooks(): array
    {
        return [
            'release' => [
                'before' => [
                    './vendor/bin/phpunit',
                ],
                'after' => [
                    './bin/cra create-github-release ${VERSION} CHANGELOG_${VERSION}.txt',
                    './bin/cra post-to-release-channel ${VERSION} \"${VERSION}リリースブランチをマージしました\"',
                ]
            ]
        ];
    }
}
