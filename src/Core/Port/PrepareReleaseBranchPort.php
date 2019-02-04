<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\SortedVersionList;

/**
 * PrepareReleaseBranchPort
 */
interface PrepareReleaseBranchPort
{
    /**
     * リリース済み・開発中すべてのバージョンのリストを返す
     *
     * @return SortedVersionList
     */
    public function listUpAllVersion(): SortedVersionList;

    /**
     * リリースブランチをチェックアウトする
     *
     * @param ReleaseBranch $branch
     */
    public function checkoutBranch(ReleaseBranch $branch): void;
}
