<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

use Sasamium\Cra\Core\Version;

/**
 * ソート済みバージョンリスト
 */
abstract class SortedVersionList implements \IteratorAggregate
{
    /**
     * @var Version[]
     */
    private $versions = [];

    /**
     * @param Version[] $versions
     */
    public function __construct(Version ...$versions)
    {
        usort($versions, [$this, 'compare']);
        $this->versions = $versions;
    }

    /**
     * バージョン番号の若い順から順番に返すイテレータを返す
     *
     * @return \Traversable<Version>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->versions as $version) {
            yield $version;
        }
    }

    /**
     * リスト内の最新バージョンを返す
     *
     * @return Version|null
     */
    public function latest(): ?Version
    {
        // array_popは参照渡しを要求するため、一旦変数に格納する
        $sorted = iterator_to_array($this->getIterator());
        return array_pop($sorted);
    }

    /**
     * リリース済みバージョンのみが格納された新しいリストを返す
     *
     * @return SortedVersionList
     */
    public function released(): SortedVersionList
    {
        return new static(...array_filter($this->versions, function (Version $version) {
            return $version->isReleased();
        }));
    }

    /**
     * 開発中バージョンのみが格納された新しいリストを返す
     *
     * @return SortedVersionList
     */
    public function wip(): SortedVersionList
    {
        return new static(...array_filter($this->versions, function (Version $version) {
            return $version->isWip();
        }));
    }

    /**
     * バージョン番号を比較する
     *
     * $a < $b -> -1
     * $a = $b ->  0
     * $a > $b ->  1
     *
     * @param Version $a
     * @param Version $b
     * @return int -1|0|1
     */
    abstract protected function compare(Version $a, Version $b): int;
}
