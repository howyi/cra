<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

/**
 * VersionParser
 */
class SemverParser
{
    /**
     * @var string
     */
    private const SEMVER_REGEX = '/^v?(\d+)\.(\d+)\.(\d+)/';

    /**
     * パース可能なバージョン文字列かを返す
     *
     * @param string $str
     * @return bool
     */
    public static function isParsable(string $str): bool
    {
        return self::parse($str) !== false;
    }

    /**
     * 文字列をパースし、正当であれば各セグメントを返す
     *
     * @param string $str
     * @return bool|array ['major' => int, 'minor' => int, 'patch' => int]
     */
    public static function parse(string $str)
    {
        $m = [];
        $result = preg_match(self::SEMVER_REGEX, $str, $m);
        if ($result !== 1 || count($m) !== 4) {
            return false;
        }
        return ['major' => (int) $m[1], 'minor' => (int) $m[2], 'patch' => (int) $m[3]];
    }
}
