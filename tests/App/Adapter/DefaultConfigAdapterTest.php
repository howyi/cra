<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class DefaultConfigAdapterTest extends TestCase
{
    public function testGet(): void
    {
        $port = new DefaultConfigAdapter();
        self::assertSame('master', $port->masterBranch());
        self::assertSame('v', $port->versionPrefix());
        self::assertSame('release', $port->releaseBranchPrefix());
        self::assertSame('feature', $port->featureBranchPrefix());

        self::assertSame([
            'github' => [
                'TOKEN' => 'env:GITHUB_TOKEN',
            ],
            'gitlab' => [
                'TOKEN' => 'env:GITLAB_TOKEN',
            ],
        ], $port->gitServices());

        self::assertSame([
            'release' => [
                'before' => [
                    './vendor/bin/phpunit',
                ],
                'after' => [
                    './bin/cra create-github-release ${VERSION} CHANGELOG_${VERSION}.txt',
                    './bin/cra post-to-release-channel ${VERSION} \"${VERSION}リリースブランチをマージしました\"',
                ]
            ]
        ], $port->hooks());
    }
}
