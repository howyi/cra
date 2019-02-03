<?php declare(strict_types=1);

namespace Sasamium\Cra;

use Howyi\Evi;

/**
 * Config
 */
class Config
{
    /**
     * @var array|null
     */
    private static $config = null;

    /**
     * @param string $path
     * @throws \RuntimeException
     */
    final public static function set(string $path): void
    {
        if (!is_null(self::$config)) {
            throw new \RuntimeException('Config already loaded.');
        }
        self::$config = Evi::parse($path, true);
    }

    /**
     * @param string $key
     * @param array  $args
     * @return mixed
     * @throws \RuntimeException
     */
    final public static function __callStatic(string $key, array $args)
    {
        if (is_null(self::$config)) {
            throw new \RuntimeException('Config not loaded.');
        }
        $value = self::$config[$key];
        if (count($args) === 0) {
            return $value;
        }
        foreach ($args as $arg) {
            $value = $value[$arg];
        }
        return $value;
    }
}
