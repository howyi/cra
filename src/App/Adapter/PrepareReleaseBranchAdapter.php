<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Cz\Git\IGit;
use Sasamium\Cra\App\SortedVersionListImpl;
use Sasamium\Cra\Core\Port\PrepareReleaseBranchPort;
use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\SortedVersionList;
use Sasamium\Cra\Core\Version;

/**
 * PrepareReleaseBranchPortの実装
 */
class PrepareReleaseBranchAdapter implements PrepareReleaseBranchPort
{
    /**
     * @var IGit
     */
    private $git;

    /**
     * @var string
     */
    private $releaseBranchPrefix;

    /**
     * @var string
     */
    private $versionPrefix;

    /**
     * @var string
     */
    private $masterBranch;

    /**
     * @param IGit   $git
     * @param string $releaseBranchPrefix
     * @param string $versionPrefix
     * @param string $masterBranch
     */
    public function __construct(
        IGit $git,
        string $releaseBranchPrefix,
        string $versionPrefix,
        string $masterBranch
    ) {
        $this->git = $git;
        $this->releaseBranchPrefix = $releaseBranchPrefix;
        $this->versionPrefix = $versionPrefix;
        $this->masterBranch = $masterBranch;
    }

    /**
     * {@inheritDoc}
     */
    public function listUpAllVersion(): SortedVersionList
    {
        $releasedVersions = [];
        foreach ($this->git->getTags() ?? [] as $tag) {
            if (Version::isValidString($tag) === false) {
                continue;
            }
            $releasedVersions[] = Version::releasedFromString($tag);
        }

        $wipVersions = [];
        foreach ($this->git->getLocalBranches() ?? [] as $branch) {
            $prefixDeleted = preg_replace("[^{$this->releaseBranchPrefix}]", '', $branch);
            if (Version::isValidString($prefixDeleted) === false) {
                continue;
            }
            $wipVersions[] = Version::wipFromString($prefixDeleted);
        }

        return new SortedVersionList(...$releasedVersions, ...$wipVersions);
    }

    /**
     * {@inheritDoc}
     */
    public function checkoutBranch(ReleaseBranch $branch): void
    {
        $this->git->checkout($this->masterBranch);
        $this->git->createBranch(
            $branch->toString($this->releaseBranchPrefix, $this->versionPrefix),
            /* afterCheckout = */ true
        );
    }
}
