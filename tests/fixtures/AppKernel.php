<?php

use Jsq\Cache\CacheEncrypterBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    private $extension;

    public function __construct($env, $debug, $extension = 'yml')
    {
        $this->extension = $extension;
        parent::__construct($env, $debug);
    }

    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new CacheEncrypterBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getTestConfigFile($this->extension));
    }


    private function getTestConfigFile($extension)
    {
        return __DIR__ . '/config.' . $extension;
    }
}
