<?php declare(strict_types=1);

namespace Sasamium\Cra;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class ConfigTest extends TestCase
{
    /**
     * @var string
     */
    private const FILE_NAME = 'testConfig.yml';

    /**
     * @var array
     */
    private const CONFIG = [
        'host' => 'github',
        'hooks' => [
            'onBeforeRelease' => [
                'hello',
                'world',
            ],

        ]
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
     * @expectedExceptionMessage Config not loaded.
     */
    public function testGetWhenConfigNotLoaded(): void
    {
        Config::host();
    }

    /**
     * @depends testGetWhenConfigNotLoaded
     */
    public function testSet(): void
    {
        Config::set(self::FILE_NAME);
        self::assertSame(self::CONFIG['host'], Config::host());
    }

    /**
     * @depends testSet
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config already loaded.
     */
    public function testSetWhenAlreadyLoaded(): void
    {
        Config::set(self::FILE_NAME);
        Config::set(self::FILE_NAME);
    }

    /**
     * @depends testSet
     */
    public function testGet(): void
    {
        self::assertSame(self::CONFIG['host'], Config::host());
        self::assertSame(self::CONFIG['hooks'], Config::hooks());
        self::assertSame(self::CONFIG['hooks']['onBeforeRelease'], Config::hooks('onBeforeRelease'));
        self::assertSame(self::CONFIG['hooks']['onBeforeRelease'][0], Config::hooks('onBeforeRelease', 0));
    }
}
