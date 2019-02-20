<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

/**
 * StoragePort
 */
interface StoragePort
{
    /**
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;
    
    /**
     * @param string $key
     * @param array  $config
     */
    public function putFromArray(string $key, array $config): void;
}
