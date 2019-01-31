<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

use Composer\Semver\Comparator;

/**
 * VersionAscIterator
 */
class VersionAscIterator implements \IteratorAggregate
{
    /**
     * @var Version[]
     */
    private $versions;

    /**
     * @param Version[] $versions
     */
    public function __construct(Version ...$versions)
    {
        $compare = function (Version $x, Version $y): int {
            $xstr = $x->toString();
            $ystr = $y->toString();
            if (Comparator::equalTo($xstr, $ystr)) {
                return 0;
            }
            return Comparator::greaterThan($xstr, $ystr) ? 1 : -1;
        };
        usort($versions, $compare);
        $this->versions = $versions;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->versions as $version) {
            yield $version;
        }
    }
}
