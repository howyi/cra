<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter\Storage;

use Sasamium\Cra\Core\Port\StoragePort;
use Symfony\Component\Yaml\Yaml;

/**
 * StoragePortのFilesystem実装
 */
class FilesystemAdapter implements StoragePort
{
    /**
     * {@inheritdoc}
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function putFromArray(string $path, array $content): void
    {
        $this->put($path, Yaml::dump($content));
    }

    /**
     * @param string $path
     * @param string $content
     */
    private function put(string $path, string $content): void
    {
        file_put_contents($path, $content);
    }
}
