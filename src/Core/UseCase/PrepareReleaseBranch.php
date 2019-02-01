<?php declare(strict_types=1);

namespace Howyi\Cra\Core\UseCase;

use Howyi\Cra\Core\Version;
use Howyi\Cra\Core\ReleaseType;
use Howyi\Cra\Core\Port\PrepareReleaseBranchPort;

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
    public function run(ReleaseType $releaseType): void
    {
        $latest = $this->port->listUpAllVersion()->released()->latest();
        if (is_null($latest)) {
            $latest = Version::initial();
        }

        $releaseVersion = $latest->increment($releaseType);
        $releaseBranch = $releaseVersion->toReleaseBranchName();

        if ($this->port->existsBranch($releaseBranch)) {
            return;
        }

        $this->port->checkoutBranch('master');
        $this->port->createBranchWithCheckout($releaseBranch);
    }
}
