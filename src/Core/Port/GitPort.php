<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

/**
 * GitPort
 */
interface GitPort
{
    /**
     * すべてのタグを返す
     *
     * @return string[]
     */
    public function listUpTags(): array;

    /**
     * ブランチ・コミット・タグにチェックアウトする
     *
     * @param string $name
     */
    public function checkout(string $name): void;

    /**
     * ブランチを作成する
     *
     * @param string $name
     */
    public function createBranch(string $name): void;
}
