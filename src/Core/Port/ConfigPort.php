<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

/**
 * ConfigPort
 */
interface ConfigPort
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
}
