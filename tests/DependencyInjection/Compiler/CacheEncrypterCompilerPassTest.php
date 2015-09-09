<?php
namespace Jeskew\Cache\DependencyInjection\Compiler;

use Symfony\Component\Filesystem\Filesystem;

class CacheEncrypterCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider configFormatProvider
     *
     * @param $format
     */
    public function testCanBuildContainer($format)
    {
        $this->purgeCache();
        $app = new \AppKernel("test_$format", true, $format);
        $app->boot();
        $container = $app->getContainer();
        $cacheServices = [
            'cache.encrypted',
            'my_encrypted_cache',
            'my_pki_encrypted_cache',
        ];

        foreach ($cacheServices as $cacheService) {
            $this->assertTrue($container->has($cacheService));
            $this->assertInstanceOf(
                'Jeskew\\Cache\\EncryptingCacheDecorator',
                $container->get($cacheService)
            );
        }
    }

    public function configFormatProvider()
    {
        return [
            ['yml'],
            ['xml'],
            ['php'],
        ];
    }

    private function purgeCache()
    {
        (new Filesystem)
            ->remove(array_map(function (array $args) {
                return dirname(dirname(__DIR__))
                . "/fixtures/cache/test_{$args[0]}";
            }, $this->configFormatProvider()));
    }

    public function testExtensionShouldEscapeStringsThatBeginWithAtSign()
    {
        $pass = new CacheEncrypterCompilerPass;
        $reflPass = new \ReflectionClass('Jeskew\\Cache\\DependencyInjection\\'
            . 'Compiler\\CacheEncrypterCompilerPass');
        $inflationMethod = $reflPass->getMethod('inflateServicesInTag');
        $inflationMethod->setAccessible(true);

        $config = ['foo' => [
            'bar' => '@@bar',
            'baz' => '@@baz'
        ]];
        $inflationMethod->invokeArgs($pass, [&$config]);

        $this->assertSame('@bar', $config['foo']['bar']);
        $this->assertSame('@baz', $config['foo']['baz']);
    }

    public function testExtensionShouldExpandServiceReferences()
    {
        $pass = new CacheEncrypterCompilerPass;
        $reflPass = new \ReflectionClass('Jeskew\\Cache\\DependencyInjection\\'
            . 'Compiler\\CacheEncrypterCompilerPass');
        $inflationMethod = $reflPass->getMethod('inflateServicesInTag');
        $inflationMethod->setAccessible(true);

        $config = ['foo' => [
            'bar' => '@bar',
            'baz' => '@baz'
        ]];
        $inflationMethod->invokeArgs($pass, [&$config]);

        foreach (['bar', 'baz'] as $ref) {
            $this->assertInstanceOf(
                'Symfony\Component\DependencyInjection\Reference',
                $config['foo'][$ref]
            );
            $this->assertSame($ref, (string) $config['foo'][$ref]);
        }
    }
}
