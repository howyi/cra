<?php declare(strict_types=1);

namespace Howyi\Cra\Core;

/**
 * Version
 */
class Version
{
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
     * @var string
     */
    private static $versionPrefix = '';

    /**
     * @var string
     */
    private static $releaseBranchPrefix = '';

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
     * バージョン文字列の接頭辞をセットする
     *
     * @param string $versionPrefix
     */
    public static function setVersionPrefix(string $versionPrefix): void
    {
        self::$versionPrefix = $versionPrefix;
    }

    /**
     * リリースブランチ文字列の接頭辞をセットする
     *
     * @param string $releaseBranchPrefix
     */
    public static function setReleaseBranchPrefix(string $releaseBranchPrefix): void
    {
        self::$releaseBranchPrefix = $releaseBranchPrefix;
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
     * @return string
     */
    public function toString(): string
    {
        return sprintf('%s%d.%d.%d', self::$versionPrefix, $this->major, $this->minor, $this->patch);
    }

    /**
     * このバージョンのリリースブランチ名を返す
     *
     * @return string
     */
    public function toReleaseBranchName(): string
    {
        return sprintf('%s%s', self::$releaseBranchPrefix, $this->toString());
    }
}
