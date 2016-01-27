<?php
namespace Jsq\Cache\DependencyInjection\Compiler;

use Jsq\Cache\EnvelopeEncryption\Decorator as EnvelopeEncryptionDecorator;
use Jsq\Cache\PasswordEncryption\Decorator as PasswordEncryptionDecorator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CacheEncrypterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cache.encrypted');

        foreach ($taggedServices as $id => $tags) {
            $this->inflateServicesInTag($tags);

            foreach ($tags as $tag) {
                $container->setDefinition(
                    isset($tag['alias']) ? $tag['alias'] : "$id.encrypted",
                    $this->buildDecoratorDefinition($id, $tag)
                );
            }
        }
    }

    private function buildDecoratorDefinition($decorated, array $tags)
    {
        if ($this->arrayHasKeys($tags, ['certificate', 'key'])) {
            return $this->buildPkiDecoratorDefinition($decorated, $tags);
        }

        return $this->buildPasswordDecoratorDefinition($decorated, $tags);
    }

    private function buildPasswordDecoratorDefinition($decorated, array $tags)
    {
        if (empty($tags['password'])) {
            throw new \DomainException('Cannot encrypt a cache'
                . ' with an empty password.');
        }

        $args = [new Reference($decorated), $tags['password']];
        if (isset($tags['cipher'])) {
            $args []= $tags['cipher'];
        }

        return new Definition(PasswordEncryptionDecorator::class, $args);
    }

    private function buildPkiDecoratorDefinition($decorated, array $tag)
    {
        $args = [new Reference($decorated), $tag['certificate'], $tag['key']];
        foreach (['password', 'cipher'] as $optionalParameter) {
            if (isset($tag[$optionalParameter])) {
                $args []= $tag[$optionalParameter];
            }
        }

        return new Definition(EnvelopeEncryptionDecorator::class, $args);
    }

    private function arrayHasKeys(array $array, array $keys)
    {
        foreach ($keys as $key) {
            if (!isset($array[$key])) {
                return false;
            }
        }

        return true;
    }

    private function inflateServicesInTag(array &$config)
    {
        array_walk($config, function (&$value) {
            if (is_array($value)) {
                $this->inflateServicesInTag($value);
            }

            if (is_string($value) && 0 === strpos($value, '@')) {
                // this is either a service reference or a string meant to
                // start with an '@' symbol. In any case, lop off the first '@'
                $value = substr($value, 1);
                if (0 !== strpos($value, '@')) {
                    // this is a service reference, not a string literal
                    $value = new Reference($value);
                }
            }
        });
    }
}
