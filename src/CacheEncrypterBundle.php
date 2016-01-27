<?php
namespace Jsq\Cache;

use Jsq\Cache\DependencyInjection\Compiler\CacheEncrypterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class CacheEncrypterBundle extends ContainerAware implements BundleInterface
{
    const VERSION = '0.3.0';

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CacheEncrypterCompilerPass);
    }

    public function getContainerExtension() {}

    public function getParent() {}

    public function boot() {}

    public function shutdown() {}

    public function getPath()
    {
        return __DIR__;
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getName()
    {
        return str_replace(__NAMESPACE__.'\\', '', __CLASS__);
    }
}
