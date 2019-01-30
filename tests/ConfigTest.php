<?php

namespace Howyi\Cra;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    protected function setup()
    {
        $config = [
            'host' => 'github',
            'hooks' => [
                'onBeforeRelease' => 'hello',
            ]
        ];

        file_put_contents('testConfig.yml', \Symfony\Component\Yaml\Yaml::dump($config));
    }

    protected function tearDown()
    {
        unlink('testConfig.yml');
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
     * @doesNotPerformAssertions
     */
    public function testSet(): void
    {
        Config::set('testConfig.yml');
    }

    /**
     * @depends testSet
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config already loaded.
     */
    public function testSetWhenAlreadyLoaded(): void
    {
        Config::set('testConfig.yml');
        Config::set('testConfig.yml');
    }

    /**
     * @depends testSet
     */
    public function testGet(): void
    {
        self::assertSame('github', Config::host());
        self::assertSame('hello', Config::hooks('onBeforeRelease'));
    }
}