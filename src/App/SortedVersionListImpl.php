<?php declare(strict_types=1);

namespace Sasamium\Cra\App;

use Sasamium\Cra\Core\Version;
use Sasamium\Cra\Core\SortedVersionList;
use Composer\Semver\Comparator;

/**
 * SortedVersionListの実装
 */
class SortedVersionListImpl extends SortedVersionList
{
    /**
     * {@inheritDoc}
     */
    protected function compare(Version $a, Version $b): int
    {
        $astr = $a->toString();
        $bstr = $b->toString();
        if (Comparator::equalTo($astr, $bstr)) {
            return 0;
        }
        return Comparator::greaterThan($astr, $bstr) ? 1 : -1;
    }
}
