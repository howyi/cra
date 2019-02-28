<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Sasamium\Cra\Core\Port\ConfigPort;
use Sasamium\Cra\Core\Port\GitPort;
use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\ReleaseType;
use Sasamium\Cra\Core\SortedVersionList;
use Sasamium\Cra\Core\Version;

/**
 * リリースブランチを用意する
 */
class PrepareReleaseBranch
{
    /**
     * @var GitPort
     */
    private $git;

    /**
     * @var ConfigPort
     */
    private $config;

    /**
     * @param GitPort    $git
     * @param ConfigPort $config
     */
    public function __construct(GitPort $git, ConfigPort $config)
    {
        $this->git = $git;
        $this->config = $config;
    }

    /**
     * @param ReleaseType $releaseType
     */
    public function byReleaseType(ReleaseType $releaseType): void
    {
        $releasedVersions = [];
        foreach ($this->git->listUpTags() as $tag) {
            if (Version::isValidString($tag) === false) {
                continue;
            }
            $releasedVersions[] = Version::releasedFromString($tag);
        }

        $latestVersion = (new SortedVersionList(...$releasedVersions))->latestOrElse(Version::initial());
        $releaseVersion = $latestVersion->increment($releaseType);

        $this->byVersion($releaseVersion);
    }

    /**
     * @param Version $version
     */
    public function byVersion(Version $version): void
    {
        $this->git->checkout($this->config->masterBranch());
        $this->git->createBranch(ReleaseBranch::of($version)->toString(
            $this->config->releaseBranchPrefix(),
            $this->config->versionPrefix()
        ));
    }
}
