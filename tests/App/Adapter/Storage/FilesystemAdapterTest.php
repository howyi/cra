<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use PHPUnit\Framework\TestCase;
use Sasamium\Cra\App\Adapter\Storage\FilesystemAdapter;

class FilesystemAdapterTest extends TestCase
{
    /**
     * @var FilesystemAdapter
     */
    private $adapter;

    /**
     * @var string
     */
    private const FILE_NAME = 'testConfig.yml';

    /**
     * @var array
     */
    private const CONFIG = [
        'masterBranch' => 'github',
        'versionPrefix' => 'v',
        'releaseBranchPrefix' => 'release',
    ];

    public function setup()
    {
        $this->adapter = new FilesystemAdapter();
    }

    protected function tearDown()
    {
        unlink(self::FILE_NAME);
    }

    public function testPut(): void
    {
        $this->adapter->putFromArray(
            self::FILE_NAME,
            self::CONFIG
        );
        self::assertTrue($this->adapter->exists(self::FILE_NAME));
        self::assertFalse($this->adapter->exists('./notExists.yml'));
    }
}
