<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

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
     * ブランチがすでに存在するかを返す
     *
     * @param string $name
     * @return bool
     */
    public function existsBranch(string $name): bool;

    /**
     * ブランチへチェックアウトする
     *
     * @param string $name
     */
    public function checkoutBranch(string $name): void;

    /**
     * ブランチを作成してチェックアウトする
     *
     * @param string $name
     */
    public function createBranchWithCheckout(string $name): void;
}
