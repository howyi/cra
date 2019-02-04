<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

/**
 * ReleaseBranch
 */
class ReleaseBranch
{
    /**
     * @var Version
     */
    private $version;

    /**
     * @param Version $version
     */
    private function __construct(Version $version)
    {
        $this->version = $version;
    }

    /**
     * バージョンに対応するリリースブランチを生成して返す
     *
     * @param Version $version
     * @throws \InvalidArgumentException
     * @return ReleaseBranch
     */
    public static function of(Version $version): ReleaseBranch
    {
        if ($version->isReleased()) {
            throw new \InvalidArgumentException(sprintf(
                'リリース済みバージョン %s のリリースブランチを生成しようとした',
                $version->toString()
            ));
        }
        return new ReleaseBranch($version);
    }

    /**
     * 渡されたリリースブランチが自身と等しいかを返す
     *
     * @param ReleaseBranch $other
     * @return bool
     */
    public function equals(ReleaseBranch $other): bool
    {
        return $this->version->equals($other->version);
    }

    /**
     * このリリースブランチの文字列表現を返す
     *
     * @param string $branchPrefix
     * @param string $versionPrefix
     * @return string
     */
    public function toString(string $branchPrefix = '', string $versionPrefix = ''): string
    {
        return sprintf('%s%s', $branchPrefix, $this->version->toString($versionPrefix));
    }
}
