<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

use Composer\Semver\Comparator;

/**
 * Version
 */
class Version
{
    /**
     * @var string
     */
    private const SEMVER_PATTERN = '/^v?(\d+)\.(\d+)\.(\d+)/';

    /**
     * @var int
     */
    private $major;

    /**
     * @var int
     */
    private $minor;

    /**
     * @var int
     */
    private $patch;

    /**
     * @var bool
     */
    private $isReleased;

    /**
     * @param int  $major
     * @param int  $minor
     * @param int  $patch
     * @param bool $isReleased
     */
    private function __construct(int $major, int $minor, int $patch, bool $isReleased)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        $this->isReleased = $isReleased;
    }

    /**
     * 正当なバージョン文字列かを返す
     *
     * @param string $str
     * @return bool
     */
    public static function isValidString(string $str): bool
    {
        return count(self::parse($str)) > 0;
    }

    /**
     * 一番最初のバージョンを生成する
     *
     * @return Version
     */
    public static function initial(): Version
    {
        return new self(0, 0, 0, false);
    }

    /**
     * リリース済みバージョンを生成する
     *
     * @param int $major
     * @param int $minor
     * @param int $patch
     * @return Version
     */
    public static function released(int $major, int $minor, int $patch): Version
    {
        return new self($major, $minor, $patch, true);
    }

    /**
     * バージョン文字列からリリース済みバージョンを生成する
     *
     * @param string $str
     * @throws InvalidVersionStringException
     * @return Version
     */
    public static function releasedFromString(string $str): Version
    {
        $parsed = self::parse($str);
        if (count($parsed) === 0) {
            throw new InvalidVersionStringException(sprintf('given: %s', $str));
        }
        return self::released($parsed['major'], $parsed['minor'], $parsed['patch']);
    }

    /**
     * 開発中バージョンを生成する
     *
     * @param int $major
     * @param int $minor
     * @param int $patch
     * @return Version
     */
    public static function wip(int $major, int $minor, int $patch): Version
    {
        return new self($major, $minor, $patch, false);
    }

    /**
     * バージョン文字列から開発中バージョンを生成する
     *
     * @param string $str
     * @throws InvalidVersionStringException
     * @return Version
     */
    public static function wipFromString(string $str): Version
    {
        $parsed = self::parse($str);
        if (count($parsed) === 0) {
            throw new InvalidVersionStringException(sprintf('given: %s', $str));
        }
        return self::wip($parsed['major'], $parsed['minor'], $parsed['patch']);
    }

    /**
     * 文字列をパースし、正当であれば各セグメントを返す
     *
     * @param string $str
     * @return array ['major' => int, 'minor' => int, 'patch' => int]|[]
     */
    private static function parse(string $str): array
    {
        $m = [];
        $result = preg_match(self::SEMVER_PATTERN, $str, $m);
        if ($result !== 1) {
            return [];
        }
        return ['major' => (int) $m[1], 'minor' => (int) $m[2], 'patch' => (int) $m[3]];
    }

    /**
     * メジャーバージョン番号を返す
     *
     * @return int
     */
    public function major(): int
    {
        return $this->major;
    }

    /**
     * マイナーバージョン番号を返す
     *
     * @return int
     */
    public function minor(): int
    {
        return $this->minor;
    }

    /**
     * パッチバージョン番号を返す
     *
     * @return int
     */
    public function patch(): int
    {
        return $this->patch;
    }

    /**
     * このバージョンがリリース済みかを返す
     *
     * @return bool
     */
    public function isReleased(): bool
    {
        return $this->isReleased;
    }

    /**
     * このバージョンが開発中かを返す
     *
     * @return bool
     */
    public function isWip(): bool
    {
        return false === $this->isReleased;
    }

    /**
     * 渡されたバージョンが自身と等しいかを返す
     *
     * @param Version $other
     * @return bool
     */
    public function equals(Version $other): bool
    {
        if ($this->isReleased !== $other->isReleased) {
            return false;
        }
        return Comparator::equalTo($this->toString(), $other->toString());
    }

    /**
     * 自身が渡されたバージョンより新しいかを返す
     *
     * @param Version $other
     * @return bool
     */
    public function greaterThan(Version $other): bool
    {
        return Comparator::greaterThan($this->toString(), $other->toString());
    }

    /**
     * リリース種別に対応したバージョン番号をインクリメントした新しいバージョンを返す
     *
     * インクリメントされた新しいバージョンは常に「開発中」となる
     *
     * @param ReleaseType $releaseType
     * @throws \LogicException
     * @return Version
     */
    public function increment(ReleaseType $releaseType): Version
    {
        switch ($releaseType->value()) {
            case ReleaseType::MAJOR:
                return new Version($this->major + 1, 0, 0, false);
                break;
            case ReleaseType::MINOR:
                return new Version($this->major, $this->minor + 1, 0, false);
                break;
            case ReleaseType::PATCH:
                return new Version($this->major, $this->minor, $this->patch + 1, false);
                break;
            default:
                // 本来到達し得ない
                throw new \LogicException(sprintf('対応していないリリース種別を渡された: %s', $releaseType->value()));
                break;
        }
    }

    /**
     * このバージョンの文字列表現を返す
     *
     * @param string $prefix default: ''
     * @return string
     */
    public function toString(string $prefix = ''): string
    {
        return sprintf('%s%d.%d.%d', $prefix, $this->major, $this->minor, $this->patch);
    }
}
