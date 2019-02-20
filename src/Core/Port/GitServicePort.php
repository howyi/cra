<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

/**
 * GitServicePort
 */
interface GitServicePort
{
    /**
     * @return string
     */
    public function name(): string;
    
    /**
     * @return array
     */
    public function defaultConfig(): array;
}
