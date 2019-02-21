<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class ConfigAdapterTest extends TestCase
{
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

    protected function setup()
    {
        file_put_contents(self::FILE_NAME, Yaml::dump(self::CONFIG));
    }

    protected function tearDown()
    {
        unlink(self::FILE_NAME);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage File not exists: ./notExistsConfig.yml
     */
    public function testGetWhenConfigNotExists(): void
    {
        new ConfigAdapter('./notExistsConfig.yml');
    }

    public function testGet(): void
    {
        $port = new ConfigAdapter(self::FILE_NAME);
        self::assertSame(self::CONFIG['masterBranch'], $port->masterBranch());
        self::assertSame(self::CONFIG['versionPrefix'], $port->versionPrefix());
        self::assertSame(self::CONFIG['releaseBranchPrefix'], $port->releaseBranchPrefix());
    }
}
