<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

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
     * @param GitPort $git
     */
    public function __construct(GitPort $git)
    {
        $this->git = $git;
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
        // TODO: 844196 configAdapterを参照するようにする
        $this->git->checkout('master');
        $this->git->createBranch(ReleaseBranch::of($version)->toString('release', 'v'));
    }
}
