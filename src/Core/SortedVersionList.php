<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

/**
 * ソート済みバージョンリスト
 */
class SortedVersionList implements \IteratorAggregate
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
        usort($versions, function (Version $a, Version $b): int {
            return $a->equals($b) ? 0 : $a->greaterThan($b) ? 1 : -1;
        });
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
     * リスト内の最新バージョンを返す
     *
     * リストが空の場合は渡されたバージョンを返す
     *
     * @param Version $else
     * @return Version
     */
    public function latestOrElse(Version $else): Version
    {
        return $this->latest() ?? $else;
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
}
