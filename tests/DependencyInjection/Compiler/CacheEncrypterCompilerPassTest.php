<?php
namespace Jsq\Cache\DependencyInjection\Compiler;

use Jsq\Cache\EnvelopeEncryption\Decorator as EnvelopeDecorator;
use Jsq\Cache\IronEncryption\Decorator as IronDecorator;
use Jsq\Cache\PasswordEncryption\Decorator as PasswordDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem;

class CacheEncrypterCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        (new Filesystem)
            ->remove(array_map(function (array $args) {
                return dirname(dirname(__DIR__))
                . "/fixtures/cache/test_{$args[0]}";
            }, self::configFormatProvider()));
    }

    /**
     * @dataProvider configFormatProvider
     *
     * @param $format
     */
    public function testCanBuildContainer($format)
    {
        $app = new \AppKernel("test_$format", true, $format);
        $app->boot();
        $container = $app->getContainer();
        $cacheServices = [
            'my_cache.encrypted' => PasswordDecorator::class,
            'my_encrypted_cache' => PasswordDecorator::class,
            'my_pki_encrypted_cache' => EnvelopeDecorator::class,
            'my_iron_cache' => IronDecorator::class,
        ];

        foreach ($cacheServices as $cacheService => $excpectedClass) {
            $this->assertTrue($container->has($cacheService));
            $this->assertInstanceOf($excpectedClass, $container->get($cacheService));
        }
    }

    public static function configFormatProvider()
    {
        return [
            ['yml'],
            ['xml'],
            ['php'],
        ];
    }

    public function testExtensionShouldEscapeStringsThatBeginWithAtSign()
    {
        $pass = new CacheEncrypterCompilerPass;
        $reflPass = new \ReflectionClass(CacheEncrypterCompilerPass::class);
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
        $reflPass = new \ReflectionClass(CacheEncrypterCompilerPass::class);
        $inflationMethod = $reflPass->getMethod('inflateServicesInTag');
        $inflationMethod->setAccessible(true);

        $config = ['foo' => [
            'bar' => '@bar',
            'baz' => '@baz'
        ]];
        $inflationMethod->invokeArgs($pass, [&$config]);

        foreach (['bar', 'baz'] as $ref) {
            $this->assertInstanceOf(
                Reference::class,
                $config['foo'][$ref]
            );
            $this->assertSame($ref, (string) $config['foo'][$ref]);
        }
    }

    /**
     * @expectedException \DomainException
     */
    public function testShouldValidateTagParameters()
    {
        $pass = new CacheEncrypterCompilerPass;
        $reflPass = new \ReflectionClass(CacheEncrypterCompilerPass::class);
        $builderMethod = $reflPass->getMethod('buildDecoratorDefinition');
        $builderMethod->setAccessible(true);

        $builderMethod->invoke($pass, 'a_cache_service', ['foo' => 'bar']);
    }
}
