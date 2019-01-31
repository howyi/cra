<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

use MyCLabs\Enum\Enum;

/**
 * ReleaseType
 */
final class ReleaseType extends Enum
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
