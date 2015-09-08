<?php

use Symfony\Component\DependencyInjection\Reference;

$container->loadFromExtension('framework', array(
    'secret' =>  'Roger Kint is Keyser Soze.',
));

$container->register('key_factory', 'KeyFactory');

$container->register('public_keys_to_encrypt_against', 'Array')
    ->setPublic(false)
    ->setFactoryService('key_factory')
    ->setFactoryMethod('getCertificates');

$container->register('my_private_key', 'String')
    ->setPublic(false)
    ->setFactoryService('key_factory')
    ->setFactoryMethod('getPrivateKey');
