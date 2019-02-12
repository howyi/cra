<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Sasamium\Cra\Core\Port\PrepareReleaseBranchPort;
use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\ReleaseType;
use Sasamium\Cra\Core\Version;

/**
 * リリースブランチを用意する
 */
class PrepareReleaseBranch
{
    /**
     * @var PrepareReleaseBranchPort
     */
    private $port;

    /**
     * @param PrepareReleaseBranchPort $port
     */
    public function __construct(PrepareReleaseBranchPort $port)
    {
        $this->port = $port;
    }

    /**
     * @param ReleaseType $releaseType
     */
    public function byReleaseType(ReleaseType $releaseType): void
    {
        $latest = $this->port->listUpAllVersion()
            ->released()
            ->latestOrElse(Version::initial());

        $releaseVersion = $latest->increment($releaseType);
        $releaseBranch = ReleaseBranch::of($releaseVersion);

        $this->port->checkoutBranch($releaseBranch);
    }

    /**
     * @param Version $version
     */
    public function byVersion(Version $version): void
    {
        $this->port->checkoutBranch(ReleaseBranch::of($version));
    }
}
