<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

class SortedVersionListDummyImpl extends SortedVersionList
{
    protected function compare(Version $a, Version $b): int
    {
        // 実装宣言を満たすためのダミー実装
        // メジャーバージョンで比較させる
        return $a->major() <=> $b->major();
    }
}
