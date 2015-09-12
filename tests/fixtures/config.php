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

$container->register('cache', 'Doctrine\Common\Cache\ArrayCache')
    ->addTag('cache.encrypted', [
        'password' => 'a',
        'cipher' => 'aes-256-cfb',
    ])
    ->addTag('cache.encrypted', [
        'alias' => 'my_encrypted_cache',
        'password' => 'b',
    ])
    ->addTag('cache.encrypted', [
        'alias' => 'my_pki_encrypted_cache',
        'certificates' => '@public_keys_to_encrypt_against',
        'key' => '@my_private_key',
        'cipher' => 'aes-192-ecb',
    ]);
