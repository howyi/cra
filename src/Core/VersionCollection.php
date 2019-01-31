<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

/**
 * VersionCollection
 */
class VersionCollection implements \IteratorAggregate
{
    /**
     * @var Version[]
     */
    private $container = [];

    /**
     * @param Version[] $container
     */
    public function __construct(Version ...$versions)
    {
        $this->container = $versions;
    }

    /**
     * コレクション同士を結合した新しいコレクションを返す
     *
     * @return VersionCollection
     */
    public static function concat(VersionCollection ...$collections): VersionCollection
    {
        $versions = [];
        foreach ($collections as $collection) {
            foreach ($collection->container as $version) {
                $versions[] = $version;
            }
        }
        return new self(...$versions);
    }

    /**
     * バージョンを格納する
     *
     * @param Version $version
     * @return self
     */
    public function push(Version $version): self
    {
        $this->container[] = $version;
        return $this;
    }

    /**
     * リリース済みバージョンのみが格納された新しいコレクションを返す
     *
     * @return VersionCollection
     */
    public function released(): VersionCollection
    {
        $isReleased = function (Version $x): bool {
            return $x->isReleased();
        };
        return new self(...array_filter($this->container, $isReleased));
    }

    /**
     * 開発中バージョンのみが格納された新しいコレクションを返す
     *
     * @return VersionCollection
     */
    public function wip(): VersionCollection
    {
        $isWip = function (Version $x): bool {
            return $x->isWip();
        };
        return new self(...array_filter($this->container, $isWip));
    }

    /**
     * 格納されているバージョンのうち、最新のものを返す
     *
     * 何も格納されていない場合はnullを返す
     *
     * @return Version|null
     */
    public function latest(): ?Version
    {
        // array_popは参照渡しを要求するため、一旦変数に格納する
        $ascSorted = $this->toAscSortedArray();
        return array_pop($ascSorted);
    }

    /**
     * 昇順ソート済み配列として返す
     *
     * @return Version[]
     */
    public function toAscSortedArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return new VersionAscIterator(...$this->container);
    }
}
