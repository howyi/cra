<?php declare(strict_types=1);

namespace Howyi\Cra\App\Adapter;

use Cz\Git\IGit;
use Howyi\Cra\App\SortedVersionListImpl;
use Howyi\Cra\Core\Port\PrepareReleaseBranchPort;
use Howyi\Cra\Core\SortedVersionList;
use Howyi\Cra\Core\Version;

/**
 * PrepareReleaseBranchPortの実装
 */
class PrepareReleaseBranchAdapter implements PrepareReleaseBranchPort
{
    /**
     * @var string
     */
    private const SEMVER_PATTERN = '/^v?(\d+)\.(\d+)\.(\d+)/';

    /**
     * @var IGit
     */
    private $git;

    /**
     * @var string
     */
    private $releaseBranchPrefix;

    /**
     * @param IGit $git
     */
    public function __construct(IGit $git, string $releaseBranchPrefix)
    {
        $this->git = $git;
        $this->releaseBranchPrefix = $releaseBranchPrefix;
    }

    /**
     * {@inheritDoc}
     */
    public function listUpAllVersion(): SortedVersionList
    {
        $releasedVersions = [];
        foreach ($this->git->getTags() ?? [] as $tag) {
            $version = $this->parseVersion($tag, [Version::class, 'released']);
            if (is_null($version) === false) {
                $releasedVersions[] = $version;
            }
        }

        $wipVersions = [];
        foreach ($this->git->getLocalBranches() ?? [] as $branch) {
            $version = $this->parseVersion(
                preg_replace("[^{$this->releaseBranchPrefix}]", '', $branch),
                [Version::class, 'wip']
            );
            if (is_null($version) === false) {
                $wipVersions[] = $version;
            }
        }

        return new SortedVersionListImpl(...$releasedVersions, ...$wipVersions);
    }

    /**
     * {@inheritDoc}
     */
    public function existsBranch(string $name): bool
    {
        return in_array($name, $this->git->getLocalBranches() ?? [], true);
    }

    /**
     * {@inheritDoc}
     */
    public function checkoutBranch(string $name): void
    {
        $this->git->checkout($name);
    }

    /**
     * {@inheritDoc}
     */
    public function createBranchWithCheckout(string $name): void
    {
        $this->git->createBranch($name, /* afterCheckout = */ true);
    }

    /**
     * 文字列をパースし、正当であればVersionインスタンスを生成して返す
     *
     * @param string   $subject
     * @param callable $versionFactory (int $major, int $minor, int $patch): Version
     * @return Version|null
     */
    private function parseVersion(string $subject, callable $versionFactory): ?Version
    {
        $m = [];
        $result = preg_match(self::SEMVER_PATTERN, $subject, $m);
        if ($result !== 1) {
            return null;
        }
        return $versionFactory((int) $m[1], (int) $m[2], (int) $m[3]);
    }
}
