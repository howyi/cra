<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

/**
 * DefaultConfigPort
 */
interface DefaultConfigPort
{
    /**
     * @return string
     */
    public function masterBranch(): string;

    /**
     * @return string
     */
    public function versionPrefix(): string;

    /**
     * @return string
     */
    public function releaseBranchPrefix(): string;

    /**
     * @return string
     */
    public function featureBranchPrefix(): string;

    /**
     * @return array
     */
    public function gitServices(): array;

    /**
     * @return array
     */
    public function hooks(): array;
}
