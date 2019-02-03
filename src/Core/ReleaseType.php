<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * ReleaseType
 */
final class ReleaseType extends AbstractEnumeration
{
    /**
     * @var string
     */
    public const MAJOR = 'major';

    /**
     * @var string
     */
    public const MINOR = 'minor';

    /**
     * @var string
     */
    public const PATCH = 'patch';
}
