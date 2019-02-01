<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

/**
 * ReleaseType
 */
final class ReleaseType
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

    /**
     * @var string[]
     */
    public const VALUES = [
        self::MAJOR,
        self::MINOR,
        self::PATCH,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return ReleaseType
     */
    public static function MAJOR(): ReleaseType
    {
        return new self(self::MAJOR);
    }

    /**
     * @return ReleaseType
     */
    public static function MINOR(): ReleaseType
    {
        return new self(self::MINOR);
    }

    /**
     * @return ReleaseType
     */
    public static function PATCH(): ReleaseType
    {
        return new self(self::PATCH);
    }

    /**
     * @param string $value
     * @throws \InvalidArgumentException
     * @return ReleaseType
     */
    public static function of(string $value): ReleaseType
    {
        if (in_array($value, self::VALUES, true) === false) {
            throw new \InvalidArgumentException();
        }
        return new self($value);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
