<?php
namespace Jeskew\Cache\DependencyInjection\Compiler;

use Symfony\Component\Filesystem\Filesystem;

class CacheEncrypterCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $fs = new Filesystem;

        $fs->remove(implode(DIRECTORY_SEPARATOR, [
            dirname(dirname(__DIR__)),
            'fixtures',
            'cache',
            'test',
        ]));
    }

    /**
     * @dataProvider configFormatProvider
     *
     * @param $configFormat
     */
    public function testCanBuildContainer($configFormat)
    {
        $app = new \AppKernel('test', true, $configFormat);

        $app->boot();

        echo 'booted';
    }

    public function configFormatProvider()
    {
        return [
            ['yml'],
            ['xml'],
            ['php'],
        ];
    }
}
