<?php
namespace Jsq\Cache;

class CacheEncrypterBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPathReturnsValidPath()
    {
        $this->assertTrue(file_exists((new CacheEncrypterBundle)->getPath()));
    }

    public function testGetNameShouldReturnShortPath()
    {
        $bundle = new CacheEncrypterBundle;
        $reflBundle = new \ReflectionClass($bundle);

        $this->assertSame($reflBundle->getShortName(), $bundle->getName());
    }

    public function testGetNamespaceShouldReturnNamespace()
    {
        $this->assertSame(__NAMESPACE__, (new CacheEncrypterBundle)->getNamespace());
    }
}
